<?php

declare(strict_types=1);

namespace Laravel\Boost\Contracts;

/**
 * Agent contract for AI coding assistants that receive guidelines.
 */
interface Agent
{
    /**
     * Get the display name of the Agent.
     *
     * @return string|null
     */
    public function agentName(): ?string;

    /**
     * Get the file path where AI guidelines should be written.
     *
     * @return string The relative or absolute path to the guideline file
     */
    public function guidelinesPath(): string;

    /**
     * Determine if the guideline file requires frontmatter.
     *
     * @return bool
     */
    public function frontmatter(): bool;
}
