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

    private array $symbolList = [];

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $sum = 0;

        // find the symbols
        foreach ($dataLines as $index => $line) {
            preg_match_all('/[^0-9.]/', $line, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                $this->symbolList[] = "$index-$match[1]";
            }
        }

        if ($this->option('debug')) {
            $this->comment(var_export($this->symbolList, true));
        }

        // find the numbers
        foreach ($dataLines as $index => $line) {
            preg_match_all('/(\d+)/', $line, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as $match) {
                $number = $match[0];
                $isValid = $this->isValidNumberLocation($index, $match[1], strlen($number));

                if ($this->option('debug')) {
                    $this->comment($index . ' - ' . $number);
                    $this->info($isValid ? 'valid' : 'invalid');
                }

                if ($isValid) {
                    $sum += (int) $number;
                }
            }
        }

        $this->info('Part number sum: ' . $sum);
    }

    private function isValidNumberLocation(int $row, int $position, int $length): bool
    {
        if ($this->option('debug')) {
            $this->comment($row . ' - ' . $position . ' - ' . $length);
        }

        $indexes = [$row - 1, $row, $row + 1];

        foreach ($indexes as $index) {
            for ($i = $position - 1; $i <= $position + $length; $i++) {
                if (in_array("$index-" . $i, $this->symbolList)) {
                    return true;
                }
            }
        }

        return false;
    }
}
