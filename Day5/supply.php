<?php
$testing = true;
$totalStacks = $testing ? 3 : 9;
$stacks = [];
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');
$procedure = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_procedure.txt' : './procedure.txt')), fn($item) => $item !== '');


for($i = 1; $i <= $totalStacks; $i++) {
    $stacks[] = [];
}
//1 1
//2 5
//3 9
//4 13
//5 17
//
// 1 = 0
// ((i - 1) + 4) + 1
foreach($items as $item) {

    for($i = 1; $i <= $totalStacks; $i++) {
        if($i === 1) {
            $offset = 1;
        } else {
            $offset = (($i - 1) * 4) + 1;
        }

        if(isset($item[$offset]) && $item[$offset] !== ' ') {
            $stacks[$i - 1][] = $item[$offset];
        }
    }
}

foreach ($procedure as $movement) {
    preg_match_all("/\d+/", $movement, $matches);
    [$amount, $from, $to] = $matches[0];

    for ($i = 1; $i <= $amount; $i++) {
        $itemToMove = array_shift($stacks[$from - 1]);
        array_unshift($stacks[$to - 1], $itemToMove);
    }
}

print "Part 1: " . implode("", array_map(fn($stack) => $stack[0], $stacks)) .PHP_EOL;

