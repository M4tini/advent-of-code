<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_06 extends Command
{
    protected $signature = 'advent:2024:6 {--debug} {--stdin}';

    protected $description = '2024 - Day 6: Guard Gallivant';

    private string $data = <<<'TEXT'
....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...
TEXT;

    private array $dataMatrix;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $this->dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();

        $this->moveUp();

        $result = collect($this->dataMatrix)
            ->sum(fn ($line) => array_count_values($line)['X'] ?? 0);

        $this->info('Guard positions visited: ' . $result);
    }

    private function moveUp(): void
    {
        foreach ($this->dataMatrix as $index => $line) {
            $guardPosition = array_search('^', $line);

            if ($guardPosition !== false) {
                if ($index === 0) {
                    if ($this->option('debug')) {
                        $this->comment('Guard has reached the border');
                    }
                    $this->dataMatrix[$index][$guardPosition] = 'X';
                    break;
                }

                if ($this->dataMatrix[$index - 1][$guardPosition] === '#') {
                    if ($this->option('debug')) {
                        $this->comment('Guard rotates to the right');
                    }
                    $this->rotateMatrix();
                } else {
                    if ($this->option('debug')) {
                        $this->comment('Guard walks');
                    }
                    $this->dataMatrix[$index][$guardPosition] = 'X';
                    $this->dataMatrix[$index - 1][$guardPosition] = '^';
                }

                $this->moveUp();
                break;
            }
        }
    }

    private function rotateMatrix(): void
    {
        $rotated = array_fill(0, count($this->dataMatrix[0]), []);

        foreach ($this->dataMatrix as $column) {
            foreach ($column as $index => $value) {
                $rotated[$index][] = $value;
            }
        }

        $this->dataMatrix = array_reverse($rotated);
    }
}
