<?php
$html = file_get_contents('rendered_output2.html');

$voidElements = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

preg_match_all('/<\/?([a-zA-Z0-9]+)[^>]*>/', $html, $matches, PREG_OFFSET_CAPTURE);

$stack = [];
$errors = [];

foreach ($matches[0] as $i => $match) {
    $tagString = $match[0];
    $pos = $match[1];
    $tagName = strtolower($matches[1][$i][0]);
    
    // Ignore void elements
    if (in_array($tagName, $voidElements)) continue;
    
    // Ignore script/style content processing for simplicity here unless the tags themselves mismatch
    
    if (strpos($tagString, '</') === 0) {
        // Closing tag
        if (empty($stack)) {
            $errors[] = "Extra closing tag </$tagName> at pos $pos";
        } else {
            $last = array_pop($stack);
            if ($last['tag'] !== $tagName) {
                // If it's a mismatch, maybe the tag was unclosed or this is an extra closing tag.
                $errors[] = "Mismatch: Expected </{$last['tag']}> but found </$tagName> at pos $pos. Context: " . substr($html, max(0, $pos - 30), 60);
                
                // Try to recover: if we find the matching tag deeper in the stack, we pop down to it (meaning unclosed tags)
                $found = false;
                for ($j = count($stack) - 1; $j >= max(0, count($stack) - 5); $j--) {
                    if ($stack[$j]['tag'] === $tagName) {
                        $found = true;
                        // Pop off the unclosed ones
                        $stack = array_slice($stack, 0, $j);
                        break;
                    }
                }
                if (!$found) {
                    // It's likely an extra closing tag, so put the last back
                    $stack[] = $last;
                }
            }
        }
    } else if (substr($tagString, -2) !== '/>') {
        // Opening tag
        $stack[] = ['tag' => $tagName, 'pos' => $pos];
    }
}

if (!empty($errors)) {
    echo "Found HTML structure errors:\n";
    foreach (array_slice($errors, 0, 10) as $e) {
        echo "- $e\n";
    }
} else {
    echo "Basic tag matching is OK.\n";
}

if (!empty($stack)) {
    echo "\nUnclosed tags remaining:\n";
    foreach (array_slice($stack, -10) as $s) {
        echo "- <{$s['tag']}> at pos {$s['pos']}\n";
    }
}
