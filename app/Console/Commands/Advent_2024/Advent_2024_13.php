<?php

declare(strict_types=1);

namespace App\Console\Commands\Advent_2024;

use Illuminate\Console\Command;

class Advent_2024_13 extends Command
{
    protected $signature = 'advent:2024:13 {--stdin}';

    protected $description = '2024 - Day 13: Claw Contraption';

    private string $data = <<<'TEXT'
Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;

        $tokens = collect(explode(PHP_EOL . PHP_EOL, $data))
            ->map(function (string $list) {
                preg_match_all('/X.(\d+), Y.(\d+)/', $list, $matches);

                return [
                    'buttons' => [
                        ['key' => 'A', 'dx' => (int) $matches[1][0], 'dy' => (int) $matches[2][0]],
                        ['key' => 'B', 'dx' => (int) $matches[1][1], 'dy' => (int) $matches[2][1]],
                    ],
                    'prize'   => ['x' => (int) $matches[1][2], 'y' => (int) $matches[2][2]],
                ];
            })
            ->map(function (array $clawMachine) {
                $path = ['buttons' => [], 'position' => ['x' => 0, 'y' => 0]];
                $results = [];

                $this->pressButtons($path, $clawMachine, $results);

                return $results;
            })
            ->map(function (array $results) {
                $costs = collect($results)->map(function (array $result) {
                    $buttons = array_count_values($result['buttons']);

                    return ($buttons['A'] ?? 0) * 3 + ($buttons['B'] ?? 0) * 3;
                })->toArray();

                return max($costs);
            })
            ->sum();

        $this->info('Fewest amount of tokens to win all possible prizes: ' . $tokens);
    }

    private function pressButtons(array $path, array $clawMachine, array &$results, int $depth = 0): void
    {
        if ($path['position'] === $clawMachine['prize']) {
            $results[] = $path;
        }
        if (
            $path['position'] === $clawMachine['prize']
            || $path['position']['x'] > $clawMachine['prize']['x']
            || $path['position']['y'] > $clawMachine['prize']['y']
            || $depth === 100
        ) {
            return;
        }

        foreach ($clawMachine['buttons'] as $button) {
            $path['buttons'][] = $button['key'];
            $path['position']['x'] += $button['dx'];
            $path['position']['y'] += $button['dy'];

            $this->pressButtons($path, $clawMachine, $results, $depth + 1);
        }
    }
}
