<?php

declare(strict_types=1);

namespace Tests\Unit\Install\CodeEnvironment;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Laravel\Boost\Contracts\Agent;
use Laravel\Boost\Contracts\McpClient;
use Laravel\Boost\Install\CodeEnvironment\CodeEnvironment;
use Laravel\Boost\Install\Contracts\DetectionStrategy;
use Laravel\Boost\Install\Detection\DetectionStrategyFactory;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;
use Mockery;

beforeEach(function () {
    $this->strategyFactory = Mockery::mock(DetectionStrategyFactory::class);
    $this->strategy = Mockery::mock(DetectionStrategy::class);
});

afterEach(function () {
    Mockery::close();
});

// Create a concrete test implementation for testing abstract methods
class TestCodeEnvironment extends CodeEnvironment
{
    public function name(): string
    {
        return 'test';
    }

    public function displayName(): string
    {
        return 'Test Environment';
    }

    public function systemDetectionConfig(Platform $platform): array
    {
        return ['paths' => ['/test/path']];
    }

    public function projectDetectionConfig(): array
    {
        return ['files' => ['test.config']];
    }
}

class TestAgent extends TestCodeEnvironment implements Agent
{
    public function guidelinesPath(): string
    {
        return 'test-guidelines.md';
    }
}

class TestMcpClient extends TestCodeEnvironment implements McpClient
{
    public function mcpConfigPath(): string
    {
        return '.test/mcp.json';
    }
}

class TestAgentAndMcpClient extends TestCodeEnvironment implements Agent, McpClient
{
    public function guidelinesPath(): string
    {
        return 'test-guidelines.md';
    }

    public function mcpConfigPath(): string
    {
        return '.test/mcp.json';
    }
}

test('detectOnSystem delegates to strategy factory and detection strategy', function () {
    $platform = Platform::Darwin;
    $config = ['paths' => ['/test/path']];

    $this->strategyFactory
        ->shouldReceive('makeFromConfig')
        ->once()
        ->with($config)
        ->andReturn($this->strategy);

    $this->strategy
        ->shouldReceive('detect')
        ->once()
        ->with($config, $platform)
        ->andReturn(true);

    $environment = new TestCodeEnvironment($this->strategyFactory);
    $result = $environment->detectOnSystem($platform);

    expect($result)->toBe(true);
});

test('detectInProject merges config with basePath and delegates to strategy', function () {
    $basePath = '/project/path';
    $projectConfig = ['files' => ['test.config']];
    $mergedConfig = ['files' => ['test.config'], 'basePath' => $basePath];

    $this->strategyFactory
        ->shouldReceive('makeFromConfig')
        ->once()
        ->with($mergedConfig)
        ->andReturn($this->strategy);

    $this->strategy
        ->shouldReceive('detect')
        ->once()
        ->with($mergedConfig)
        ->andReturn(false);

    $environment = new TestCodeEnvironment($this->strategyFactory);
    $result = $environment->detectInProject($basePath);

    expect($result)->toBe(false);
});

test('agentName returns displayName by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->agentName())->toBe('Test Environment');
});

test('mcpClientName returns displayName by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->mcpClientName())->toBe('Test Environment');
});

test('IsAgent returns true when implements Agent interface and has agentName', function () {
    $agent = new TestAgent($this->strategyFactory);

    expect($agent->IsAgent())->toBe(true);
});

test('IsAgent returns false when does not implement Agent interface', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->IsAgent())->toBe(false);
});

test('isMcpClient returns true when implements McpClient interface and has mcpClientName', function () {
    $mcpClient = new TestMcpClient($this->strategyFactory);

    expect($mcpClient->isMcpClient())->toBe(true);
});

test('isMcpClient returns false when does not implement McpClient interface', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->isMcpClient())->toBe(false);
});

test('mcpInstallationStrategy returns File by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->mcpInstallationStrategy())->toBe(McpInstallationStrategy::FILE);
});

test('shellMcpCommand returns null by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->shellMcpCommand())->toBe(null);
});

test('mcpConfigPath returns null by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->mcpConfigPath())->toBe(null);
});

test('frontmatter returns false by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->frontmatter())->toBe(false);
});

test('mcpConfigKey returns mcpServers by default', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    expect($environment->mcpConfigKey())->toBe('mcpServers');
});

test('installMcp uses Shell strategy when configured', function () {
    $environment = Mockery::mock(TestCodeEnvironment::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::SHELL);

    $environment->shouldReceive('installShellMcp')
        ->once()
        ->with('test-key', 'test-command', ['arg1'], ['ENV' => 'value'])
        ->andReturn(true);

    $result = $environment->installMcp('test-key', 'test-command', ['arg1'], ['ENV' => 'value']);

    expect($result)->toBe(true);
});

test('installMcp uses File strategy when configured', function () {
    $environment = Mockery::mock(TestCodeEnvironment::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::FILE);

    $environment->shouldReceive('installFileMcp')
        ->once()
        ->with('test-key', 'test-command', ['arg1'], ['ENV' => 'value'])
        ->andReturn(true);

    $result = $environment->installMcp('test-key', 'test-command', ['arg1'], ['ENV' => 'value']);

    expect($result)->toBe(true);
});

test('installMcp returns false for None strategy', function () {
    $environment = Mockery::mock(TestCodeEnvironment::class)->makePartial();

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::NONE);

    $result = $environment->installMcp('test-key', 'test-command');

    expect($result)->toBe(false);
});

test('installShellMcp returns false when shellMcpCommand is null', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    $result = $environment->installMcp('test-key', 'test-command');

    expect($result)->toBe(false);
});

test('installShellMcp executes command with placeholders replaced', function () {
    $environment = Mockery::mock(TestCodeEnvironment::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('shellMcpCommand')
        ->andReturn('install {key} {command} {args} {env}');

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::SHELL);

    $mockResult = Mockery::mock();
    $mockResult->shouldReceive('successful')->andReturn(true);
    $mockResult->shouldReceive('errorOutput')->andReturn('');

    Process::shouldReceive('run')
        ->once()
        ->with(Mockery::on(function ($command) {
            return str_contains($command, 'install test-key test-command "arg1" "arg2"') &&
                   str_contains($command, '-e ENV1="value1"') &&
                   str_contains($command, '-e ENV2="value2"');
        }))
        ->andReturn($mockResult);

    $result = $environment->installMcp('test-key', 'test-command', ['arg1', 'arg2'], ['env1' => 'value1', 'env2' => 'value2']);

    expect($result)->toBe(true);
});

test('installShellMcp returns true when process fails but has already exists error', function () {
    $environment = Mockery::mock(TestCodeEnvironment::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('shellMcpCommand')
        ->andReturn('install {key}');

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::SHELL);

    $mockResult = Mockery::mock();
    $mockResult->shouldReceive('successful')->andReturn(false);
    $mockResult->shouldReceive('errorOutput')->andReturn('Error: already exists');

    Process::shouldReceive('run')
        ->once()
        ->andReturn($mockResult);

    $result = $environment->installMcp('test-key', 'test-command');

    expect($result)->toBe(true);
});

test('installFileMcp returns false when mcpConfigPath is null', function () {
    $environment = new TestCodeEnvironment($this->strategyFactory);

    $result = $environment->installMcp('test-key', 'test-command');

    expect($result)->toBe(false);
});

test('installFileMcp creates new config file when none exists', function () {
    $environment = Mockery::mock(TestMcpClient::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::FILE);

    File::shouldReceive('ensureDirectoryExists')
        ->once()
        ->with('.test');

    File::shouldReceive('exists')
        ->once()
        ->with('.test/mcp.json')
        ->andReturn(false);

    File::shouldReceive('put')
        ->once()
        ->with('.test/mcp.json', Mockery::type('string'))
        ->andReturn(true);

    $result = $environment->installMcp('test-key', 'test-command', ['arg1'], ['ENV' => 'value']);

    expect($result)->toBe(true);
});

test('installFileMcp updates existing config file', function () {
    $environment = Mockery::mock(TestMcpClient::class)->makePartial();
    $environment->shouldAllowMockingProtectedMethods();

    $environment->shouldReceive('mcpInstallationStrategy')
        ->andReturn(McpInstallationStrategy::FILE);

    $existingConfig = json_encode(['mcpServers' => ['existing' => ['command' => 'existing-cmd']]]);

    File::shouldReceive('ensureDirectoryExists')
        ->once()
        ->with('.test');

    File::shouldReceive('exists')
        ->once()
        ->with('.test/mcp.json')
        ->andReturn(true);

    File::shouldReceive('get')
        ->once()
        ->with('.test/mcp.json')
        ->andReturn($existingConfig);

    File::shouldReceive('put')
        ->once()
        ->with('.test/mcp.json', Mockery::on(function ($json) {
            $config = json_decode($json, true);

            return isset($config['mcpServers']['test-key']) &&
                   isset($config['mcpServers']['existing']);
        }))
        ->andReturn(true);

    $result = $environment->installMcp('test-key', 'test-command', ['arg1'], ['ENV' => 'value']);

    expect($result)->toBe(true);
});
