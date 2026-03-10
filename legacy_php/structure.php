<?php
echo "<h1>Searching for '/PhpFinalWebTemplate/' in project files</h1>";

// Function to search files recursively
function searchFiles($directory, $pattern) {
    $found = [];
    $files = scandir($directory);
    
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        
        $path = $directory . '/' . $file;
        
        if (is_dir($path)) {
            // Search subdirectories
            $found = array_merge($found, searchFiles($path, $pattern));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            // Search only PHP files
            $content = file_get_contents($path);
            if (strpos($content, $pattern) !== false) {
                $found[] = [
                    'file' => $path,
                    'count' => substr_count($content, $pattern),
                    'lines' => getLinesWithPattern($content, $pattern)
                ];
            }
        }
    }
    
    return $found;
}

function getLinesWithPattern($content, $pattern) {
    $lines = explode("\n", $content);
    $matches = [];
    
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $pattern) !== false) {
            $matches[] = [
                'line' => $lineNumber + 1,
                'text' => htmlspecialchars(trim($line))
            ];
        }
    }
    
    return $matches;
}

// Start search from current directory
$searchResults = searchFiles(__DIR__, '/PhpFinalWebTemplate');

echo "<h2>Found in " . count($searchResults) . " files:</h2>";

foreach ($searchResults as $result) {
    $relativePath = str_replace(__DIR__, '.', $result['file']);
    echo "<h3 style='color:red;'>$relativePath ({$result['count']} occurrences)</h3>";
    
    foreach ($result['lines'] as $match) {
        echo "Line {$match['line']}: <code>{$match['text']}</code><br>";
    }
    echo "<hr>";
}

// Also check HTML/CSS/JS files
echo "<h2>Checking other file types...</h2>";

$extensions = ['html', 'htm', 'css', 'js'];
foreach ($extensions as $ext) {
    echo "<h3>Searching .$ext files:</h3>";
    
    $found = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__)
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === $ext) {
            $content = file_get_contents($file->getPathname());
            if (strpos($content, '/PhpFinalWebTemplate') !== false) {
                $relativePath = str_replace(__DIR__, '.', $file->getPathname());
                $found[] = $relativePath;
            }
        }
    }
    
    if (count($found) > 0) {
        echo "Found in: " . implode('<br>', $found);
    } else {
        echo "No matches found.";
    }
    echo "<hr>";
}
?>