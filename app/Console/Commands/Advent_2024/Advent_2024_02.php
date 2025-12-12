<?php

namespace App\Console\Commands\Advent_2024;

use Exception;
use Illuminate\Console\Command;

class Advent_2024_02 extends Command
{
    protected $signature = 'advent:2024:2 {--stdin}';

    protected $description = '2024 - Day 2: Red-Nosed Reports';

    private string $data = <<<'TEXT'
7 6 4 2 1
1 2 7 8 9
9 7 6 2 1
1 3 2 4 5
8 6 4 4 1
1 3 6 7 9
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $safeReportCount = 0;
        $safeReportTolerationCount = 0;

        foreach ($dataLines as $line) {
            $report = explode(' ', $line);

            if ($this->isSafe($report)) {
                $safeReportCount++;
            }
            if ($this->isSafe($report) || $this->isSafeWithToleration($report)) {
                $safeReportTolerationCount++;
            }
        }

        $this->info('Number of safe reports: ' . $safeReportCount);
        $this->info('Number of safe reports with Problem Dampener: ' . $safeReportTolerationCount);
    }

    private function isSafeWithToleration(array $report, int $indexToIgnore = 0): bool
    {
        if (!isset($report[$indexToIgnore])) {
            return false;
        }

        $reportClone = $report;
        unset($reportClone[$indexToIgnore]);

        if ($this->isSafe(array_values($reportClone))) {
            return true;
        }

        return $this->isSafeWithToleration($report, ++$indexToIgnore);
    }

    private function isSafe(array $report): bool
    {
        try {
            if ($report[0] > $report[1]) {
                return $this->isAllDecreasing($report) && $this->differenceAllowed($report);
            }
            if ($report[0] < $report[1]) {
                return $this->isAllIncreasing($report) && $this->differenceAllowed($report);
            }
        } catch (Exception) {
        }

        return false;
    }

    private function isAllDecreasing(array $report): bool
    {
        return array_reduce($report, function ($carry, $item) {
            if ($carry !== null && $carry <= $item) {
                throw new Exception('Nope');
            }

            return $item;
        });
    }

    private function isAllIncreasing(array $report): bool
    {
        return array_reduce($report, function ($carry, $item) {
            if ($carry !== null && $carry >= $item) {
                throw new Exception('Nope');
            }

            return $item;
        });
    }

    private function differenceAllowed(array $report): bool
    {
        return array_reduce($report, function ($carry, $item) {
            if ($carry !== null && max($carry, $item) - min($carry, $item) > 3) {
                throw new Exception('Nope');
            }

            return $item;
        });
    }
}
