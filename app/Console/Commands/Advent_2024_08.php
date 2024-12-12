<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_08 extends Command
{
    protected $signature = 'advent:2024:8 {--stdin}';

    protected $description = '2024 - Day 8: Resonant Collinearity';

    private string $data = <<<'TEXT'
............
........0...
.....0......
.......0....
....0.......
......A.....
............
............
........A...
.........A..
............
............
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();
        $maxX = count($dataMatrix[0]) - 1;
        $maxY = count($dataMatrix) - 1;
        $antinodes = [];

        foreach ($dataMatrix as $antennaX => $antennaLine) {
            foreach ($antennaLine as $antennaY => $antennaFrequency) {
                if ($antennaFrequency === '.') {
                    continue;
                }

                // Once we have found an antenna, we loop the entire matrix again to find matching antennas.
                foreach ($dataMatrix as $x => $line) {
                    foreach ($line as $y => $frequency) {
                        if (
                            $frequency === '.'
                            || $frequency !== $antennaFrequency
                            || ($x === $antennaX && $y === $antennaY)
                        ) {
                            continue;
                        }

                        // Take the first antenna and extend the X / Y distance from the second antenna.
                        $newX = $this->extendCoordinate($antennaX, $x);
                        $newY = $this->extendCoordinate($antennaY, $y);
                        if ($newX >= 0 && $newX <= $maxX && $newY >= 0 && $newY <= $maxY) {
                            $antinodes[] = $newX . '_' . $newY;
                        }

                        // Take the second antenna and extend the X / Y distance from the first antenna.
                        $newX = $this->extendCoordinate($x, $antennaX);
                        $newY = $this->extendCoordinate($y, $antennaY);
                        if ($newX >= 0 && $newX <= $maxX && $newY >= 0 && $newY <= $maxY) {
                            $antinodes[] = $newX . '_' . $newY;
                        }
                    }
                }
            }
        }

        $this->info('Unique locations containing an antinode: ' . count(array_unique($antinodes)));
    }

    private function extendCoordinate($a, $b): int
    {
        return match (true) {
            $a === $b => $a,
            $a < $b   => $a - ($b - $a),
            $a > $b   => $a + ($a - $b),
        };
    }
}
