<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_06 extends Command
{
    protected $signature = 'advent:2025:6 {--debug} {--stdin}';

    protected $description = '2025 - Day 6: Trash Compactor';

    private string $data = <<<'TEXT'
123 328  51 64
 45 64  387 23
  6 98  215 314
*   +   *   +
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $homework = [];
        $total = 0;
        preg_match_all('/\S/', array_pop($dataLines), $operations);

        foreach ($dataLines as $line) {
            preg_match_all('/\d+/', $line, $matches);

            foreach ($matches[0] as $index => $match) {
                $homework[$index][] = $match;
            }
        }

        foreach ($homework as $index => $homeworkItem) {
            $total += $this->reduceWithOperator($homeworkItem, $operations[0][$index]);
        }

        $this->info('Grand total: ' . $total);
    }

    private function reduceWithOperator(array $items, string $operator): int
    {
        $initial = match ($operator) {
            '*' => 1,
            '+' => 0,
        };

        return array_reduce($items, fn (mixed $carry, string $item) => match ($operator) {
            '*' => $carry * intval($item),
            '+' => $carry + intval($item),
        }, $initial);
    }
}
