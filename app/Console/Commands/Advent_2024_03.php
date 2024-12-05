<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class Advent_2024_03 extends Command implements PromptsForMissingInput
{
    protected $signature = 'advent:2024:3 {--input=}';

    protected $description = '2024 - Day 3: Mull It Over';

    private string $data = <<<'TEXT'
xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))
TEXT;

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $result = 0;

        foreach ($dataLines as $line) {
            preg_match_all('/mul\(\d+,\d+\)/', $line, $matches);

            $result += collect($matches[0])
                ->map(function ($item) {
                    preg_match('/mul\((\d+),(\d+)\)/', $item, $matches);

                    return $matches[1] * $matches[2];
                })
                ->sum();
        }

        $this->info('Result of multiplications: ' . $result);
    }
}
