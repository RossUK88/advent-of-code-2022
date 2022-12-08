<?php
$testing = false;
$items = array_filter(explode(PHP_EOL, file_get_contents($testing ? './test_input.txt' : './input.txt')), fn($item) => $item !== '');

class Node {
    public function __construct(public array $children, public string $name, public ?Node $parent = null, public ?int $size = null)
    {
    }

    public function addDir(string $name): self
    {
        $found = null;
        foreach($this->children as $node) {
            if($node->name === $name) {
                $found = $node;
                break;
            }
        }

        if(is_null($found)) {
            $this->children[] = new Node([], $name, $this, null);
        }

        return $this;
    }

    public function addFile(string $name, int $size): self
    {
        $found = null;
        foreach($this->children as $node) {
            if($node->name === $name) {
                $found = $node;
                break;
            }
        }

        if(is_null($found)) {
            $this->children[] = new Node([], $name, $this, $size);
        }

        return $this;
    }

    public function isDir(): bool
    {
        return is_null($this->size);
    }
    public function isFile(): bool
    {
        return !is_null($this->size);
    }

    public function calculateSize(): int
    {
        $runningTotal = 0;
        foreach($this->children as $child) {
            if($child->isFile()) {
                $runningTotal += $child->size;
            } else {
                // recurssively call this function
                $runningTotal += $child->calculateSize();
            }
        }
        return $runningTotal;
    }

    public function getDirectoriesOver(int $lowerLimit): array
    {
        $dirs = [];
        foreach($this->children as $node) {
            $size = $node->calculateSize();

            if($node->isDir()) {
                if ($size >= $lowerLimit) {
                    $dirs[] = $size;
                }
                $subDirs = $node->getDirectoriesOver($lowerLimit);
                foreach ($subDirs as $dir) {
                    $dirs[] = $dir;
                }
            }
        }

        return $dirs;
    }

    public function totalSizeOfDirectoriesUnder(int $upperLimit): int
    {
        $total = 0;
        foreach($this->children as $node) {
            $size = $node->calculateSize();

            if($node->isDir()) {
                if ($size <= $upperLimit) {
                    $total += $size;
                }
                $total += $node->totalSizeOfDirectoriesUnder($upperLimit);
            }
        }

        return $total;
    }
}
class Tree {
    public function __construct(public Node $root)
    {
    }

    public function calculateSize(): int
    {
        $runningTotal = 0;

        foreach($this->root->children as $node) {
            if ($node->isDir()) {
                $runningTotal += $node->calculateSize();
            } else {
                $runningTotal += $node->size;
            }
        }

        return $runningTotal;
    }

    public function getDirectoriesOver(int $lowerLimit): array
    {
        $dirs = [];
        foreach($this->root->children as $node) {
            $size = $node->calculateSize();

            if($node->isDir()) {
                if($size >= $lowerLimit) {
                    $dirs[] = $node;
                }

                $subDirs = $node->getDirectoriesOver($lowerLimit);
                foreach ($subDirs as $dir) {
                    $dirs[] = $dir;
                }
            }
        }

        return $dirs;
    }

    public function totalSizeOfDirectoriesUnder(int $upperLimit): int
    {
        $total = 0;
        foreach($this->root->children as $node) {
            $size = $node->calculateSize();

            if($node->isDir()) {
                if($size <= $upperLimit) {
                    $total += $size;
                }
                $total += $node->totalSizeOfDirectoriesUnder($upperLimit);
            }
        }

        return $total;
    }

}

$tree = new Tree(new Node([], "/"));
$cwd = [];
$currentNode = $tree->root;

foreach ($items as $item) {
    if(str_starts_with($item, "$ ")) {
        $command = substr($item, 2);

        if(str_starts_with($command, "cd ")) {
            $directory = substr($command, 3);
            if(str_starts_with($directory, "/")) {
                $cwd = [];
                $currentNode = $tree->root;
            } else if (str_starts_with($directory, "..")) {
                // Already at root
                if(!is_null($currentNode)) {
                    //  Go back a directory
                    array_pop($cwd);
                    $currentNode = $currentNode->parent;
                } else {
                    $currentNode = $tree->root;
                }
            } else {
                $cwd[] = $directory;
                $found = null;
                foreach($currentNode->children as $node) {
                    if($node->name === $directory) {
                        $found = $node;
                        break;
                    }
                }

                if(is_null($found)) {
                    $currentNode->children[] = new Node([], $directory, $currentNode, null);
                    $currentNode = $currentNode->children[count($currentNode->children) - 1];
                } else {
                    $currentNode = $found;
                }

            }
        }
    } else {
        if(str_starts_with($item, "dir ")) {
            // This is a directory
            continue;
        }

        $file = explode(" ", $item);
        $currentNode->addFile($file[1], $file[0]);
    }
}

print "Part 1: " . $tree->totalSizeOfDirectoriesUnder(100_000) . PHP_EOL;

$totalSpace = 70_000_000;
$spaceNeeded = 30_000_000;
$sizeOfTree = $tree->calculateSize();
$unusedSpace = $totalSpace - $sizeOfTree;
$spaceToClear = $spaceNeeded - $unusedSpace;

// This doesn't include the base directory but you should never sudo rm -rf --no-preserve-root
$dirsToDelete = $tree->getDirectoriesOver($spaceToClear);


$dirsToDelete = array_map(function($nodes) {
    // Fuck Knows?!?
    if(is_int($nodes)) {
        return $nodes;
    }
    return $nodes->calculateSize();
}, $dirsToDelete);

print "Part 2: " . min($dirsToDelete) .PHP_EOL;
