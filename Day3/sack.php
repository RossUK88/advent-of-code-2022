<?php

$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
$items = explode(PHP_EOL, file_get_contents('./input.txt'));

$prioArray = array_flip(str_split($alphabet));
$sum = 0;
$partTwoSum = 0;

foreach ($items as $item) {
    if ($item === '') continue;
    $compartments = str_split($item, strlen($item) / 2);

    $compartmentA = str_split($compartments[0]);
    $compartmentB = str_split($compartments[1]);

    $dupe = array_values(array_unique(array_intersect($compartmentA, $compartmentB)));

    $sum += $prioArray[$dupe[0]] + 1;
}

print "Part 1: " . $sum . PHP_EOL;

$part2Groups = array_filter(array_chunk($items, 3), fn($items) => count($items) === 3);

foreach ($part2Groups as $items) {
    $item1 = str_split($items[0]);
    $item2 = str_split($items[1]);
    $item3 = str_split($items[2]);

    $dupe = array_values(array_unique(array_intersect($item1, $item2, $item3)));

    $partTwoSum += $prioArray[$dupe[0]] + 1;
}
print "Part 2: " . $partTwoSum . PHP_EOL;
