<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_07 extends Command
{
    protected $signature = 'advent:2025:7 {--stdin}';

    protected $description = '2025 - Day 7: Laboratories';

    private string $data = <<<'TEXT'
.......S.......
...............
.......^.......
...............
......^.^......
...............
.....^.^.^.....
...............
....^.^...^....
...............
...^.^...^.^...
...............
..^...^.....^..
...............
.^.^.^.^.^...^.
...............
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $splitCount = 0;
        $currentLine = array_shift($dataLines);

        foreach ($dataLines as $nextLine) {
            $beamLocations = str_split($currentLine);

            foreach ($beamLocations as $beamIndex => $beamLocation) {
                if ($beamLocation === 'S') {
                    if ($nextLine[$beamIndex] === '^') {
                        $nextLine[$beamIndex - 1] = 'S';
                        $nextLine[$beamIndex + 1] = 'S';
                        $splitCount++;
                    } else {
                        $nextLine[$beamIndex] = 'S';
                    }
                }
            }

            $currentLine = $nextLine;
        }

        $this->info('Beam splits: ' . $splitCount);
    }
}
