<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_12 extends Command
{
    protected $signature = 'advent:2025:12 {--stdin}';

    protected $description = '2025 - Day 12: Christmas Tree Farm';

    private string $data = <<<'TEXT'
0:
###
##.
##.

1:
###
##.
.##

2:
.##
###
##.

3:
##.
###
##.

4:
###
#..
###

5:
###
.#.
###

4x4: 0 0 0 0 2 0
12x5: 1 0 1 0 2 2
12x5: 1 0 1 0 3 2
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataChunks = explode(PHP_EOL . PHP_EOL, $data);
        $regions = [];
        $shapes = [];

        $regionsChunk = array_pop($dataChunks);
        $dataLines = explode(PHP_EOL, $regionsChunk);
        foreach ($dataLines as $dataLine) {
            preg_match('/(\d+)x(\d+): (.+)/', $dataLine, $matches);
            $row = str_repeat('.', (int) $matches[1]);

            $regions[] = [
                'field'  => array_fill(0, (int) $matches[2], $row),
                'shapes' => array_map('intval', explode(' ', $matches[3])),
            ];
        }

        foreach ($dataChunks as $dataChunk) {
            $dataLines = explode(PHP_EOL, $dataChunk);
            $index = intval(array_shift($dataLines));

            $shapes[$index] = $dataLines;
        }

        dump($regions, $shapes);

        $this->info('This is going to be some recursive tetris puzzle... kthxbye');
    }
}
