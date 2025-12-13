<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;

class Advent_2025_11 extends Command
{
    protected $signature = 'advent:2025:11 {--stdin}';

    protected $description = '2025 - Day 11: Reactor';

    private string $data = <<<'TEXT'
aaa: you hhh
you: bbb ccc
bbb: ddd eee
ccc: ddd eee fff
ddd: ggg
eee: out
fff: out
ggg: out
hhh: ccc fff iii
iii: out
TEXT;

    private array $devices = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $paths = 0;
        $devices = [];

        foreach ($dataLines as $dataLine) {
            preg_match('/(.+): (.+)/', $dataLine, $matches);

            $this->devices[$matches[1]] = explode(' ', $matches[2]);
        }

        $results = [];
        $this->findOut([], 'you', $results);

        $this->info('Amount of paths from you to out: ' . count($results));
    }

    private function findOut(array $path, string $deviceKey, array &$results): void
    {
        if ($deviceKey === 'out') {
            $results[] = $path;
            return;
        }

        foreach ($this->devices[$deviceKey] as $nextValue) {
            $path[] = $deviceKey;

            $this->findOut($path, $nextValue, $results);
        }
    }
}
