<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_03 extends Command
{
    protected $signature = 'advent:2024:3 {--stdin}';

    protected $description = '2024 - Day 3: Mull It Over';

    private string $data = <<<'TEXT'
xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataWithoutBreaks = str_replace(PHP_EOL, '', $data);
        $dataLines = explode('do()', $dataWithoutBreaks);
        $result = 0;

        foreach ($dataLines as $line) {
            $mulsToProcess = explode("don't()", $line)[0];

            preg_match_all('/mul\(\d+,\d+\)/', $mulsToProcess, $matches);

            $result += collect($matches[0])
                ->map(function ($item) {
                    preg_match('/mul\((\d+),(\d+)\)/', $item, $matches);

                    return $matches[1] * $matches[2];
                })
                ->sum();
        }

        $this->info('Result of enabled multiplications: ' . $result);
    }
}
