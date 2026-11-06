<?php

require_once "api/scam_analyzer.php";


$text =
    "Your bank account will be blocked today. " .
    "Verify immediately using this link " .
    "https://example.com and send your OTP.";


$result =
    analyzeScamText(
        $text
    );


echo "<h1>TrustGuard Scam Analyzer Test</h1>";


echo "<h2>Input</h2>";

echo "<p>";

echo htmlspecialchars(
    $text
);

echo "</p>";


echo "<h2>Trust Score</h2>";

echo "<p>";

echo $result["trust_score"];

echo "/100";

echo "</p>";


echo "<h2>Risk Level</h2>";

echo "<p>";

echo $result["risk_level"];

echo "</p>";


echo "<h2>Classification</h2>";

echo "<p>";

echo $result["classification"];

echo "</p>";


echo "<h2>Recommendation</h2>";

echo "<p>";

echo $result["recommendation"];

echo "</p>";


echo "<h2>Evidence</h2>";


echo "<pre>";

print_r(
    $result["evidence"]
);

echo "</pre>";

?>
