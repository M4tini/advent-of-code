<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_01 extends Command
{
    protected $signature = 'advent:2025:1 {--debug} {--stdin}';

    protected $description = '2025 - Day 1: Secret Entrance';

    private string $data = <<<'TEXT'
L68
L30
R48
L5
R60
L55
L1
L99
R14
L82
TEXT;

    private int $dial = 50;
    private int $zeroPassed = 0;
    private int $zeroPointed = 0;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);

        foreach ($dataLines as $line) {
            preg_match('/(\D)(\d+)/', $line, $matches);

            $this->dial = $this->countZero($matches[1], (int) $matches[2]);
        }

        $this->info('Password: ' . $this->zeroPointed);
        $this->info('Password method 0x434C49434B: ' . $this->zeroPassed);
    }

    private function countZero(string $direction, int $clicks): int
    {
        // The amount of clicks can contain an amount of full circles (100 clicks)
        $this->zeroPassed += intval($clicks / 100);
        $remainingClicks = $clicks % 100;

        $newDial = ($direction === 'R')
            ? $this->dial + $remainingClicks
            : $this->dial - $remainingClicks + 100;
        $newDial %= 100;

        $this->option('debug') && $this->info($direction . ' ' . $clicks . ' FROM ' . $this->dial . ' TO ' . $newDial);

        if ($newDial === 0) {
            $this->zeroPassed++;
            $this->zeroPointed++;
        } else {
            if ($this->dial !== 0) {
                if ($direction === 'R' && $newDial < $this->dial) {
                    $this->zeroPassed++;
                }
                if ($direction === 'L' && $newDial > $this->dial) {
                    $this->zeroPassed++;
                }
            }
        }

        $this->option('debug') && $this->warn('PASSED ' . $this->zeroPassed);

        return $newDial;
    }
}
