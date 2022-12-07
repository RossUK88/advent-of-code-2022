<?php
// Rock A X //1
// PAPER B Y //2
// SCiS C Z // 3
//
//Loss 0
//Draw 3
//Win 6
$moves = explode(PHP_EOL, file_get_contents('./input.txt'));
$totalPoints = 0;
$stepTwo = 0;

$scores = [
    'A X' => 1 + 3,
    'A Y' => 2 + 6,
    'A Z' => 3 + 0,
    'B X' => 1 + 0,
    'B Y' => 2 + 3,
    'B Z' => 3 + 6,
    'C X' => 1 + 6,
    'C Y' => 2 + 0,
    'C Z' => 3 + 3,
];
$requiredResults = [
    'A X' => 'A Z',
    'A Y' => 'A X',
    'A Z' => 'A Y',
    'B X' => 'B X',
    'B Y' => 'B Y',
    'B Z' => 'B Z',
    'C X' => 'C Y',
    'C Y' => 'C Z',
    'C Z' => 'C X',
];


foreach($moves as $move) {
    if($move === '') continue;

    $totalPoints += $scores[$move];

    $stepTwo += $scores[$requiredResults[$move]];
}

echo "Part 1: " .  $totalPoints .PHP_EOL;
echo "Part 2: " .  $stepTwo .PHP_EOL;


