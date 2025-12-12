<?php

declare(strict_types=1);

namespace App\Console\Commands\Advent_2024;

use Illuminate\Console\Command;

class Advent_2024_11 extends Command
{
    protected $signature = 'advent:2024:11 {--debug} {--stdin}';

    protected $description = '2024 - Day 11: Plutonian Pebbles';

    private string $data = <<<'TEXT'
125 17
TEXT;

    /**
     * Credits to ChatGPT for coming up with the idea of grouping similar stones, so we do not run into memory issues.
     */
    private array $stoneCounts = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $stones = array_map('intval', explode(' ', $data));
        $this->stoneCounts = array_count_values($stones);

        for ($i = 0; $i < 25; $i++) {
            $this->blink();
        }

        $this->info('Amount of stones after blinking 25 times: ' . array_sum($this->stoneCounts));

        for ($i = 0; $i < 50; $i++) {
            $this->blink();
        }

        $this->info('Amount of stones after blinking 75 times: ' . array_sum($this->stoneCounts));
    }

    private function blink(): void
    {
        $newCounts = [];

        foreach ($this->stoneCounts as $stone => $count) {
            if ($stone === 0) {
                $newCounts[1] = ($newCounts[1] ?? 0) + $count;
                continue;
            }

            $length = (int) floor(log10($stone) + 1);
            if ($length % 2 === 0) {
                $stoneString = (string) $stone;
                $part1 = (int) substr($stoneString, 0, $length / 2);
                $part2 = (int) substr($stoneString, $length / 2);

                $newCounts[$part1] = ($newCounts[$part1] ?? 0) + $count;
                $newCounts[$part2] = ($newCounts[$part2] ?? 0) + $count;
            } else {
                $newStone = $stone * 2024;
                $newCounts[$newStone] = ($newCounts[$newStone] ?? 0) + $count;
            }
        }

        $this->option('debug') && $this->comment(var_export($newCounts, true));

        $this->stoneCounts = $newCounts;
    }
}
