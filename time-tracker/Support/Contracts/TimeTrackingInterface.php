<?php

namespace App\Support\Contracts;

use Closure;

/**
 * Interface TimeTrackingInterface
 *
 * Defines the contract for a time tracking service.
 *
 * @package App\Support\Services
 * @author Shakil Alam <itxshakil@gmail.com>
 */
interface TimeTrackingInterface
{
    /**
     * Start tracking the execution time of a task.
     *
     * @param  string|null  $label  A label to identify the tracked task (optional)
     * @return string The unique label associated with the tracked task
     */
    public function track(string $label = 'default'): string;

    /**
     * End the tracking of a specific task.
     *
     * @param  string  $trackNumber  The unique label of the tracked task
     * @param  int  $threshold  The threshold in milliseconds for triggering a critical log (optional)
     * @param  Closure|null  $closure  A closure to be executed after ending tracking (optional)
     * @return void
     */
    public function endTrack(
        string $trackNumber,
        int $threshold = 1000,
        Closure $closure = null
    ): void;
}
