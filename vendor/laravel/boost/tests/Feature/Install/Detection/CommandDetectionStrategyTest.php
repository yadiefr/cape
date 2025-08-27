<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Process;
use Laravel\Boost\Install\Detection\CommandDetectionStrategy;
use Laravel\Boost\Install\Enums\Platform;

beforeEach(function () {
    $this->strategy = new CommandDetectionStrategy();
});

test('detects command with successful exit code', function () {
    Process::fake([
        'which php' => Process::result(exitCode: 0),
    ]);

    $result = $this->strategy->detect([
        'command' => 'which php',
    ]);

    expect($result)->toBeTrue();
});

test('fails for command with non zero exit code', function () {
    Process::fake([
        'which nonexistent' => Process::result(exitCode: 1),
    ]);

    $result = $this->strategy->detect([
        'command' => 'which nonexistent',
    ]);

    expect($result)->toBeFalse();
});

test('returns false when no command config', function () {
    $result = $this->strategy->detect([
        'other_config' => 'value',
    ]);

    expect($result)->toBeFalse();
});

test('handles command with output', function () {
    Process::fake([
        'echo test' => Process::result(output: 'test', exitCode: 0),
    ]);

    $result = $this->strategy->detect([
        'command' => 'echo test',
    ]);

    expect($result)->toBeTrue();
});

test('handles command with error output', function () {
    Process::fake([
        'invalid-command' => Process::result(errorOutput: 'command not found', exitCode: 127),
    ]);

    $result = $this->strategy->detect([
        'command' => 'invalid-command',
    ]);

    expect($result)->toBeFalse();
});

test('works with different platforms parameter', function () {
    Process::fake([
        'where code' => Process::result(exitCode: 0),
    ]);

    $result = $this->strategy->detect([
        'command' => 'where code',
    ], Platform::Windows);

    expect($result)->toBeTrue();
});
