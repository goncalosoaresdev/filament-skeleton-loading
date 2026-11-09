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
        $delay = getenv('WORKBENCH_LATENCY_MS');
        $milliseconds = (int) (($delay === false ? 1500 : (int) $delay) * $factor);

        if ($milliseconds > 0) {
            usleep($milliseconds * 1000);
        }
    }
}
