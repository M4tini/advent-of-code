<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2023_04 extends Command
{
    protected $signature = 'advent:2023:4 {--debug} {--input=}';

    protected $description = '2023 - Day 4: Scratchcards';

    /**
     * winning numbers | numbers I have
     */
    private string $data = <<<TEXT
Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
Card 2: 13 32 20 16 61 | 61 30 68 82 17 32 24 19
Card 3:  1 21 53 59 44 | 69 82 63 72 16 21 14  1
Card 4: 41 92 73 84 69 | 59 84 76 51 58  5 54 83
Card 5: 87 83 26 28 32 | 88 30 70 12 93 22 82 36
Card 6: 31 18 13 56 72 | 74 77 10 23 35 67 36 11
TEXT;

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $totalPoints = 0;
        $cardCount = array_fill(1, count($dataLines), 1);

        foreach ($dataLines as $line) {
            preg_match('/\D+(\d+):([\s\d]+)\|([\s\d]+)/', $line, $matches);
            $cardNumber = intval($matches[1]);
            $winningNumbers = array_filter(explode(' ', $matches[2]));
            $ownedNumbers = array_filter(explode(' ', $matches[3]));
            $points = 0;
            $cardCopies = 0;

            if ($this->option('debug')) {
                $this->info('Card: ' . $cardNumber);
                $this->comment('Winning: ' . implode(' ', $winningNumbers));
                $this->comment('Owned: ' . implode(' ', $ownedNumbers));
            }

            foreach ($ownedNumbers as $ownedNumber) {
                if (in_array($ownedNumber, $winningNumbers)) {
                    if ($points) {
                        $points *= 2;
                    } else {
                        $points = 1;
                    }
                    $cardCopies++;
                }
            }

            $this->comment('Copies won: ' . $cardCopies);

            for ($i = $cardNumber + 1; $i <= $cardNumber + $cardCopies; $i++) {
                $cardCount[$i] += $cardCount[$cardNumber];
            }

            $totalPoints += $points;
        }

        $this->info('Total points: ' . $totalPoints);
        $this->info('Total cards: ' . array_sum($cardCount));
    }
}
