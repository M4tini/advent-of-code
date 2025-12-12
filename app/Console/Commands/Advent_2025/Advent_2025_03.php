<?php

namespace App\Console\Commands\Advent_2025;

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
        $totalJoltage2 = 0;
        $totalJoltage12 = 0;

        foreach ($dataLines as $bank) {
            $batteries = str_split($bank);

            $totalJoltage2 += (int) $this->bestBatteries($batteries, 2);
            $totalJoltage12 += (int) $this->bestBatteries($batteries, 12);
        }

        $this->info('Total output joltage using 2 batteries: ' . $totalJoltage2);
        $this->info('Total output joltage using 12 batteries: ' . $totalJoltage12);
    }

    private function bestBatteries(array $batteries, int $amountToActivate): string
    {
        if ($amountToActivate === 1) {
            return max($batteries);
        }

        // Find the best battery among the ones that are not needed for the remaining battery $length.
        $bestBattery = max(array_slice($batteries, 0, count($batteries) - $amountToActivate + 1));
        $bestBatteryIndex = array_search($bestBattery, $batteries);

        // Continue finding the next best battery, starting the search from the $bestBatteryIndex.
        $nextBatteries = $this->bestBatteries(array_slice($batteries, $bestBatteryIndex + 1), $amountToActivate - 1);

        return $bestBattery . $nextBatteries;
    }
}
