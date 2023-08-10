<?php

namespace App\Support\Services;

use App\Support\Contracts\TimeTrackingInterface;
use Closure;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

/**
 * Class TimeTrackingService
 *
 * A service class for tracking execution time of specific tasks.
 *
 * @package App\Support\Services
 * @author Shakil Alam <itxshakil@gmail.com>
 */
class TimeTrackingService implements TimeTrackingInterface
{
    /** @var array Holds the tracked items */
    protected array $items = [];

    /**
     * Start tracking the execution time of a task.
     *
     * @param  string|null  $label  A label to identify the tracked task (optional)
     * @return string The unique label associated with the tracked task
     */
    public function track(string $label = 'default'): string
    {
        $trackNumber = $this->generateLabel($label);

        $this->items[$trackNumber] = [
            'track_number' => $trackNumber,
            'label' => $label,
            'start' => microtime(true),
            'end' => null,
        ];

        return $trackNumber;
    }

    /**
     * Generate a unique label for the tracked task.
     *
     * @param  string|null  $label  A label to base the unique label on
     * @return string The generated unique label
     */
    private function generateLabel(string $label = 'default'): string
    {
        $label = $label.'_'.uniqid();
        while (array_key_exists($label, $this->items)) {
            $label = $label.'_'.uniqid();
        }

        return $label;
    }

    /**
     * End the tracking of a specific task.
     *
     * @param  string  $trackNumber  The unique label of the tracked task
     * @param  int  $threshold  The threshold in milliseconds for triggering a critical log (optional)
     * @param  Closure|null  $closure  A closure to be executed after ending tracking (optional)
     * @return void
     */
    public function endTrack(string $trackNumber, int $threshold = 1000, Closure $closure = null): void
    {
        $track = $this->find($trackNumber);

        if (!$track) {
            $this->logger()->warning('Time Tracking: '.$trackNumber.' not found.');
        }

        if ($track['end']) {
            $this->logger()->warning('Time Tracking: '.$trackNumber.' already ended.');
        }

        $this->items[$trackNumber]['end'] = microtime(true);
        $track = $this->items[$trackNumber];

        if ($closure) {
            $closure($this->find($trackNumber));
        }

        $label = $track['label'];
        $timeTaken = $track['end'] - $track['start'];

        $formattedTime = $this->formatTime($timeTaken);

        $this->logger()->info('Time Tracking: '.$label.' took '.$formattedTime.' ms');

        if ($formattedTime > $threshold) {
            $this->logger()->critical('Time Tracking: '.$label.' took '.$formattedTime.' ms');
        }
    }

    /**
     * Find a tracked item by its label.
     *
     * @param  string  $label  The label of the tracked task
     * @return array|null The tracked item data or null if not found
     */
    private function find(string $label): ?array
    {
        return $this->items[$label] ?? null;
    }

    /**
     * Get the logger instance.
     *
     * @return LoggerInterface The logger instance
     */
    protected function logger(): LoggerInterface
    {
        return Log::channel('time-track');
    }

    /**
     * Format the time taken to a specified number of decimal places.
     *
     * @param  float  $timeTaken  The time taken in seconds
     * @return float The formatted time
     */
    private function formatTime(float $timeTaken): float
    {
        return round($timeTaken, 2);
    }
}
