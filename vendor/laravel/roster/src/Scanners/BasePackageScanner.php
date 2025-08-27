<?php

namespace Laravel\Roster\Scanners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravel\Roster\Approach;
use Laravel\Roster\Enums\Approaches;
use Laravel\Roster\Enums\Packages;
use Laravel\Roster\Package;

abstract class BasePackageScanner
{
    /**
     * Map of package names to enums
     *
     * @var array<string, Packages|Approaches|array<int, Packages|Approaches>>
     */
    protected array $map = [
        'alpinejs' => Packages::ALPINEJS,
        '@inertiajs/react' => [Packages::INERTIA, Packages::INERTIA_REACT],
        '@inertiajs/svelte' => [Packages::INERTIA, Packages::INERTIA_SVELTE],
        '@inertiajs/vue3' => [Packages::INERTIA, Packages::INERTIA_VUE],
        'laravel-echo' => Packages::ECHO,
        '@laravel/vite-plugin-wayfinder' => [Packages::WAYFINDER, Packages::WAYFINDER_VITE],
        'react' => Packages::REACT,
        'tailwindcss' => [Packages::TAILWINDCSS],
        'vue' => Packages::VUE,
    ];

    public function __construct(protected string $path) {}

    /**
     * @return \Illuminate\Support\Collection<int, \Laravel\Roster\Package|\Laravel\Roster\Approach>
     */
    abstract public function scan(): Collection;

    /**
     * Check if the scanner can handle the given path
     */
    abstract public function canScan(): bool;

    /**
     * Process dependencies and add them to the mapped items collection
     *
     * @param  array<string, string>  $dependencies
     * @param  Collection<int, Package|Approach>  $mappedItems
     */
    protected function processDependencies(array $dependencies, Collection $mappedItems, bool $isDev): void
    {
        foreach ($dependencies as $packageName => $version) {
            $mappedPackage = $this->map[$packageName] ?? null;
            if (is_null($mappedPackage)) {
                continue;
            }

            if (! is_array($mappedPackage)) {
                $mappedPackage = [$mappedPackage];
            }

            foreach ($mappedPackage as $mapped) {
                $niceVersion = preg_replace('/[^0-9.]/', '', $version) ?? '';
                $mappedItems->push(match (get_class($mapped)) {
                    Packages::class => new Package($mapped, $packageName, $niceVersion, $isDev),
                    Approaches::class => new Approach($mapped),
                    default => throw new \InvalidArgumentException('Unsupported mapping')
                });
            }
        }
    }

    /**
     * Common file validation logic
     */
    protected function validateFile(string $path, string $type = 'Package'): ?string
    {
        if (! file_exists($path)) {
            Log::warning("Failed to scan $type: $path");

            return null;
        }

        if (! is_readable($path)) {
            Log::warning("File not readable: $path");

            return null;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            Log::warning("Failed to read $type: $path");

            return null;
        }

        return $contents;
    }
}
