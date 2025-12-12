<?php

namespace App\Console\Commands\Advent_2023;

use Illuminate\Console\Command;

class Advent_2023_02 extends Command
{
    protected $signature = 'advent:2023:2 {--debug} {--input=}';

    protected $description = '2023 - Day 2: Cube Conundrum';

    private string $data = <<<TEXT
Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
Game 2: 1 blue, 2 green; 3 green, 4 blue, 1 red; 1 green, 1 blue
Game 3: 8 green, 6 blue, 20 red; 5 blue, 4 red, 13 green; 5 green, 1 red
Game 4: 1 green, 3 red, 6 blue; 3 green, 6 red; 3 green, 15 blue, 14 red
Game 5: 6 red, 1 blue, 3 green; 2 blue, 1 red, 2 green
TEXT;

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $possibleGameSum = 0;
        $powerSumAllSets = 0;

        foreach ($dataLines as $line) {
            if ($this->option('debug')) {
                $this->info($line);
            }

            $gameData = explode(':', $line);
            $gameNumber = (int) preg_replace('/\D/', '', $gameData[0]);
            $gameRounds = explode(';', $gameData[1]);
            $gameAnalysis = [
                'possible'  => true,
                'max_red'   => 0,
                'max_green' => 0,
                'max_blue'  => 0,
            ];

            foreach ($gameRounds as $gameRound) {
                $roundAnalysis = $this->analyzeRound($gameRound);

                if (!$roundAnalysis['possible']) {
                    $gameAnalysis['possible'] = false;
                }
                foreach (['max_red', 'max_green', 'max_blue'] as $color) {
                    if ($gameAnalysis[$color] < $roundAnalysis[$color]) {
                        $gameAnalysis[$color] = $roundAnalysis[$color];
                    }
                }
            }

            if ($gameAnalysis['possible']) {
                $possibleGameSum += $gameNumber;
            }
            $powerSumAllSets += ($gameAnalysis['max_red'] * $gameAnalysis['max_green'] * $gameAnalysis['max_blue']);

            if ($this->option('debug')) {
                $this->info($gameAnalysis['possible'] ? 'possible' : 'impossible');
            }
        }

        $this->info('Sum of possible games: ' . $possibleGameSum);
        $this->info('Power sum of all sets: ' . $powerSumAllSets);
    }

    private function analyzeRound(string $gameRound): array
    {
        $roundAnalysis = [
            'possible'  => true,
            'max_red'   => 0,
            'max_green' => 0,
            'max_blue'  => 0,
        ];

        $rolls = explode(';', $gameRound);

        foreach ($rolls as $roll) {
            $colors = explode(',', $roll);

            foreach ($colors as $color) {
                [$colorCount, $colorName] = explode(' ', trim($color));

                if ($this->option('debug')) {
                    $this->comment($roll . ' - ' . $colorCount . ' - ' . $colorName);
                }

                switch ($colorName) {
                    case 'red':
                        if ($colorCount > 12) {
                            $roundAnalysis['possible'] = false;
                        }
                        $roundAnalysis['max_red'] = $colorCount;
                        break;
                    case 'green':
                        if ($colorCount > 13) {
                            $roundAnalysis['possible'] = false;
                        }
                        $roundAnalysis['max_green'] = $colorCount;
                        break;
                    case 'blue':
                        if ($colorCount > 14) {
                            $roundAnalysis['possible'] = false;
                        }
                        $roundAnalysis['max_blue'] = $colorCount;
                        break;
                }
            }
        }

        return $roundAnalysis;
    }
}
