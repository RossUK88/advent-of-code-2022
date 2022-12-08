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

    public function calculateSizeOfDir(string $dirPath): int
    {
        $runningTotal = 0;
        $directoryArray = explode("/", $dirPath);

        foreach($this->root->children as $node) {
            if ($node->isDir() && $node->name === $directoryArray[0]) {
                $runningTotal += $node->calculateSize();
                break;
            }
        }

        return $runningTotal;
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
//
//$tree->root->addDir("a");
//$tree->root->children[0]->addFile("b.txt", 14848514);
//$tree->root->children[0]->addFile("c.dat", 8504156);
//$tree->root->addDir("b");
//$tree->root->children[1]->addFile("b.dicks", 250);
//$tree->root->children[1]->addDir("d");
//$tree->root->children[1]->children[1]->addFile("chris.txt", 250);
//$tree->root->addDir("c");
//$tree->root->children[2]->addFile("chris.txt", 200);
//
//
//print $tree->totalSizeOfDirectoriesUnder(250) . PHP_EOL;


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

print $tree->totalSizeOfDirectoriesUnder(100_000) . PHP_EOL;
