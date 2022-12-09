<?php
$testing = false;
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');

class Point {

    public array $visited = [];

    public function __construct(public int $x = 0, public int $y = 0) {
        $this->visited[] = sprintf("%d-%d", $this->x, $this->y);
    }
}

class Rope {
    public array $knots;

    public function __construct(public int $amountOfKnots = 9) {
        for($i = 1; $i <= $amountOfKnots; $i++) {
            $this->knots[] = new Point(0,0);
        }
    }
    private function sign($x)
    {
        if($x === 0) {
            return 0;
        }
        if($x < 0) {
            return -1;
        }

        return 1;
    }
    private function trailing(Point $head, Point $tail): bool
    {
        $dx = $head->x - $tail->x;
        $dy = $head->y - $tail->y;

        if(abs($dx) <= 1 && abs($dy) <= 1) {
            return false;
        }

        $tail->x += $this->sign($dx);
        $tail->y += $this->sign($dy);
        $tail->visited[] = sprintf("%d-%d", $tail->x, $tail->y);
        return true;
    }
    public function move(string $direction, int $amount): void
    {
        $dir = [
            "R" => [1, 0],
            "U" => [0, 1],
            "L" => [-1, 0],
            "D" => [0, -1],
        ];
        foreach(range(1, $amount) as $m) {
            [$x, $y] = $dir[$direction];
            $this->knots[0]->x += $x;
            $this->knots[0]->y += $y;

            for($i = 0; $i < $this->amountOfKnots - 1; $i++) {
                if(!$this->trailing($this->knots[$i], $this->knots[$i + 1])) {
                    break;
                }

                $this->knots[$i]->visited[] = sprintf("%d-%d", $this->knots[$i]->x, $this->knots[$i]->y);
            }


        }
    }
}


$part1 = new Rope(2);
for ($i = 0; $i < count($items); $i++) {
    $actions = explode(" ", $items[$i]);

    $part1->move($actions[0], $actions[1]);
}

$part2 = new Rope(10);
for ($i = 0; $i < count($items); $i++) {
    $actions = explode(" ", $items[$i]);

    $part2->move($actions[0], $actions[1]);
}

echo "Part 1: " .  count(array_unique($part1->knots[count($part1->knots) - 1]->visited)) .PHP_EOL;
echo "Part 2: " .  count(array_unique($part2->knots[count($part2->knots) - 1]->visited)) .PHP_EOL;
