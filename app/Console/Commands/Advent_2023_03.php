<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2023_03 extends Command
{
    protected $signature = 'advent:2023:3 {--debug} {--input=}';

    protected $description = '2023 - Day 3: Gear Ratios';

    private string $data = <<<TEXT
467..114..
...*......
..35..633.
......#...
617*......
.....+.58.
..592.....
......755.
...$.*....
.664.598..
TEXT;

    private array $dataLines = [];

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $this->dataLines = explode(PHP_EOL, $data);
        $partNumberSum = 0;

        foreach ($this->dataLines as $index => $line) {
            preg_match_all('/(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                $number = $match[0];
                $isValid = $this->isSurroundedBy($index, $match[1], strlen($number));

                if ($this->option('debug')) {
                    $this->comment($index . ' - ' . $number);
                    $this->info($isValid ? 'valid' : 'invalid');
                }

                if ($isValid) {
                    $partNumberSum += (int) $number;
                }
            }
        }

        $this->info('Part number sum: ' . $partNumberSum);
    }

    private function isSurroundedBy(int $row, int $position, int $length): bool
    {
        $indexes = [$row - 1, $row, $row + 1];

        foreach ($indexes as $index) {
            for ($i = $position - 1; $i <= $position + $length; $i++) {
                $character = $this->dataLines[$index][$i] ?? null;

                if (preg_match('/[^0-9.]/', $character)) {
                    return true;
                }
            }
        }

        return false;
    }
}
