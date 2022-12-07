<?PHP

$elves = [];
$calories = explode(PHP_EOL, file_get_contents('./input.txt'));

foreach($calories as $elvCalories) {
    if ($elvCalories === '') {
        $elves[] = 0;
        continue;
    }
    if(empty($elves)) {
        $elves[] = $elvCalories;
        continue;
    }

    $elves[array_key_last($elves)] += $elvCalories;
}

print "Step 1: " . max($elves) . PHP_EOL;

rsort($elves);

print "Step 2: " . $elves[0] + $elves[1] + $elves[2] .PHP_EOL;

