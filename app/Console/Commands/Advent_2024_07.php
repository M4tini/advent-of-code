<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_07 extends Command
{
    protected $signature = 'advent:2024:7 {--stdin}';

    protected $description = '2024 - Day 7: Bridge Repair';

    private string $data = <<<'TEXT'
190: 10 19
3267: 81 40 27
83: 17 5
156: 15 6
7290: 6 8 6 15
161011: 16 10 13
192: 17 8 14
21037: 9 7 18 13
292: 11 6 16 20
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $calibrations = [];

        foreach ($dataLines as $line) {
            $equation = explode(': ', $line);
            $result = (int) $equation[0];
            $numbers = explode(' ', $equation[1]);

            $combinations = $this->combine((int) array_pop($numbers), $numbers);

            if (in_array($result, $combinations)) {
                $calibrations[] = $result;
            }
        }

        $this->info('Total calibration result: ' . array_sum($calibrations));
    }

    private function combine(int $number, array $numbers): array
    {
        $result = [];
        $combinations = count($numbers) === 1
            ? $numbers
            : $this->combine((int) array_pop($numbers), $numbers);

        foreach ($combinations as $combination) {
            $result[] = $number + (int) $combination;
            $result[] = $number * (int) $combination;
        }

        return $result;
    }
}
