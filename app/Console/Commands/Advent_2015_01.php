<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2015_01 extends Command
{
    protected $signature = 'advent:2015:1 {--stdin}';

    protected $description = '2015 - Day 1: Not Quite Lisp';

    private string $data = <<<'TEXT'
()())
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $instructions = str_split($data);
        $floor = 0;
        $basement = 0;

        foreach ($instructions as $index => $instruction) {
            if ($instruction === '(') {
                $floor++;
            } else {
                $floor--;
            }
            if ($floor === -1 && $basement === 0) {
                $basement = $index + 1;
            }
        }

        $this->info('Floor: ' . $floor);
        $this->info('Basement position: ' . $basement);
    }
}
