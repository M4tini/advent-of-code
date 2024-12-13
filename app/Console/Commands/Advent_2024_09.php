<?php

declare(strict_types=1);

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

        $id = 0;
        $disk = collect($diskMap)
            ->flatMap(function (string $char, int $index) use (&$id) {
                return $index % 2 === 1
                    ? array_fill(0, (int) $char, '.')
                    : array_fill(0, (int) $char, $id++);
            });

        $arrayToReduce = $disk->toArray();
        $dotsToReplace = $disk->where(fn (string $char) => $char === '.')->toArray();

        foreach ($dotsToReplace as $index => $dot) {
            $this->option('debug') && $this->comment(implode('', $arrayToReduce));

            if ($index > count($arrayToReduce) - 1) {
                break;
            }

            do {
                $replacement = array_pop($arrayToReduce);
            } while ($replacement === '.');

            $arrayToReduce[$index] = $replacement;
        }

        $checksum = collect($arrayToReduce)
            ->map(fn (int $char, int $index) => $index * $char)
            ->sum();

        $this->info('Filesystem checksum: ' . $checksum);
    }
}
