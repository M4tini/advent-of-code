<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2023_01 extends Command
{
    protected $signature = 'advent:2023:1 {--debug} {--input=}';

    protected $description = '2023 - Day 1: Trebuchet?!';

    private string $data = <<<TEXT
two1nine
eightwothree
abcone2threexyz
xtwone3four
4nineeightseven2
zoneight234
7pqrstsixteen
TEXT;

    private array $replacements = [
        ['one', 1],
        ['two', 2],
        ['three', 3],
        ['four', 4],
        ['five', 5],
        ['six', 6],
        ['seven', 7],
        ['eight', 8],
        ['nine', 9],
    ];

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $sum = 0;

        foreach ($dataLines as $data) {
            // Replace all numbers to their string representation (to properly support input like `5eightwo`).
            foreach ($this->replacements as $replacement) {
                $data = str_replace($replacement[1], $replacement[0], $data);
            }

            $replacements = collect($this->replacements)
                ->filter(fn (array $replacement) => str_contains($data, $replacement[0]));

            if ($replacements->count()) {
                // Replace the first occurrence of the first found letter string.
                $first = $replacements->sortBy(fn (array $replacement) => strpos($data, $replacement[0]))->first();
                $data = preg_replace("/{$first[0]}/", $first[1], $data, 1);

                // Replace all occurrences of the last found letter string.
                $last = $replacements->sortBy(fn (array $replacement) => strrpos($data, $replacement[0]))->last();
                $data = preg_replace("/{$last[0]}/", $last[1], $data);
            }

            $numeric = preg_replace('/\D/', '', $data);

            $num = intval(substr($numeric, 0, 1) . substr($numeric, -1, 1));

            if ($this->option('debug')) {
                $this->comment($num . ' -- ' . $data);
            }

            $sum += $num;
        }

        $this->info($sum); // 54418
    }
}
