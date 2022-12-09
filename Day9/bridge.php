<?php
$testing = false;
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');

class Point {

    public array $visited = [];

    public function __construct(public int $x = 0, public int $y = 0) {
        $this->visited[] = sprintf("%d-%d", $this->x, $this->y);
    }

    public function countVisited(): int
    {
        return count(array_unique($this->visited));
    }

    private function isNeighbour(Point $point): bool
    {
        return ($point->x - 1 === $this->x || $point->x + 1 === $this->x || $point->x === $this->x)
            && ($point->y - 1 === $this->y || $point->y + 1 === $this->y || $point->y === $this->y);
    }

    public function move(string $direction, int $amount, Point $tail): self {
        for($i = 1; $i <= $amount; $i++) {
            switch($direction) {
                case 'R':
                    $this->horizontal(1);
                    if(!$this->isNeighbour($tail)) {
                        $tail->y = $this->y;
                        $tail->horizontal(1);
                    }
                    break;
                case 'L':
                    $this->horizontal(-1);
                    if(!$this->isNeighbour($tail)) {
                        $tail->y = $this->y;
                        $tail->horizontal(-1);
                    }
                    break;
                case 'U':
                    $this->vertical(1);
                    if(!$this->isNeighbour($tail)) {
                        $tail->x = $this->x;
                        $tail->vertical(1);
                    }
                    break;
                case 'D':
                    $this->vertical(-1);
                    if(!$this->isNeighbour($tail)) {
                        $tail->x = $this->x;
                        $tail->vertical(-1);
                    }
                    break;
            }

            $this->visited[] = sprintf("%d-%d", $this->x, $this->y);
            $tail->visited[] = sprintf("%d-%d", $tail->x, $tail->y);
        }

        return $this;
    }
    public function horizontal(int $amount): void
    {
        $this->x += $amount;
    }
    public function vertical(int $amount): void
    {
        $this->y += $amount;
    }
}

$head = new Point(0, 0);
$tail = new Point(0, 0);

for ($i = 0; $i < count($items); $i++) {
    $actions = explode(" ", $items[$i]);

    $head->move($actions[0], $actions[1], $tail);
}

print_r(array_values(array_unique($tail->visited)));

echo "Part 1: " . $tail->countVisited() . PHP_EOL;
