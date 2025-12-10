<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_09 extends Command
{
    protected $signature = 'advent:2025:9 {--stdin}';

    protected $description = '2025 - Day 9: Movie Theater';

    private string $data = <<<'TEXT'
7,1
11,1
11,7
9,7
9,5
2,5
2,3
7,3
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $areas = [];

        $coordinates = collect($dataLines)->map(fn (string $dataLine) => explode(',', $dataLine))->all();

        foreach ($coordinates as $coordinate1) {
            foreach ($coordinates as $coordinate2) {
                if ($coordinate1 === $coordinate2) {
                    continue(2);
                }

                $areas[] = (max($coordinate1[0], $coordinate2[0]) - min($coordinate1[0], $coordinate2[0]) + 1) *
                    (max($coordinate1[1], $coordinate2[1]) - min($coordinate1[1], $coordinate2[1]) + 1);
            }
        }

        $this->info('Largest rectangle area: ' . max($areas));
    }
}
