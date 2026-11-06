<?php

require_once "api/scam_analyzer.php";


$text =
    "Your account needs verification. " .
    "Visit https://example.com/login immediately.";


$urls =
    extractURLs(
        $text
    );


echo "<h1>URL Extractor Test</h1>";


echo "<h2>Original Text</h2>";

echo "<p>";

echo htmlspecialchars(
    $text
);

echo "</p>";


echo "<h2>Extracted URLs</h2>";


echo "<pre>";

print_r(
    $urls
);

echo "</pre>";

?>
