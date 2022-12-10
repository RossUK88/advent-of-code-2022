<?php

class Cathode
{
    protected int $register = 1;
    protected array $items = [];
    protected int $cycle = 1;
    protected bool $testing = false;
    public int $signalStrength = 0;


    public function __construct()
    {
        $this->items = array_filter(explode(PHP_EOL, file_get_contents($this->testing ? './test_input.txt' : './input.txt')),
            fn($item) => $item !== '');
    }

    public function solve(): int
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

    private function cycle(): void
    {
        if (in_array($this->cycle, [20, 60, 100, 140, 180, 220])) {
            $this->signalStrength += $this->cycle * $this->register;
        }

        $this->cycle += 1;
    }
}

print "Part 1 : " . (new Cathode())->solve() .PHP_EOL;
