<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_11 extends Command
{
    protected $signature = 'advent:2024:11 {--debug} {--stdin}';

    protected $description = '2024 - Day 11: Plutonian Pebbles';

    private string $data = <<<'TEXT'
125 17
TEXT;

    private array $stones = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $this->stones = explode(' ', $data);

        for ($i = 0; $i < 25; $i++) {
            $this->blink();
        }

        $this->info('Amount of stones after blinking 25 times: ' . count($this->stones));
    }

    private function blink(): void
    {
        $newStones = [];

        foreach ($this->stones as $stone) {
            if ($stone === '0') {
                $newStones[] = '1';
                continue;
            }

            $length = strlen($stone);
            if ($length % 2 === 0) {
                $newStones[] = substr($stone, 0, $length / 2);
                $newStones[] = (string) (int) substr($stone, $length / 2);
            } else {
                $newStones[] = (string) (intval($stone) * 2024);
            }
        }

        $this->option('debug') && $this->comment(implode(' ', $newStones));

        $this->stones = $newStones;
    }
}