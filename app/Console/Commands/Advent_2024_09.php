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
                    : str_repeat((string) $block++ % 10, (int) $char);
            })
            ->join('');

        $compactDisk = $this->compact($disk);

        $checksum = collect(str_split($compactDisk))
            ->map(fn (string $char, int $index) => $index * (int) $char)
            ->sum();

        $this->info('Filesystem checksum: ' . $checksum);
    }

    private function compact(string $disk): string
    {
        $this->option('debug') && $this->comment($disk);

        $trimmedDisk = trim($disk, '.');

        if (!str_contains($trimmedDisk, '.')) {
            return $disk;
        }

        $dotIndex = strpos($disk, '.');
        $numIndex = strlen($trimmedDisk) - 1;

        $diskParts = str_split($disk);
        $diskParts[$dotIndex] = $diskParts[$numIndex];
        $diskParts[$numIndex] = '.';

        return $this->compact(implode($diskParts));
    }
}
