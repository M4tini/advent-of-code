<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_04 extends Command
{
    protected $signature = 'advent:2025:4 {--stdin}';

    protected $description = '2025 - Day 4: Printing Department';

    private string $data = <<<'TEXT'
..@@.@@@@.
@@@.@.@.@@
@@@@@.@.@@
@.@@@@..@.
@@.@@@@.@@
.@@@@@@@.@
.@.@.@.@@@
@.@@@.@@@@
.@@@@@@@@.
@.@.@@@.@.
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $dataMatrix = collect($dataLines)->map(fn (string $item) => str_split($item))->all();
        $accessible = 0;

        foreach ($dataMatrix as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                if ($col === '@') {
                    $adjacentRolls = [
                        $dataMatrix[$rowIndex - 1][$colIndex - 1] ?? '.',
                        $dataMatrix[$rowIndex - 1][$colIndex] ?? '.',
                        $dataMatrix[$rowIndex - 1][$colIndex + 1] ?? '.',
                        $dataMatrix[$rowIndex][$colIndex + 1] ?? '.',
                        $dataMatrix[$rowIndex + 1][$colIndex + 1] ?? '.',
                        $dataMatrix[$rowIndex + 1][$colIndex] ?? '.',
                        $dataMatrix[$rowIndex + 1][$colIndex - 1] ?? '.',
                        $dataMatrix[$rowIndex][$colIndex - 1] ?? '.',
                    ];
                    $adjacentRollValues = array_count_values($adjacentRolls);

                    if (isset($adjacentRollValues['@']) && $adjacentRollValues['@'] >= 4) {
                        continue;
                    }

                    $accessible++;
                }
            }
        }

        $this->info('Forklift accessible rolls of paper: ' . $accessible); // 11845 is too high
    }
}
