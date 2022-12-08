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

//remove first and last
array_pop($treesToCheck);
array_shift($treesToCheck);
$visibleTrees = 0;
for($i = 0; $i < count($treesToCheck); $i++) {
    array_pop($treesToCheck[$i]);
    array_shift($treesToCheck[$i]);

    for($j = 0; $j < count($treesToCheck[$i]); $j++) {
        if(isVisible($grid, $i + 1, $j + 1, $visGrid)) {
            ++$visibleTrees;
        }
    }
}

print_r($visGrid);

echo "Part 1 : ".  $visibleTrees + $edgeTrees . PHP_EOL;
//echo "Part 1 : ".  $visibleTrees . PHP_EOL;
