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

    private array $deviceMemory = [];

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);

        foreach ($dataLines as $dataLine) {
            preg_match('/(.+): (.+)/', $dataLine, $matches);

            $this->devices[$matches[1]] = explode(' ', $matches[2]);
        }

        $countYou = $this->countPaths('you', true, true, 'out');
        $countSvr = $this->countPaths('svr', false, false, 'out');

        $this->info('Amount of paths from you to out: ' . $countYou);
        $this->info('Amount of paths from svr to out: ' . $countSvr);
    }

    // Passing array &$results causes memory limits, so the friend suggested to use a global memory to track totals.
    private function countPaths(string $device, bool $seenDac, bool $seenFft, string $target): int
    {
        if ($device === 'dac') $seenDac = true;
        if ($device === 'fft') $seenFft = true;

        if ($device === $target) {
            return ($seenDac && $seenFft) ? 1 : 0;
        }

        if (isset($this->deviceMemory[$device][$seenDac][$seenFft])) {
            return $this->deviceMemory[$device][$seenDac][$seenFft];
        }

        $total = 0;
        foreach ($this->devices[$device] as $nextDevice) {
            $total += $this->countPaths($nextDevice, $seenDac, $seenFft, $target);
        }

        return $this->deviceMemory[$device][$seenDac][$seenFft] = $total;
    }
}
