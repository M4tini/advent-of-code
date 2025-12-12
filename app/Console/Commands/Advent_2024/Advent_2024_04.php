<?php

namespace App\Console\Commands\Advent_2024;

use Illuminate\Console\Command;

class Advent_2024_04 extends Command
{
    protected $signature = 'advent:2024:4 {--debug} {--stdin}';

    protected $description = '2024 - Day 4: Ceres Search';

    private string $data = <<<'TEXT'
MMMSXXMASM
MSAMXMSMSA
AMXSXMAAMM
MSAMASMSMX
XMASAMXAMM
XXAMMXXAMA
SMSMSASXSS
SAXAMASAAA
MAMMMXMMMM
MXMXAXMASX
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $xmasCount = 0;

        // Count horizontal lines (each row).
        foreach ($dataLines as $line) {
            $xmasCount += $this->countXmas($line);
        }

        $dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();

        // Count vertical lines (each column).
        foreach ($dataMatrix as $index => $column) {
            $line = implode(array_column($dataMatrix, $index));
            $xmasCount += $this->countXmas($line);
        }

        // Count diagonal lines (orientation 1).
        foreach ($dataMatrix as $index => $column) {
            $line = $this->diagonalLineDownwards($dataMatrix, $index);
            $xmasCount += $this->countXmas($line);
        }

        $dataMatrix = $this->rotateMatrix($dataMatrix);

        // Count diagonal lines (orientation 2).
        foreach ($dataMatrix as $index => $column) {
            $line = $this->diagonalLineDownwards($dataMatrix, $index);
            $xmasCount += $this->countXmas($line);
        }

        $dataMatrix = $this->rotateMatrix($dataMatrix);

        // Count diagonal lines (orientation 3) skipping the overlapping (center) line from orientation 1.
        foreach ($dataMatrix as $index => $column) {
            if ($index === 0) {
                continue;
            }
            $line = $this->diagonalLineDownwards($dataMatrix, $index);
            $xmasCount += $this->countXmas($line);
        }

        $dataMatrix = $this->rotateMatrix($dataMatrix);

        // Count diagonal lines (orientation 4) skipping the overlapping (center) line from orientation 2.
        foreach ($dataMatrix as $index => $column) {
            if ($index === 0) {
                continue;
            }
            $line = $this->diagonalLineDownwards($dataMatrix, $index);
            $xmasCount += $this->countXmas($line);
        }

        $this->info('Amount of XMAS: ' . $xmasCount); // 2524
    }

    private function countXmas(string $line): int
    {
        if ($this->option('debug')) {
            $this->comment(substr_count($line, 'XMAS') . ' ' . substr_count($line, 'SAMX') . ' - ' . $line);
        }

        return substr_count($line, 'XMAS') + substr_count($line, 'SAMX');
    }

    private function diagonalLineDownwards(array $matrix, int $row): string
    {
        $column = 0;
        $result = $matrix[$row][$column];

        while (++$row < count($matrix)) {
            $result .= $matrix[$row][++$column] ?? '';
        }

        return $result;
    }

    private function rotateMatrix(array $matrix): array
    {
        $rotated = array_fill(0, count($matrix[0]), []);

        foreach ($matrix as $column) {
            foreach ($column as $index => $value) {
                $rotated[$index][] = $value;
            }
        }

        return array_reverse($rotated);
    }
}
