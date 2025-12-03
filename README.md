# Advent of Code

My solutions for https://adventofcode.com

## Installation

- Run `sail up -d`
- Run `sail composer install`

## Usage

Run `sail up -d` and then one of the following advent solutions:

<details>
<summary>2️⃣0️⃣1️⃣5️⃣ solutions</summary>

```bash
sail artisan advent:2015:1
sail artisan advent:2015:2
sail artisan advent:2015:3
```
</details>
<details>
<summary>2️⃣0️⃣2️⃣3️⃣ solutions</summary>

```bash
sail artisan advent:2023:1 --debug
sail artisan advent:2023:2 --debug
sail artisan advent:2023:3 --debug
sail artisan advent:2023:4 --debug
```
</details>
<details>
<summary>2️⃣0️⃣2️⃣4️⃣ solutions</summary>

```bash
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
sail artisan advent:2024:11 --debug
sail artisan advent:2024:12 --debug
```
</details>
<details>
<summary>2️⃣0️⃣2️⃣5️⃣ solutions</summary>

```bash
sail artisan advent:2025:1 --debug
sail artisan advent:2025:2
sail artisan advent:2025:3
```
</details>

### Options

Custom puzzle input can be loaded from a file using `--stdin`, for example:

```bash
sail artisan advent:2024:3 --stdin < input.txt
```

Some commands offer `--debug` information to show how the data is being evaluated.
