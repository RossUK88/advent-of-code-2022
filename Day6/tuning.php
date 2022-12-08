<?php
$testing = false;
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');

$dataBuffer = str_split($items[0]);
function findMarker(array $items, int $distinct): int {
    $buffer = [];
    for($i = 0; $i <= count($items); $i++) {
        if(count($buffer) < $distinct) {
            $buffer[] = $items[$i];
            continue;
        }

        if(count(array_unique($buffer)) === $distinct) {
            return $i;
        }

        array_shift($buffer);
        $buffer[] = $items[$i];
    }
}

echo "Part 1: ". findMarker($dataBuffer, 4).PHP_EOL;
echo "Part 2: ". findMarker($dataBuffer, 14).PHP_EOL;
