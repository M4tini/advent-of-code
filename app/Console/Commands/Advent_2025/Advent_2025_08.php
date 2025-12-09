<?php

namespace App\Console\Commands\Advent_2025;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class Advent_2025_08 extends Command
{
    protected $signature = 'advent:2025:8 {--depth=10} {--stdin}';

    protected $description = '2025 - Day 8: Playground';

    private string $data = <<<'TEXT'
162,817,812
57,618,57
906,360,560
592,479,940
352,342,300
466,668,158
542,29,236
431,825,988
739,650,466
52,470,668
216,146,977
819,987,18
117,168,530
805,96,715
346,949,466
970,615,88
941,993,340
862,61,35
984,92,344
425,690,689
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $circuits = [];

        // For each junction box, we calculate the distance to all other junction boxes.
        $junctionBoxes = $dataLines;
        $junctionDistances = [];
        foreach ($junctionBoxes as $boxP) {
            $circuits[] = [$boxP];

            foreach ($junctionBoxes as $boxQ) {
                if ($boxP !== $boxQ) {
                    $p = explode(',', $boxP);
                    $q = explode(',', $boxQ);
                    $r = sqrt(pow($p[0] - $q[0], 2) + pow($p[1] - $q[1], 2) + pow($p[2] - $q[2], 2));

                    $key = $boxP < $boxQ ? $boxP . $boxQ : $boxQ . $boxP;

                    $junctionDistances[$key] = [
                        'p' => $boxP,
                        'q' => $boxQ,
                        'r' => $r,
                    ];
                }
            }
        }

        // Then we sort the result and process the $depth shortest connections (10 for testing, 1000 for puzzle input).
        usort($junctionDistances, fn (array $a, array $b): int => $a['r'] <=> $b['r']);
        $junctionBoxesToConnect = array_splice($junctionDistances, 0, (int) $this->option('depth'));

        foreach ($junctionBoxesToConnect as $junctionBoxes) {
            foreach ($circuits as $circuitKey => $circuitValues) {
                if (in_array($junctionBoxes['p'], $circuitValues) && in_array($junctionBoxes['q'], $circuitValues)) {
                    // Nothing happens!
                    continue(2);
                }
                if (in_array($junctionBoxes['p'], $circuitValues) && !in_array($junctionBoxes['q'], $circuitValues)) {
                    // Find circuit key which contains q and merge with current key (unset old key)
                    $key = array_find_key($circuits, fn (array $values) => in_array($junctionBoxes['q'], $values));
                    $circuits[$circuitKey] = array_merge($circuitValues, $circuits[$key]);
                    unset($circuits[$key]);

                    continue(2);
                }
                if (!in_array($junctionBoxes['p'], $circuitValues) && in_array($junctionBoxes['q'], $circuitValues)) {
                    // Find circuit key which contains p and merge with current key (unset old key)
                    $key = array_find_key($circuits, fn (array $values) => in_array($junctionBoxes['p'], $values));
                    $circuits[$circuitKey] = array_merge($circuitValues, $circuits[$key]);
                    unset($circuits[$key]);

                    continue(2);
                }
            }
        }

        // Then we sum the size of the 3 biggest circuits.
        $size = collect($circuits)
            ->map(fn (array $circuit) => count($circuit))
            ->sortDesc()
            ->splice(0, 3)
            ->all();

        $this->info('Multiplied size of the three largest circuits: ' . array_product($size)); // 5202 too low
    }
}
