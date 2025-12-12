<?php

namespace App\Console\Commands\Advent_2015;

use Illuminate\Console\Command;

class Advent_2015_02 extends Command
{
    protected $signature = 'advent:2015:2 {--stdin}';

    protected $description = '2015 - Day 2: I Was Told There Would Be No Math';

    private string $data = <<<'TEXT'
2x3x4
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $totalSquareFeet = 0;
        $totalRibbonFeet = 0;

        foreach ($dataLines as $line) {
            $numbers = explode('x', $line);
            $length = $numbers[0];
            $width = $numbers[1];
            $height = $numbers[2];
            sort($numbers);

            $sides = [
                $length * $width,
                $width * $height,
                $height * $length,
            ];
            sort($sides);

            $totalSquareFeet += (2 * array_sum($sides)) + $sides[0];
            $totalRibbonFeet += 2 * $numbers[0] + 2 * $numbers[1] + $numbers[0] * $numbers[1] * $numbers[2];
        }

        $this->info('Total square feet of wrapping paper: ' . $totalSquareFeet);
        $this->info('Total feet of ribbon: ' . $totalRibbonFeet);
    }
}
