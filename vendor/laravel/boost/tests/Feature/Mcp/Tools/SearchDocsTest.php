<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Laravel\Boost\Mcp\Tools\SearchDocs;
use Laravel\Mcp\Server\Tools\ToolResult;
use Laravel\Roster\Enums\Packages;
use Laravel\Roster\Package;
use Laravel\Roster\PackageCollection;
use Laravel\Roster\Roster;

test('it searches documentation successfully', function () {
    $packages = new PackageCollection([
        new Package(Packages::LARAVEL, 'laravel/framework', '11.0.0'),
        new Package(Packages::PEST, 'pestphp/pest', '2.0.0'),
    ]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Documentation search results', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['authentication', 'testing']]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeFalse()
        ->and($data['content'][0]['text'])->toBe('Documentation search results');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://boost.laravel.com/api/docs' &&
               $request->data()['queries'] === ['authentication', 'testing'] &&
               $request->data()['packages'] === [
                   ['name' => 'laravel/framework', 'version' => '11.x'],
                   ['name' => 'pestphp/pest', 'version' => '2.x'],
               ] &&
               $request->data()['token_limit'] === 10000 &&
               $request->data()['format'] === 'markdown';
    });
});

test('it handles API error response', function () {
    $packages = new PackageCollection([
        new Package(Packages::LARAVEL, 'laravel/framework', '11.0.0'),
    ]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('API Error', 500),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['authentication']]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeTrue()
        ->and($data['content'][0]['text'])->toBe('Failed to search documentation: API Error');
});

test('it filters empty queries', function () {
    $packages = new PackageCollection([]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Empty results', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['test', '  ', '*', ' ']]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeFalse();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://boost.laravel.com/api/docs' &&
               $request->data()['queries'] === ['test'] &&
               empty($request->data()['packages']) &&
               $request->data()['token_limit'] === 10000;
    });
});

test('it formats package data correctly', function () {
    $packages = new PackageCollection([
        new Package(Packages::LARAVEL, 'laravel/framework', '11.0.0'),
        new Package(Packages::LIVEWIRE, 'livewire/livewire', '3.5.1'),
    ]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Package data results', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['test']]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    Http::assertSent(function ($request) {
        return $request->data()['packages'] === [
            ['name' => 'laravel/framework', 'version' => '11.x'],
            ['name' => 'livewire/livewire', 'version' => '3.x'],
        ] && $request->data()['token_limit'] === 10000;
    });
});

test('it handles empty results', function () {
    $packages = new PackageCollection([]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Empty response', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['nonexistent']]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    $data = $result->toArray();
    expect($data['isError'])->toBeFalse()
        ->and($data['content'][0]['text'])->toBe('Empty response');
});

test('it uses custom token_limit when provided', function () {
    $packages = new PackageCollection([]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Custom token limit results', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['test'], 'token_limit' => 5000]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    Http::assertSent(function ($request) {
        return $request->data()['token_limit'] === 5000;
    });
});

test('it caps token_limit at maximum of 1000000', function () {
    $packages = new PackageCollection([]);

    $roster = Mockery::mock(Roster::class);
    $roster->shouldReceive('packages')->andReturn($packages);

    Http::fake([
        'https://boost.laravel.com/api/docs' => Http::response('Capped token limit results', 200),
    ]);

    $tool = new SearchDocs($roster);
    $result = $tool->handle(['queries' => ['test'], 'token_limit' => 2000000]);

    expect($result)->toBeInstanceOf(ToolResult::class);

    Http::assertSent(function ($request) {
        return $request->data()['token_limit'] === 1000000;
    });
});
