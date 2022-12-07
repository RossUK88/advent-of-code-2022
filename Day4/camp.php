<?php

$items = array_filter(explode(PHP_EOL, file_get_contents('./input.txt')), fn($item) => $item !== '');

$fullyContained = 0;
$partiallyContained = 0;
foreach($items as $item) {
    $elfSchedule = explode(",", $item);
    $elfOne = explode("-", $elfSchedule[0]);
    $elfTwo = explode("-", $elfSchedule[1]);

    if(min($elfOne) <= min($elfTwo) && max($elfOne) >= max($elfTwo)) {
        ++$fullyContained;
    } else if(min($elfTwo) <= min($elfOne) && max($elfTwo) >= max($elfOne)) {
        ++$fullyContained;
    }

    if(min($elfOne) <= min($elfTwo) && max($elfOne) >= min($elfTwo)) {
        ++$partiallyContained;
    } else if(min($elfOne) <= max($elfTwo) && max($elfOne) >= max($elfTwo)) {
        ++$partiallyContained;
    } else if(min($elfTwo) <= min($elfOne) && max($elfTwo) >= min($elfOne)) {
        ++$partiallyContained;
    } else if(min($elfTwo) <= max($elfOne) && max($elfTwo) >= max($elfOne)) {
        ++$partiallyContained;
    }

}

echo "Part 1: " . $fullyContained . PHP_EOL;
echo "Part 2: " . $partiallyContained . PHP_EOL;
