<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_05 extends Command
{
    protected $signature = 'advent:2025:5 {--stdin}';

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

    private array $processedRanges = [];

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
        $freshIngredientRangeCount = $ingredientRanges->reduce(function (int $carry, array $range) {
            foreach ($this->processedRanges as $processedIndex => $processedRange) {
                // Early return if the new range falls completely within a processed range.
                if ($processedRange[0] <= $range[0] && $processedRange[1] >= $range[1]) {
                    return $carry;
                }

                // Remove the processed range if it falls completely within the new range.
                if ($processedRange[0] >= $range[0] && $processedRange[1] <= $range[1]) {
                    unset($processedRange[$processedIndex]);
                    continue;
                }

                // Adjust the beginning of the new range to match a processed range if needed.
                if ($processedRange[0] <= $range[0] && $processedRange[1] >= $range[0]) {
                    $range[0] = $processedRange[1] + 1;
                }

                // Adjust the end of the range to match a processed range if needed.
                if ($processedRange[0] >= $range[0] && $processedRange[1] >= $range[0]) {
                    $range[1] = $processedRange[0] - 1;
                }
            }

            $this->processedRanges[] = $range;

            $this->info('Adding ' . $range[1] . ' - ' . $range[0] . ' = ' . ($range[1] - $range[0] + 1));

            return $carry + $range[1] - $range[0] + 1;
        }, 0);

        $this->info('Amount of fresh ingredients: ' . $freshIngredientCount);
        $this->info('Amount of ingredients considered fresh: ' . $freshIngredientRangeCount); // 354179868661316 is too high
    }
}
