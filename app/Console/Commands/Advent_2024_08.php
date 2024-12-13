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

    private array $antinodes1 = [];
    private array $antinodes2 = [];
    private int $maxX = 0;
    private int $maxY = 0;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();
        $this->maxX = count($dataMatrix[0]) - 1;
        $this->maxY = count($dataMatrix) - 1;

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
                        $this->findAntinode($antennaX, $antennaY, $x, $y);

                        // Take the second antenna and extend the X / Y distance from the first antenna.
                        $this->findAntinode($x, $y, $antennaX, $antennaY);
                    }
                }
            }
        }

        $this->info('Unique antinode locations: ' . count(array_unique($this->antinodes1)));
        $this->info('Unique antinode locations using resonant harmonics: ' . count(array_unique($this->antinodes2)));
    }

    private function findAntinode(string $x1, string $y1, string $x2, string $y2, int $depth = 0): void
    {
        // Antennas are always antinodes when using resonant harmonics.
        $this->antinodes2[] = $x1 . '_' . $y1;

        $deltaX = $x2 - $x1;
        $deltaY = $y2 - $y1;

        $newX = $x1 - $deltaX;
        $newY = $y1 - $deltaY;

        if ($newX >= 0 && $newX <= $this->maxX && $newY >= 0 && $newY <= $this->maxY) {
            if ($depth === 0) {
                $this->antinodes1[] = $newX . '_' . $newY;
            }
            $this->antinodes2[] = $newX . '_' . $newY;

            $this->findAntinode($newX, $newY, $x1, $y1, ++$depth);
        }
    }
}
