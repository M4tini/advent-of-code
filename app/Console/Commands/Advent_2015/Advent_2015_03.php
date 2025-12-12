<?php

namespace App\Console\Commands\Advent_2015;

use Illuminate\Console\Command;

class Advent_2015_03 extends Command
{
    protected $signature = 'advent:2015:3 {--stdin}';

    protected $description = '2015 - Day 3: Perfectly Spherical Houses in a Vacuum';

    private string $data = <<<'TEXT'
^>v<
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $moves = str_split($data);
        $housesVisitedPart1 = [0 => [0 => 1]];
        $x = 0;
        $y = 0;
        $housesVisitedPart2 = [0 => [0 => 1]];
        $santaX = 0;
        $santaY = 0;
        $robotX = 0;
        $robotY = 0;
        $santaOrRobot = 1;

        foreach ($moves as $move) {
            // Santa (part 1)
            switch ($move) {
                case '^': $y++; break;
                case '>': $x++; break;
                case 'v': $y--; break;
                case '<': $x--; break;
            }
            if (isset($housesVisitedPart1[$x][$y])) {
                $housesVisitedPart1[$x][$y] += 1;
            } else {
                $housesVisitedPart1[$x][$y] = 1;
            }

            // Santa and Robo-Santa (part 2)
            switch ($move) {
                case '^': $santaOrRobot > 0 ? $santaY++ : $robotY++; break;
                case '>': $santaOrRobot > 0 ? $santaX++ : $robotX++; break;
                case 'v': $santaOrRobot > 0 ? $santaY-- : $robotY--; break;
                case '<': $santaOrRobot > 0 ? $santaX-- : $robotX--; break;
            }
            $x = $santaOrRobot > 0 ? $santaX : $robotX;
            $y = $santaOrRobot > 0 ? $santaY : $robotY;
            if (isset($housesVisitedPart2[$x][$y])) {
                $housesVisitedPart2[$x][$y] += 1;
            } else {
                $housesVisitedPart2[$x][$y] = 1;
            }
            $santaOrRobot *= -1;
        }

        $this->info('Houses visited: ' . collect($housesVisitedPart1)->flatten()->count());
        $this->info('Houses visited using Robo-Santa: ' . collect($housesVisitedPart2)->flatten()->count());
    }
}
