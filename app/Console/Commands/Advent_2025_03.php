<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_03 extends Command
{
    protected $signature = 'advent:2025:3 {--stdin}';

    protected $description = '2025 - Day 3: Lobby';

    private string $data = <<<'TEXT'
987654321111111
811111111111119
234234234234278
818181911112111
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $totalJoltage = 0;

        foreach ($dataLines as $bank) {
            $batteries = str_split($bank);
            $possibilities = [];

            foreach ($batteries as $index => $battery) {
                $combinations = array_slice($batteries, $index + 1);

                if ($combinations) {
                    $possibilities[] = $battery . max($combinations);
                }
            }

            $totalJoltage += intval(max($possibilities));
        }

        $this->info('Total output joltage: ' . $totalJoltage);
    }
}
