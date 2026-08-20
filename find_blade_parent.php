<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views', RecursiveDirectoryIterator::SKIP_DOTS);
$iterator = new RecursiveIteratorIterator($dir);

foreach ($iterator as $file) {
    if (strpos($file->getFilename(), '.blade.php') !== false) {
        $lines = file($file->getPathname());
        foreach ($lines as $num => $line) {
            if (strpos($line, 'parent_id') !== false || strpos($line, 'children') !== false) {
                echo str_replace(__DIR__, '', $file->getPathname()) . " [Line " . ($num + 1) . "]: " . trim($line) . "\n";
            }
        }
    }
}
