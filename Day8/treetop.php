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

function isVisible(array $trees, int $row, int $column): bool
{
    $totalColumns = count($trees[0]);
    $totalRows = count($trees);

    $height = $trees[$row][$column];
    $rowsToCheckRange = range(0, $totalRows - 1);
    unset($rowsToCheckRange[$row]);
    $columnsToCheckRange = range(0, $totalColumns - 1);
    unset($columnsToCheckRange[$column]);

    foreach ($rowsToCheckRange as $rowCheck) {
        if($trees[$rowCheck][$column] > $height) {
            return true;
        }

        if($rowCheck === $row) {
            foreach ($columnsToCheckRange as $columnCheck) {
                if($trees[$row][$columnCheck] > $height) {
                    return true;
                }
            }
        }
    }

    return false;
}

//remove first and last
array_pop($treesToCheck);
array_shift($treesToCheck);
$visibleTrees = 0;
for($i = 0; $i < count($treesToCheck); $i++) {
    array_pop($treesToCheck[$i]);
    array_shift($treesToCheck[$i]);

    for($j = 0; $j < count($treesToCheck[$i]); $j++) {
        if(isVisible($grid, $i + 1, $j + 1) ) {
            ++$visibleTrees;
        }
    }
}

echo "Part 1 : ".  $visibleTrees + $edgeTrees . PHP_EOL;
