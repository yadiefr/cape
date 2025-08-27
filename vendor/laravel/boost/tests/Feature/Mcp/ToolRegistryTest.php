<?php

use Laravel\Boost\Mcp\ToolRegistry;
use Laravel\Boost\Mcp\Tools\ApplicationInfo;

test('can discover available tools', function () {
    $tools = ToolRegistry::getAvailableTools();

    expect($tools)->toBeArray()
        ->and($tools)->toContain(ApplicationInfo::class);
});

test('can check if tool is allowed', function () {
    expect(ToolRegistry::isToolAllowed(ApplicationInfo::class))->toBeTrue();
    expect(ToolRegistry::isToolAllowed('NonExistentTool'))->toBeFalse();
});

test('can get tool names', function () {
    $tools = ToolRegistry::getToolNames();

    expect($tools)->toBeArray()
        ->and($tools)->toHaveKey('ApplicationInfo')
        ->and($tools['ApplicationInfo'])->toBe(ApplicationInfo::class);
});

test('can clear cache', function () {
    // First call caches the results
    $tools1 = ToolRegistry::getAvailableTools();

    // Clear cache
    ToolRegistry::clearCache();

    // Second call should work fine
    $tools2 = ToolRegistry::getAvailableTools();

    expect($tools1)->toEqual($tools2);
});
