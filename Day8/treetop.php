<?php
$testing = false;
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');

$grid = [];
// Build the grid
for($i = 0; $i < count($items); $i++) {
    $columns = str_split($items[$i]);
    for($j = 0; $j < count($columns); $j++) {
        $grid[$i][$j] = $columns[$j];
    }
}

$edgeTrees = (count($grid[0]) * 2) + ((count($grid) - 2) * 2);
$treesToCheck = $grid;
$visGrid = $grid;
$scenicGrid = $grid;

function isVisible(array $trees, int $row, int $column, array &$visGrid): bool
{
    $totalColumns = count($trees[0]);
    $totalRows = count($trees);

    $height = $trees[$row][$column];
    $rowsToCheckRange = range(0, $totalRows - 1);
    unset($rowsToCheckRange[$row]);
    $columnsToCheckRange = range(0, $totalColumns - 1);
    unset($columnsToCheckRange[$column]);

    $rowsAbove = range(0, $row - 1);
    $rowsBelow = range($row + 1, $totalRows - 1);

    $colsLeft = range(0, $column - 1);
    $colsRight = range($column + 1, $totalColumns - 1);

    $canBeSeenAbove = true;
    $canBeSeenBelow = true;
    $canBeSeenLeft = true;
    $canBeSeenRight = true;
    $left = 1;
    $right = 2;
    $top = 4;
    $below = 8;
    $visGrid[$row][$column] = 0;

    foreach ($rowsAbove as $rowCheck) {
        if($height <= $trees[$rowCheck][$column]) {
            $canBeSeenAbove = false;
        }

    }

    if($canBeSeenAbove) {
        $visGrid[$row][$column] |= $top;
    }

    foreach ($rowsBelow as $rowCheck) {
        if($height <= $trees[$rowCheck][$column]) {

            $canBeSeenBelow = false;
        }
    }

    if($canBeSeenBelow) {
        $visGrid[$row][$column] |= $below;
    }

    foreach ($colsLeft as $columnCheck) {
        if($height <= $trees[$row][$columnCheck]) {
            $canBeSeenLeft = false;
        }
    }

    if($canBeSeenLeft) {
        $visGrid[$row][$column] |= $left;
    }

    foreach ($colsRight as $columnCheck) {
        if($height <= $trees[$row][$columnCheck]) {
            $canBeSeenRight = false;
        }

    }
    if($canBeSeenRight) {
        $visGrid[$row][$column] |= $right;
    }



    return ($canBeSeenAbove || $canBeSeenBelow) || ($canBeSeenLeft || $canBeSeenRight);
}

function getScenicScore(array $trees, int $row, int $column, array &$scenicGrid): void
{
    $totalColumns = count($trees[0]);
    $totalRows = count($trees);
    // Above
    $above = range($row - 1, 0);
    $left = range($column - 1, 0);
    $below = range($row + 1, $totalRows - 1);
    $right = range($column + 1, $totalColumns - 1);

    $heightOfTree = $trees[$row][$column];
    $scenicGrid[$row][$column] =[
        'total' => 0,
        'left' => 0,
        'right' => 0,
        'top' => 0,
        'bottom' => 0,
    ];

    for($i = max($above); $i >= min($above); $i--) {
        $scenicGrid[$row][$column]['top'] += 1;
        if($heightOfTree <= $trees[$i][$column]) {
            break;
        }
    }

    for($i = min($below); $i <= max($below); $i++) {
        $scenicGrid[$row][$column]['bottom'] += 1;
        if($heightOfTree <= $trees[$i][$column]) {
            break;
        }
    }

//    if($column === 2) {
//        print_r($left);
//        die;
//    }
    for($i = max($left); $i >= min($left); $i--) {
        $scenicGrid[$row][$column]['left'] += 1;
        if($heightOfTree <= $trees[$row][$i]) {
            break;
        }
    }

    for($i = min($right); $i <= max($right); $i++) {
        $scenicGrid[$row][$column]['right'] += 1;
        if($heightOfTree <= $trees[$row][$i]) {
            break;
        }
    }

    $scenicGrid[$row][$column]['total'] =
        $scenicGrid[$row][$column]['top'] * $scenicGrid[$row][$column]['bottom'] *
        $scenicGrid[$row][$column]['left'] * $scenicGrid[$row][$column]['right'];

}

//remove first and last
array_pop($treesToCheck);
array_shift($treesToCheck);
array_pop($scenicGrid);
array_shift($scenicGrid);

$visibleTrees = 0;
for($i = 0; $i < count($treesToCheck); $i++) {
    array_pop($treesToCheck[$i]);
    array_shift($treesToCheck[$i]);
    array_pop($scenicGrid[$i]);
    array_shift($scenicGrid[$i]);

    for($j = 0; $j < count($treesToCheck[$i]); $j++) {
        if(isVisible($grid, $i + 1, $j + 1, $visGrid)) {
            ++$visibleTrees;
        }

        getScenicScore($grid, $i + 1, $j + 1, $scenicGrid);

    }
}

array_shift($scenicGrid);


echo "Part 1 : ".  $visibleTrees + $edgeTrees . PHP_EOL;

$bestScenicScore = max(array_map(function($row) {
    $row = array_map(fn($row) => $row['total'], $row);
    return max($row);
}, $scenicGrid));
echo "Part 2 : ".  $bestScenicScore . PHP_EOL;

