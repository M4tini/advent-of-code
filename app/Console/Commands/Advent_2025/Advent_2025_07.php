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

        // The lines which only consist of `.` are not interesting for the timeline count.
        $dataLines = array_values(array_filter($dataLines, fn (string $dataLine) => trim($dataLine, '.') !== ''));
        $timeLineCount = $this->countTimelines($dataLines);

        $this->info('Beam splits: ' . $splitCount);
        $this->info('Amount of timelines: ' . $timeLineCount);
    }

    // Recursively passing $dataLines and using array_shift() causes memory limits, so the friend suggested to use DP.
    // This means we collect a list of timeline possibilities while going through each row of the grid (no recursion).
    private function countTimelines(array $dataLines): int
    {
        $cols = strlen($dataLines[0]);
        $startCol = strpos($dataLines[0], 'S');

        $timeLines = array_fill(0, $cols, 0);
        $timeLines[$startCol] = 1; // 0 0 0 0 0 0 0 1 0 0 0 0 0 0 0

        foreach ($dataLines as $dataLine) {
            $lineValues = str_split($dataLine);
            $next = array_fill(0, $cols, 0);

            foreach ($lineValues as $i => $lineValue) {
                if ($lineValue === '^') {
                    // Split the timeline left and right.
                    $next[$i - 1] += $timeLines[$i];
                    $next[$i + 1] += $timeLines[$i];
                } else {
                    // Keep the timeline while going down.
                    $next[$i] += $timeLines[$i];
                }
            }

            $timeLines = $next;
        }

        return array_sum($timeLines); // 1 0 2 0 10 0 11 0 11 0 2 1 1 0 1
    }
}
