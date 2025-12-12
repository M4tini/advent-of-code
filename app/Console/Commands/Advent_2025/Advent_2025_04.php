<?php

namespace App\Console\Commands\Advent_2025;

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

    private array $dataMatrix = [];
    private array $removedRolls = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $this->dataMatrix = collect($dataLines)->map(fn (string $item) => str_split($item))->all();

        $this->removeAccessibleRolls();

        $this->info('Forklift accessible rolls of paper: ' . $this->removedRolls[0]);
        $this->info('Forklift removed rolls of paper: ' . array_sum($this->removedRolls));
    }

    private function removeAccessibleRolls(): void
    {
        $removed = 0;
        $newDataMatrix = $this->dataMatrix;

        foreach ($this->dataMatrix as $rowIndex => $row) {
            foreach ($row as $colIndex => $col) {
                if ($col === '@') {
                    $adjacentRolls = [
                        $this->dataMatrix[$rowIndex - 1][$colIndex - 1] ?? '.',
                        $this->dataMatrix[$rowIndex - 1][$colIndex] ?? '.',
                        $this->dataMatrix[$rowIndex - 1][$colIndex + 1] ?? '.',
                        $this->dataMatrix[$rowIndex][$colIndex + 1] ?? '.',
                        $this->dataMatrix[$rowIndex + 1][$colIndex + 1] ?? '.',
                        $this->dataMatrix[$rowIndex + 1][$colIndex] ?? '.',
                        $this->dataMatrix[$rowIndex + 1][$colIndex - 1] ?? '.',
                        $this->dataMatrix[$rowIndex][$colIndex - 1] ?? '.',
                    ];

                    $adjacentRollValues = array_count_values($adjacentRolls);

                    if (isset($adjacentRollValues['@']) && $adjacentRollValues['@'] >= 4) {
                        continue;
                    }

                    $newDataMatrix[$rowIndex][$colIndex] = '.';
                    $removed++;
                }
            }
        }

        $this->dataMatrix = $newDataMatrix;
        $this->removedRolls[] = $removed;

        if ($removed !== 0) {
            $this->removeAccessibleRolls();
        }
    }
}
