<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2024_09 extends Command
{
    protected $signature = 'advent:2024:9 {--debug} {--stdin}';

    protected $description = '2024 - Day 9: Disk Fragmenter';

    private string $data = <<<'TEXT'
2333133121414131402
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $diskMap = str_split($data);

        $block = 0;
        $disk = collect($diskMap)
            ->map(function (string $char, int $index) use (&$block) {
                return $index % 2 === 1
                    ? str_repeat('.', (int) $char)
                    : str_repeat((string) $block++ % 10, (int) $char); // TODO: remove modulo to work with 10 etc
            })
            ->join('');

        while (str_contains($disk, '.')) {
            $this->option('debug') && $this->comment($disk);

            $disk = preg_replace('/\./', substr($disk, -1), $disk, 1);
            $disk = substr($disk, 0, -1);
        }

        $checksum = collect(str_split($disk)) // TODO: convert string to array to work with file ID numbers above 10
            ->map(fn (string $char, int $index) => $index * (int) $char)
            ->sum();

        $this->info('Filesystem checksum: ' . $checksum);
    }
}
