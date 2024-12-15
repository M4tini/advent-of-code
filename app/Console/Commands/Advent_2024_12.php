<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_12 extends Command
{
    protected $signature = 'advent:2024:12 {--stdin}';

    protected $description = '2024 - Day 12: Garden Groups';

    private string $data = <<<'TEXT'
RRRRIICCFF
RRRRIICCCF
VVRRRCCFFF
VVRCCCJFFF
VVVVCJJCFE
VVIVCCJJEE
VVIIICJJEE
MIIIIIJJEE
MIIISIJEEE
MMMISSJEEE
TEXT;

    private array $dataMatrix = [];
    private array $dataBackup = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $this->dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();
        $this->dataBackup = $this->dataMatrix; // Create a backup so we can cross off processed regions from the matrix.
        $regions = [];

        foreach ($this->dataBackup as $row => $line) {
            foreach ($line as $col => $identifier) {
                if ($this->dataMatrix[$row][$col] === '.') {
                    continue;
                }

                $region = $this->findRegion($row, $col);

                $regions[] = [
                    'area'      => count($region),
                    'perimeter' => $this->calculatePerimeter($region, $identifier),
                ];
            }
        }

        $price = collect($regions)->map(fn (array $region) => $region['area'] * $region['perimeter'])->sum();

        $this->info('Total price of fencing all regions on the map: ' . $price);
    }

    private function findRegion(int $row, int $col): array
    {
        $regionIdentifier = $this->dataMatrix[$row][$col];
        $regionPlots = [
            $this->id($row, $col) => [$row, $col],
        ];

        return $this->expandRegion($regionPlots, $regionIdentifier);
    }

    /**
     * Loop over a given amount of plots and try to expand them in any direction. If successful, try another expansion.
     */
    private function expandRegion(array $regionPlots, string $identifier): array
    {
        $area = count($regionPlots);

        foreach ($regionPlots as $plot) {
            $row = $plot[0];
            $col = $plot[1];

            if (isset($this->dataMatrix[$row - 1][$col]) && $this->dataMatrix[$row - 1][$col] === $identifier) {
                $regionPlots[$this->id($row - 1, $col)] = [$row - 1, $col];
                $this->markProcessed($row - 1, $col);
            }
            if (isset($this->dataMatrix[$row + 1][$col]) && $this->dataMatrix[$row + 1][$col] === $identifier) {
                $regionPlots[$this->id($row + 1, $col)] = [$row + 1, $col];
                $this->markProcessed($row + 1, $col);
            }
            if (isset($this->dataMatrix[$row][$col - 1]) && $this->dataMatrix[$row][$col - 1] === $identifier) {
                $regionPlots[$this->id($row, $col - 1)] = [$row, $col - 1];
                $this->markProcessed($row, $col - 1);
            }
            if (isset($this->dataMatrix[$row][$col + 1]) && $this->dataMatrix[$row][$col + 1] === $identifier) {
                $regionPlots[$this->id($row, $col + 1)] = [$row, $col + 1];
                $this->markProcessed($row, $col + 1);
            }
        }

        return count($regionPlots) === $area ? $regionPlots : $this->expandRegion($regionPlots, $identifier);
    }

    private function id($row, $col): string
    {
        return $row . '_' . $col;
    }

    /**
     * Mark processed plots, so we know which plots are left to process.
     */
    private function markProcessed(int $row, int $col): void
    {
        $this->dataMatrix[$row][$col] = '.';
    }

    /**
     * The perimeter is the sum of all bordering plots which do not have the same identifier (or are out of bounds).
     */
    private function calculatePerimeter(array $regionPlots, string $identifier): int
    {
        $perimeter = 0;

        foreach ($regionPlots as $plot) {
            $row = $plot[0];
            $col = $plot[1];

            if (!isset($this->dataBackup[$row - 1][$col]) || $this->dataBackup[$row - 1][$col] !== $identifier) {
                $perimeter++;
            }
            if (!isset($this->dataBackup[$row + 1][$col]) || $this->dataBackup[$row + 1][$col] !== $identifier) {
                $perimeter++;
            }
            if (!isset($this->dataBackup[$row][$col - 1]) || $this->dataBackup[$row][$col - 1] !== $identifier) {
                $perimeter++;
            }
            if (!isset($this->dataBackup[$row][$col + 1]) || $this->dataBackup[$row][$col + 1] !== $identifier) {
                $perimeter++;
            }
        }

        return $perimeter;
    }
}
