<?php

namespace App\Console\Commands\Advent_2024;

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
    private bool $rotating = false;
    private int $rotations = 0;
    private int $loopOptions = 0;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $this->dataMatrix = collect($dataLines)->map(fn ($line) => str_split($line))->toArray();

        $this->moveUp();

        $unwalked = collect($this->dataMatrix)->sum(fn ($line) => array_count_values($line)['.'] ?? 0);
        $obstacles = collect($this->dataMatrix)->sum(fn ($line) => array_count_values($line)['#'] ?? 0);
        $result = (count($this->dataMatrix) * count($this->dataMatrix[0])) - $unwalked - $obstacles;

        $this->info('Guard positions visited: ' . $result);
        $this->info('Loop obstruction positions: ' . $this->loopOptions);
    }

    private function debug(string $message): void
    {
        if ($this->option('debug')) {
            $this->comment(implode(' - ', [$this->rotations, $message]));
        }
    }

    private function moveUp(): void
    {
        foreach ($this->dataMatrix as $index => $line) {
            $guardPosition = array_search('^', $line);

            if ($guardPosition !== false) {
                if ($index === 0) {
                    $this->debug('Guard has reached the border');
                    $this->dataMatrix[$index][$guardPosition] = $this->rotations;
                    break;
                }

                if ($this->dataMatrix[$index - 1][$guardPosition] === '#') {
                    $this->debug('Guard rotates to the right');
                    $this->rotateMatrix();
                } else {
                    $this->debug('Guard walks');
                    $this->dataMatrix[$index][$guardPosition] = $this->rotations;

                    if (!$this->rotating && $this->detectLoop($index, $guardPosition)) {
                        $this->debug('Loop option detected');
                        $this->loopOptions++;
                    }

                    $this->dataMatrix[$index - 1][$guardPosition] = '^';
                    $this->rotating = false;
                }

                $this->moveUp();
                break;
            }
        }
    }

    private function detectLoop(int $index, int $guardPosition): bool
    {
        foreach ($this->dataMatrix[$index] as $position => $value) {
            if ($position <= $guardPosition || $value === '.') {
                continue;
            }
            if (is_int($value)) {
                $nextValue = $this->dataMatrix[$index][$position + 1] ?? null;

                return $nextValue === $value || $nextValue === '#';
            }
        }

        return false;
    }

    private function rotateMatrix(): void
    {
        $this->rotating = true;
        $this->rotations++;
        $rotated = array_fill(0, count($this->dataMatrix[0]), []);

        foreach ($this->dataMatrix as $column) {
            foreach ($column as $index => $value) {
                $rotated[$index][] = $value;
            }
        }

        $this->dataMatrix = array_reverse($rotated);
    }
}
