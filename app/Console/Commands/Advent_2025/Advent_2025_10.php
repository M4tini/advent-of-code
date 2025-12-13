<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_10 extends Command
{
    protected $signature = 'advent:2025:10 {--stdin}';

    protected $description = '2025 - Day 10: Factory';

    private string $data = <<<'TEXT'
[.##.] (3) (1,3) (2) (2,3) (0,2) (0,1) {3,5,4,7}
[...#.] (0,2,3,4) (2,3) (0,4) (0,1,2) (1,2,3,4) {7,5,12,7,2}
[.###.#] (0,1,2,3,4) (0,3,4) (0,1,2,4,5) (1,2) {10,11,11,5,10,5}
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $buttonPresses = 0;
        $machines = [];

        foreach ($dataLines as $dataLine) {
            preg_match('/\[(.+)\](.+)\{(.+)\}/', $dataLine, $matches);

            $machines[] = [
                'lights'  => $matches[1], // str_split($matches[1])
                'buttons' => array_map(fn ($i) => explode(',', trim($i, '()')), explode(' ', trim($matches[2]))),
                'joltage' => explode(',', $matches[3]),
            ];
        }

        foreach ($machines as $machine) {
            $results = [];

            $this->pressButtons(
                $machine['lights'],
                str_repeat('.', strlen($machine['lights'])),
                $machine['buttons'],
                $results,
            );

            $buttonPresses += min($results);
        }

        $this->info('Fewest button presses required: ' . $buttonPresses);
    }

    private function pressButtons(string $target, string $lights, array $buttons, array &$results, int $depth = 0): void
    {
        if ($lights === $target) {
            $results[] = $depth;
        }
        if ($depth === 6) { // TODO: hardcoded depth (probably replace with joltage check for part 2)
            return;
        }

        foreach ($buttons as $button) {
            $result = str_split($lights);

            foreach ($button as $index) {
                $result[$index] = $result[$index] === '.' ? '#' : '.';
            }

            $this->pressButtons($target, implode('', $result), $buttons, $results, $depth + 1);
        }
    }
}
