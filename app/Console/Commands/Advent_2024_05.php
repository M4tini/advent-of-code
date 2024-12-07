<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class Advent_2024_05 extends Command implements PromptsForMissingInput
{
    protected $signature = 'advent:2024:5 {--debug} {--stdin}';

    protected $description = '2024 - Day 5: Print Queue';

    private string $data = <<<'TEXT'
47|53
97|13
97|61
97|47
75|29
61|13
75|53
29|13
97|29
53|29
61|53
97|53
61|29
47|13
75|47
97|75
47|61
75|61
47|29
75|13
53|13

75,47,61,53,29
97,61,53,29,13
75,29,13
75,97,47,61,53
61,13,29
97,13,75,29,47
TEXT;

    public function handle(): void
    {
        $data = $this->option('stdin') ? file_get_contents('php://stdin') : $this->data;
        $dataLines = explode(PHP_EOL, $data);
        $orderingRules = [];
        $result = 0;

        foreach ($dataLines as $line) {
            if (str_contains($line, '|')) {
                $orderingRules[] = explode('|', $line);
            }
        }

        foreach ($dataLines as $line) {
            if (str_contains($line, ',')) {
                $numberSequence = explode(',', $line);

                foreach ($numberSequence as $index => $number) {
                    if (!$this->followsTheRules($index, $numberSequence, $orderingRules)) {
                        continue(2);
                    }
                }

                $middleIndex = count($numberSequence) / 2;
                $result += (int) $numberSequence[$middleIndex];

                if ($this->option('debug')) {
                    $this->comment('Adding: ' . $numberSequence[$middleIndex]);
                }
            }
        }

        $this->info('Middle page number sum of correctly-ordered updates: ' . $result);
    }

    private function followsTheRules(int $index, array $updateNumbers, array $orderingRules): bool
    {
        $number = $updateNumbers[$index];

        foreach ($orderingRules as $rule) {
            if ($rule[0] === $number) {
                if (in_array($rule[1], array_slice($updateNumbers, 0, max(0, $index)))) {
                    if ($this->option('debug')) {
                        $this->comment('Rule ' . implode(' ', $rule) . ' disqualified ' . $number . ' in ' . implode(',', $updateNumbers));
                    }
                    return false;
                }
            }
            if ($rule[1] === $number) {
                if (in_array($rule[0], array_slice($updateNumbers, $index + 1))) {
                    if ($this->option('debug')) {
                        $this->comment('Rule ' . implode(' ', $rule) . ' disqualified ' . $number . ' in ' . implode(',', $updateNumbers));
                    }
                    return false;
                }
            }
        }

        return true;
    }
}
