<?php

class Cathode
{
    protected int $register = 1;
    protected array $items = [];
    protected int $cycle = 1;
    protected bool $testing = false;
    public int $signalStrength = 0;

    public array $grid = [];

    public function __construct()
    {
        $this->items = array_filter(explode(PHP_EOL, file_get_contents($this->testing ? './test_input.txt' : './input.txt')),
            fn($item) => $item !== '');

        foreach(range(0, 5) as $row) {
            $row = [];
            foreach(range(0, 39) as $column) {
                $row[$column] = ".";
            }
            $this->grid[]  = $row;
        }
    }

    public function solve1(): int
    {
        foreach ($this->items as $item) {
            if (str_starts_with($item, "noop")) {
                $this->cycle();
            } else {
                $operations = explode(" ", $item);
                $this->cycle();
                $this->cycle();
                $this->register += (int)$operations[1];
            }
        }

        return $this->signalStrength;
    }

    public function solve2(): void
    {
        foreach ($this->items as $item) {
            if (str_starts_with($item, "noop")) {
                $this->cycle(true);
            } else {
                $operations = explode(" ", $item);
                $this->cycle(true);
                $this->cycle(true);
                $this->register += (int)$operations[1];
            }
        }


        foreach(range(0, 5) as $row) {
            foreach(range(0, 39) as $column) {
                print $this->grid[$row][$column];
            }
            print PHP_EOL;
        }
    }

    private function cycle(bool $turnOnLed = false): void
    {
        if (in_array($this->cycle, [20, 60, 100, 140, 180, 220])) {
            $this->signalStrength += $this->cycle * $this->register;
        }

        if($turnOnLed) {
            if(abs((($this->cycle % 40) - 1) - $this->register) <= 1) {
                $this->grid[floor($this->cycle / 40)][($this->cycle % 40) - 1] = "#";
            }
        }

        $this->cycle += 1;
    }
}

print "Part 1 : " . (new Cathode())->solve1() .PHP_EOL;

print "Part 2 : " . PHP_EOL;
(new Cathode())->solve2();

