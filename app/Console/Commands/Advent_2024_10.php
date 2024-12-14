<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_10 extends Command
{
    protected $signature = 'advent:2024:10 {--debug} {--stdin}';

    protected $description = '2024 - Day 10: Hoof It';

    private string $data = <<<'TEXT'
89010123
78121874
87430965
96549874
45678903
32019012
01329801
10456732
TEXT;

    private array $dataMatrix = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $this->dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();
        $sum = 0;

        foreach ($this->dataMatrix as $row => $line) {
            foreach ($line as $col => $position) {
                if ($position === '0') {
                    $nines = $this->trailheadScore($row, $col);
                    $sum += count(array_unique($nines));
                }
            }
        }

        $this->info('Sum of the scores of all trailheads: ' . $sum);
    }

    private function trailheadScore(int $row, int $col): array
    {
        $score = [];

        $height = (int) $this->dataMatrix[$row][$col];

        if ($height === 9) {
            return [$row . '_' . $col];
        }

        if (isset($this->dataMatrix[$row - 1][$col]) && intval($this->dataMatrix[$row - 1][$col]) === $height + 1) {
            $this->option('debug') && $this->comment('up ' . $this->dataMatrix[$row - 1][$col]);

            $score = array_merge($score, $this->trailheadScore($row - 1, $col));
        }
        if (isset($this->dataMatrix[$row + 1][$col]) && intval($this->dataMatrix[$row + 1][$col]) === $height + 1) {
            $this->option('debug') && $this->comment('down ' . $this->dataMatrix[$row + 1][$col]);

            $score = array_merge($score, $this->trailheadScore($row + 1, $col));
        }
        if (isset($this->dataMatrix[$row][$col - 1]) && intval($this->dataMatrix[$row][$col - 1]) === $height + 1) {
            $this->option('debug') && $this->comment('left ' . $this->dataMatrix[$row][$col - 1]);

            $score = array_merge($score, $this->trailheadScore($row, $col - 1));
        }
        if (isset($this->dataMatrix[$row][$col + 1]) && intval($this->dataMatrix[$row][$col + 1]) === $height + 1) {
            $this->option('debug') && $this->comment('right ' . $this->dataMatrix[$row][$col + 1]);

            $score = array_merge($score, $this->trailheadScore($row, $col + 1));
        }

        return $score;
    }
}
