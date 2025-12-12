<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_05 extends Command
{
    protected $signature = 'advent:2025:5 {--debug} {--stdin}';

    protected $description = '2025 - Day 5: Cafeteria';

    private string $data = <<<'TEXT'
3-5
10-14
16-20
12-18

1
5
8
11
17
32
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        [$ingredientRangeData, $ingredientIdData] = explode(PHP_EOL . PHP_EOL, $data);
        $ingredientRanges = collect(explode(PHP_EOL, $ingredientRangeData))
            ->map(fn (string $ingredientRange) => array_map('intval', explode('-', $ingredientRange)))
            ->sortBy(fn (array $ingredientRange) => $ingredientRange[0]);
        $ingredientIds = explode(PHP_EOL, $ingredientIdData);
        $freshIngredientCount = 0;

        foreach ($ingredientIds as $ingredientId) {
            $freshRangeCount = $ingredientRanges
                ->filter(fn (array $range) => $range[0] <= $ingredientId && $range[1] >= $ingredientId)
                ->count();
            if ($freshRangeCount) {
                $freshIngredientCount++;
            }
        }

        // Using array_fill() causes memory limits, so we reduce the sorted ranges to a single value.
        $rangeFrom = 0;
        $freshIngredientRangeCount = $ingredientRanges->reduce(function (int $carry, array $range) use (&$rangeFrom) {
            $this->option('debug') && $this->info('Checking ' . implode('-', $range) . ' starting from ' . $rangeFrom);

            if ($rangeFrom === $range[0] && $rangeFrom === $range[1]) {
                $this->option('debug') && $this->warn('Special case where we need to do +1');
                return $carry + 1;
            }

            if ($range[1] <= $rangeFrom) {
                $this->option('debug') && $this->warn('Skipped');
                return $carry;
            }

            $add = $range[1] - max($range[0], $rangeFrom) + 1;
            $this->option('debug') && $this->warn('Added ' . $add);

            $rangeFrom = $range[1] + 1;

            return $carry + $add;
        }, 0);

        $this->info('Amount of fresh ingredients: ' . $freshIngredientCount);
        $this->info('Amount of ingredients considered fresh: ' . $freshIngredientRangeCount);
    }
}
