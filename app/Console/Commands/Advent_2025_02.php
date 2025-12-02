<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2025_02 extends Command
{
    protected $signature = 'advent:2025:2 {--stdin}';

    protected $description = '2025 - Day 2: Gift Shop';

    private string $data = <<<'TEXT'
11-22,95-115,998-1012,1188511880-1188511890,222220-222224,1698522-1698528,446443-446449,38593856-38593862,565653-565659,824824821-824824827,2121212118-2121212124
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(',', $data);
        $invalidIDSum = 0;
        $invalidIDNew = 0;

        foreach ($dataLines as $line) {
            preg_match('/(\d+)-(\d+)/', $line, $matches);
            $rangeStart = (int) $matches[1];
            $rangeEnd = (int) $matches[2];

            for ($id = $rangeStart; $id <= $rangeEnd; $id++) {
                $length = strlen($id);

                if ($length % 2 === 0) {
                    if (substr($id, 0, $length / 2) === substr($id, $length / 2)) {
                        $invalidIDSum += $id;
                    }
                }

                for ($i = 1; $i <= $length; $i++) {
                    $sequence = substr($id, 0, $i);

                    preg_match('/^(' . $sequence . ')+$/', $id, $matches);

                    if ($matches && $matches[0] !== $matches[1]) {
                        $invalidIDNew += $id;
                        continue(2); // Repeating pattern found, no need to further check this ID.
                    }
                }
            }
        }

        $this->info('Sum of invalid IDs: ' . $invalidIDSum);
        $this->info('Sum of invalid IDs using new rules: ' . $invalidIDNew);
    }
}
