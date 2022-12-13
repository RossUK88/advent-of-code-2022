<?php

$testing = true;

$testMonkeys = [
    new Monkey([79, 98], fn($old) => $old * 19, 23, 2, 3),
    new Monkey([54, 65, 75, 74], fn($old) => $old + 6, 19, 2, 0),
    new Monkey([79, 60, 97], fn($old) => $old * $old, 13, 1, 3),
    new Monkey([74], fn($old) => $old + 3, 17, 0, 1),
];
$prodMonkeys = [

];
class Monkey {
    public function __construct(public array $items, public Closure $operation, public int $divisible, public int $truthy, public int $falsey)
    {
    }
}

$worryLevel = 0;
$monkeys = $testing ? $testMonkeys : $prodMonkeys;
foreach($monkeys as $monkey) {
    for($i = 0; $i < count($monkey->items); $i++) {
//    foreach($monkey->items as $item) {
        $worryForItem = $monkey->operation;
        $worryForItem = $worryForItem($monkey->items[$i]);

        $worryLevel = $worryForItem / 3;
//        print $worryForItem .PHP_EOL;
//        print $worryLevel .PHP_EOL;
//        print $worryLevel % $monkey->divisible .PHP_EOL;
//        print $worryLevel;
        if($worryLevel % $monkey->divisible === 0) {
            $monkeys[$monkey->truthy]->items[] = floor($worryLevel);
        } else {
            $monkeys[$monkey->falsey]->items[] = floor($worryLevel);
        }
        unset($monkey->items[$i]);

//        print_r($monkeys);

//        die;
    }
}
print_r($monkeys);
