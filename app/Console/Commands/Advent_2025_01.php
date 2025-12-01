<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_01 extends Command
{
    protected $signature = 'advent:2025:1 {--stdin}';

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

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $dial = 50;
        $password = 0;

        foreach ($dataLines as $line) {
            preg_match('/(\D)(\d+)/', $line, $matches);
            $rotation = $matches[1];
            $clicks = (int) $matches[2];

            // Rotate the dial
            $dial = ($rotation === 'L')
                ? $dial - $clicks % 100
                : $dial + $clicks % 100;

            // Limit to 0 - 99
            $dial %= 100;

            if ($dial === 0) {
                $password++;
            }
        }

        $this->info('Password: ' . $password);
    }
}
