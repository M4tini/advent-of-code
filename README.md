# Advent of Code

My solutions for https://adventofcode.com

## Installation

- Run `sail up -d`
- Run `sail composer install`

## Usage

Run `sail up -d` and then one of the following advent solutions:

```bash
sail artisan advent:2023:1 --debug
sail artisan advent:2023:2 --debug
sail artisan advent:2023:3 --debug
sail artisan advent:2023:4 --debug

sail artisan advent:2024:1
sail artisan advent:2024:2
sail artisan advent:2024:3
sail artisan advent:2024:4 --debug
sail artisan advent:2024:5 --debug
sail artisan advent:2024:6 --debug
sail artisan advent:2024:7
sail artisan advent:2024:8
sail artisan advent:2024:9 --debug
sail artisan advent:2024:10 --debug
```

### Options

Custom puzzle input can be loaded from a file using `--stdin`, for example:

```bash
sail artisan advent:2024:3 --stdin < input.txt
```

Some commands offer `--debug` information to show how the data is being evaluated.
