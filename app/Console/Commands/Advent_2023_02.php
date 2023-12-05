<?php

namespace App\Console\Commands;

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
        $result = 0;

        foreach ($dataLines as $line) {
            if ($this->option('debug')) {
                $this->info($line);
            }

            $gameData = explode(':', $line);
            $gameNumber = (int) preg_replace('/\D/', '', $gameData[0]);
            $gameRounds = explode(';', $gameData[1]);
            $possible = true;

            foreach ($gameRounds as $gameRound) {
                if (!$this->isPossible($gameRound)) {
                    $possible = false;
                }
            }

            if ($possible) {
                $result += $gameNumber;
            }

            if ($this->option('debug')) {
                $this->info($possible ? 'possible' : 'impossible');
            }
        }

        $this->info($result); // 2085
    }

    private function isPossible(string $gameRound): bool
    {
        $rolls = explode(';', $gameRound);

        foreach ($rolls as $roll) {
            $colors = explode(',', $roll);

            foreach ($colors as $color) {
                [$colorCount, $colorName] = explode(' ', trim($color));

                if ($this->option('debug')) {
                    $this->comment($roll . ' - ' . $color . ' - ' . $colorCount . ' - ' . $colorName);
                }

                switch ($colorName) {
                    case 'red':
                        if ($colorCount > 12) {
                            return false;
                        }
                        break;
                    case 'green':
                        if ($colorCount > 13) {
                            return false;
                        }
                        break;
                    case 'blue':
                        if ($colorCount > 14) {
                            return false;
                        }
                        break;
                }
            }
        }

        return true;
    }
}
