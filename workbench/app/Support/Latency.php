<?php

declare(strict_types=1);

namespace Workbench\App\Support;

/**
 * Slows the demo down so the skeletons and the page loader stay on screen long enough to see.
 * WORKBENCH_LATENCY_MS sets the delay (1500 by default; 0 turns it off).
 */
final class Latency
{
    public static function wait(float $factor = 1.0): void
    {
        $milliseconds = (int) (((int) (getenv('WORKBENCH_LATENCY_MS') ?: 1500)) * $factor);

        if ($milliseconds > 0) {
            usleep($milliseconds * 1000);
        }
    }
}
