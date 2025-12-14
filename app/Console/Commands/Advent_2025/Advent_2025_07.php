<?php

namespace App\Console\Commands\Advent_2025;

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

    private int $timelines = 0;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $splitCount = 0;
        $currentLine = '';

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

        $currentLine = array_shift($dataLines);
        $currentIndex = strpos($currentLine, 'S');

        // The lines which only consist of `.` are not interesting for the timeline count.
        $dataLines = array_filter($dataLines, fn (string $dataLine) => str_contains($dataLine, '^'));

        $this->findTimelines($currentIndex, $dataLines);

        $this->info('Beam splits: ' . $splitCount);
        $this->info('Amount of timelines: ' . $this->timelines);
    }

    private function findTimelines(int $index, array $dataLines): void
    {
        $nextLine = array_shift($dataLines);

        if ($nextLine === null) {
            $this->timelines++;
            return;
        }

        if ($nextLine[$index] === '^') {
            $this->findTimelines($index - 1, $dataLines);
            $this->findTimelines($index + 1, $dataLines);
        } else {
            $this->findTimelines($index, $dataLines);
        }
    }
}
