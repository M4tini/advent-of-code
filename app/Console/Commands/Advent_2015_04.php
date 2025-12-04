<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2015_04 extends Command
{
    protected $signature = 'advent:2015:4 {--stdin}';

    protected $description = '2015 - Day 4: The Ideal Stocking Stuffer';

    private string $data = <<<'TEXT'
abcdef
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;

        $this->info('MD5 secret key starting with 00000: ' . $this->findMd5($data, '00000'));
        $this->info('MD5 secret key starting with 000000: ' . $this->findMd5($data, '000000'));
    }

    /**
     * Instead of recursively calling this function (which I did at first, exceeding nesting limits) it uses a loop.
     */
    private function findMd5(string $data, $startsWith): int
    {
        $number = 1;

        while (true) {
            $md5 = md5($data . $number);

            if (str_starts_with($md5, $startsWith)) {
                return $number;
            }

            $number++;
        }
    }
}
