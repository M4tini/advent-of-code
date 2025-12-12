<?php

declare(strict_types=1);

namespace App\Console\Commands\Advent_2024;

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
        $results = [];

        foreach ($this->dataMatrix as $row => $line) {
            foreach ($line as $col => $position) {
                if ($position === '0') {
                    $results[] = $this->trailheadScore($row, $col);
                }
            }
        }

        $sumOfScores = collect($results)->map(fn (array $result) => count(array_unique($result)))->sum();
        $sumOfRatings = collect($results)->map(fn (array $result) => count($result))->sum();

        $this->info('Sum of the scores of all trailheads: ' . $sumOfScores);
        $this->info('Sum of the ratings of all trailheads: ' . $sumOfRatings);
    }

    private function trailheadScore(int $row, int $col): array
    {
        $result = [];

        $height = (int) $this->dataMatrix[$row][$col];

        if ($height === 9) {
            return [$row . '_' . $col];
        }

        if (isset($this->dataMatrix[$row - 1][$col]) && intval($this->dataMatrix[$row - 1][$col]) === $height + 1) {
            $this->option('debug') && $this->comment('up ' . $this->dataMatrix[$row - 1][$col]);
            $result = array_merge($result, $this->trailheadScore($row - 1, $col));
        }
        if (isset($this->dataMatrix[$row + 1][$col]) && intval($this->dataMatrix[$row + 1][$col]) === $height + 1) {
            $this->option('debug') && $this->comment('down ' . $this->dataMatrix[$row + 1][$col]);
            $result = array_merge($result, $this->trailheadScore($row + 1, $col));
        }
        if (isset($this->dataMatrix[$row][$col - 1]) && intval($this->dataMatrix[$row][$col - 1]) === $height + 1) {
            $this->option('debug') && $this->comment('left ' . $this->dataMatrix[$row][$col - 1]);
            $result = array_merge($result, $this->trailheadScore($row, $col - 1));
        }
        if (isset($this->dataMatrix[$row][$col + 1]) && intval($this->dataMatrix[$row][$col + 1]) === $height + 1) {
            $this->option('debug') && $this->comment('right ' . $this->dataMatrix[$row][$col + 1]);
            $result = array_merge($result, $this->trailheadScore($row, $col + 1));
        }

        return $result;
    }
}
