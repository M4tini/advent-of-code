<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_06 extends Command
{
    protected $signature = 'advent:2025:6 {--stdin}';

    protected $description = '2025 - Day 6: Trash Compactor';

    public function handle(): void
    {
        $data = file_get_contents('php://stdin'); // Only using stdin because trailing spaces matter.
        $dataLines = array_filter(explode(PHP_EOL, $data));
        $homework = [];
        $total = 0;
        $totalRightToLeft = 0;
        preg_match_all('/\S/', array_pop($dataLines), $operations);

        foreach ($dataLines as $line) {
            preg_match_all('/\d+/', $line, $matches);

            foreach ($matches[0] as $index => $match) {
                $homework[$index][] = $match;
            }
        }

        foreach ($homework as $index => $homeworkItem) {
            $total += $this->reduceWithOperator($homeworkItem, $operations[0][$index]);
        }

        $rightToLeft = [];
        $rightToLeftIndex = 0;
        for ($i = strlen($dataLines[0]) - 1; $i >= 0; $i--) {
            $number = '';
            for ($j = 0; $j < count($dataLines); $j++) {
                $number .= $dataLines[$j][$i];
            }
            if (intval($number) === 0) {
                $rightToLeftIndex++;
            } else {
                $rightToLeft[$rightToLeftIndex][] = intval($number);
            }
        }

        // Right to left needs the operators reversed.
        $operations[0] = array_reverse($operations[0]);

        foreach ($rightToLeft as $index => $homeworkItem) {
            $totalRightToLeft += $this->reduceWithOperator($homeworkItem, $operations[0][$index]);
        }

        $this->info('Grand total: ' . $total);
        $this->info('Grand total right-to-left: ' . $totalRightToLeft);
    }

    private function reduceWithOperator(array $items, string $operator): int
    {
        $initial = match ($operator) {
            '*' => 1,
            '+' => 0,
        };

        return array_reduce($items, fn (mixed $carry, string $item) => match ($operator) {
            '*' => $carry * intval($item),
            '+' => $carry + intval($item),
        }, $initial);
    }
}
