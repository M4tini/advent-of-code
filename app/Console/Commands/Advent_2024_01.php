<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_01 extends Command
{
    protected $signature = 'advent:2024:1 {--debug} {--input=}';

    protected $description = '2024 - Day 1: Historian Hysteria';

    private string $data = <<<TEXT
3   4
4   3
2   5
1   3
3   9
3   3
TEXT;

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $leftValues = [];
        $rightValues = [];

        foreach ($dataLines as $line) {
            preg_match('/(\d+)\s+(\d+)/', $line, $matches);
            $leftValues[] = $matches[1];
            $rightValues[] = $matches[2];
        }

        sort($leftValues);
        sort($rightValues);
        $distance = 0;
        $similarityScore = 0;

        foreach ($leftValues as $index => $leftValue) {
            $rightValue = $rightValues[$index];
            $distance += max($leftValue, $rightValue) - min($leftValue, $rightValue);

            $similarRightValues = collect($rightValues)->filter(fn ($rightValue) => $rightValue === $leftValue)->count();
            $similarityScore += $leftValue * $similarRightValues;
        }

        $this->info('Total distance between lists: ' . $distance);
        $this->info('Similarity score: ' . $similarityScore);
    }
}
