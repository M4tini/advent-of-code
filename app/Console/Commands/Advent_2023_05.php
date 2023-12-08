<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Advent_2023_05 extends Command
{
    protected $signature = 'advent:2023:5 {--debug} {--input=}';

    protected $description = '2023 - Day 5: If You Give A Seed A Fertilizer';

    private string $data = <<<TEXT
seeds: 79 14 55 13

seed-to-soil map:
50 98 2
52 50 48

soil-to-fertilizer map:
0 15 37
37 52 2
39 0 15

fertilizer-to-water map:
49 53 8
0 11 42
42 0 7
57 7 4

water-to-light map:
88 18 7
18 25 70

light-to-temperature map:
45 77 23
81 45 19
68 64 13

temperature-to-humidity map:
0 69 1
1 0 69

humidity-to-location map:
60 56 37
56 93 4
TEXT;

    public function handle(): void
    {
        $data = $this->option('input') ?? $this->data;
        $locationNumber = 0;

        preg_match_all('/\D+:\s*([\s*\d]+)\s*/', $data, $matches);

        $seeds = array_filter(explode(' ', $matches[1][0]));
        $seeds2soil = array_filter(explode(' ', $matches[1][1]));
        $soil2fertilizer = array_filter(explode(' ', $matches[1][2]));
        $fertilizer2water = array_filter(explode(' ', $matches[1][3]));
        $water2light = array_filter(explode(' ', $matches[1][4]));
        $light2temperature = array_filter(explode(' ', $matches[1][5]));
        $temperature2humidity = array_filter(explode(' ', $matches[1][6]));
        $humidity2location = array_filter(explode(' ', $matches[1][7]));

        $this->info('Location number: ' . $locationNumber);
    }
}
