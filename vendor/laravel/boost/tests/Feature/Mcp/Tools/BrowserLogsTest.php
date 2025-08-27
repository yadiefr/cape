<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Boost\Mcp\Tools\BrowserLogs;
use Laravel\Boost\Middleware\InjectBoost;
use Laravel\Boost\Services\BrowserLogger;
use Laravel\Mcp\Server\Tools\ToolResult;

beforeEach(function () {
    // Clean up any existing browser.log file before each test
    $logFile = storage_path('logs/browser.log');
    if (File::exists($logFile)) {
        File::delete($logFile);
    }
});

test('it returns log entries when file exists', function () {
    // Create a fake browser.log file with some entries
    $logFile = storage_path('logs/browser.log');
    File::ensureDirectoryExists(dirname($logFile));

    $logContent = <<<'LOG'
[2024-01-15 10:00:00] browser.DEBUG: console log message {"url":"http://example.com","user_agent":"Mozilla/5.0","timestamp":"2024-01-15T10:00:00.000000Z"}
[2024-01-15 10:01:00] browser.ERROR: JavaScript error occurred {"url":"http://example.com/page","user_agent":"Mozilla/5.0","timestamp":"2024-01-15T10:01:00.000000Z"}
[2024-01-15 10:02:00] browser.WARNING: Warning message {"url":"http://example.com/other","user_agent":"Mozilla/5.0","timestamp":"2024-01-15T10:02:00.000000Z"}
LOG;

    File::put($logFile, $logContent);

    $tool = new BrowserLogs;
    $result = $tool->handle(['entries' => 2]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeFalse();
    expect($data['content'][0]['type'])->toBe('text');

    $text = $data['content'][0]['text'];
    expect($text)->toContain('browser.WARNING: Warning message');
    expect($text)->toContain('browser.ERROR: JavaScript error occurred');
    expect($text)->not->toContain('browser.DEBUG: console log message');
});

test('it returns error when entries argument is invalid', function () {
    $tool = new BrowserLogs;

    // Test with zero
    $result = $tool->handle(['entries' => 0]);
    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeTrue();
    expect($data['content'][0]['text'])->toBe('The "entries" argument must be greater than 0.');

    // Test with negative
    $result = $tool->handle(['entries' => -5]);
    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeTrue();
    expect($data['content'][0]['text'])->toBe('The "entries" argument must be greater than 0.');
});

test('it returns error when log file does not exist', function () {
    $tool = new BrowserLogs;
    $result = $tool->handle(['entries' => 10]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeTrue();
    expect($data['content'][0]['text'])->toBe('No log file found, probably means no logs yet.');
});

test('it returns error when log file is empty', function () {
    // Create an empty browser.log file
    $logFile = storage_path('logs/browser.log');
    File::ensureDirectoryExists(dirname($logFile));
    File::put($logFile, '');

    $tool = new BrowserLogs;
    $result = $tool->handle(['entries' => 5]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeFalse();
    expect($data['content'][0]['text'])->toBe('Unable to retrieve log entries, or no logs');
});

test('@boostJs blade directive renders browser logger script', function () {
    // Ensure route exists
    Route::post('/_boost/browser-logs', function () {
    })->name('boost.browser-logs');

    $blade = Blade::compileString('@boostJs');

    expect($blade)->toBe('<?php echo \\Laravel\\Boost\\Services\\BrowserLogger::getScript(); ?>');

    // Test that the script contains expected content
    $script = BrowserLogger::getScript();
    expect($script)->toContain('browser-logger-active');
    expect($script)->toContain('/_boost/browser-logs');
    expect($script)->toContain('console.log');
    expect($script)->toContain('console.error');
    expect($script)->toContain('window.onerror');
});

test('browser logs endpoint processes logs correctly', function () {
    Log::shouldReceive('channel')
        ->with('browser')
        ->andReturn($logger = Mockery::mock(\Illuminate\Log\Logger::class));

    $logger->shouldReceive('write')
        ->once()
        ->with('debug', 'Test message', [
            'url' => 'http://example.com',
            'user_agent' => 'Mozilla/5.0',
            'timestamp' => '2024-01-15T10:00:00.000Z',
        ]);

    $logger->shouldReceive('write')
        ->once()
        ->with('error', 'Error occurred', [
            'url' => 'http://example.com/error',
            'user_agent' => 'Chrome/96',
            'timestamp' => '2024-01-15T10:01:00.000Z',
        ]);

    $response = $this->postJson('/_boost/browser-logs', [
        'logs' => [
            [
                'type' => 'log',
                'timestamp' => '2024-01-15T10:00:00.000Z',
                'data' => ['Test message'],
                'url' => 'http://example.com',
                'userAgent' => 'Mozilla/5.0',
            ],
            [
                'type' => 'error',
                'timestamp' => '2024-01-15T10:01:00.000Z',
                'data' => ['Error occurred'],
                'url' => 'http://example.com/error',
                'userAgent' => 'Chrome/96',
            ],
        ],
    ]);

    $response->assertOk();
    $response->assertJson(['status' => 'logged']);
});

test('browser logs endpoint handles complex nested data', function () {
    $this->withoutExceptionHandling();

    Log::shouldReceive('channel')
        ->with('browser')
        ->andReturn($logger = Mockery::mock(\Illuminate\Log\Logger::class));

    $logger->shouldReceive('write')
        ->once()
        ->with('error', 'Unhandled Promise Rejection TypeError NetworkError when attempting to fetch resource. null', Mockery::any());

    $response = $this->postJson('/_boost/browser-logs', [
        'logs' => [
            [
                'type' => 'unhandled_rejection',
                'timestamp' => '2024-01-15T10:00:00.000Z',
                'data' => [
                    [
                        'message' => 'Unhandled Promise Rejection',
                        'reason' => [
                            'name' => 'TypeError',
                            'message' => 'NetworkError when attempting to fetch resource.',
                            'stack' => '',
                        ],
                    ],
                ],
                'url' => 'http://example.com',
                'userAgent' => 'Mozilla/5.0',
            ],
        ],
    ]);

    $response->assertOk();
});

test('InjectBoost middleware injects script into HTML response', function () {
    $middleware = new InjectBoost;

    $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Hello World</h1>
</body>
</html>
HTML;

    $request = Request::create('/');
    $response = new \Illuminate\Http\Response($html, 200, ['Content-Type' => 'text/html']);

    $result = $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });

    $content = $result->getContent();
    expect($content)->toContain('browser-logger-active');
    expect($content)->toContain('</head>');
    expect(substr_count($content, 'browser-logger-active'))->toBe(1); // Should not inject twice
});

test('InjectBoost middleware does not inject into non-HTML responses', function () {
    $middleware = new InjectBoost;

    $json = json_encode(['status' => 'ok']);

    $request = Request::create('/');
    $response = new \Illuminate\Http\Response($json);

    $result = $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });

    $content = $result->getContent();
    expect($content)->toBe($json);
    expect($content)->not->toContain('browser-logger-active');
});

test('InjectBoost middleware does not inject script twice', function () {
    $middleware = new InjectBoost;

    $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <script id="browser-logger-active">// Already injected</script>
</head>
<body>
    <h1>Hello World</h1>
</body>
</html>
HTML;

    $request = Request::create('/');
    $response = new \Illuminate\Http\Response($html);

    $result = $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });

    $content = $result->getContent();
    expect(substr_count($content, 'browser-logger-active'))->toBe(1);
});

test('InjectBoost middleware injects before body tag when no head tag', function () {
    $middleware = new InjectBoost;

    $html = <<<'HTML'
<!DOCTYPE html>
<html>
<body>
    <h1>Hello World</h1>
</body>
</html>
HTML;

    $request = Request::create('/');
    $response = new \Illuminate\Http\Response($html, 200, ['Content-Type' => 'text/html']);

    $result = $middleware->handle($request, function ($req) use ($response) {
        return $response;
    });

    $content = $result->getContent();
    expect($content)->toContain('browser-logger-active');
    expect($content)->toMatch('/<script[^>]*browser-logger-active[^>]*>.*<\/script>\s*<\/body>/s');
});
