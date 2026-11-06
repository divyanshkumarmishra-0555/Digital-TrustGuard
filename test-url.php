<?php

require_once "api/url_analyzer.php";


$url =
    "https://www.example.com";



$result =
    analyzeURL($url);


echo "<h1>TrustGuard URL Analyzer Test</h1>";


echo "<h2>URL</h2>";

echo "<p>";

echo htmlspecialchars(
    $result["url"]
);

echo "</p>";


echo "<h2>Trust Score</h2>";

echo "<p>";

echo $result["trust_score"];

echo "/100";

echo "</p>";


echo "<h2>Risk Level</h2>";

echo "<p>";

echo htmlspecialchars(
    $result["risk_level"]
);

echo "</p>";


echo "<h2>Classification</h2>";

echo "<p>";

echo htmlspecialchars(
    $result["classification"]
);

echo "</p>";


echo "<h2>Evidence</h2>";

foreach (
    $result["evidence"]
    as $item
) {

    echo "<hr>";

    echo "<strong>";

    echo htmlspecialchars(
        $item["category"]
    );

    echo "</strong>";

    echo "<p>";

    echo htmlspecialchars(
        $item["explanation"]
    );

    echo "</p>";

}


echo "<h2>Recommendation</h2>";

echo "<p>";

echo htmlspecialchars(
    $result["recommendation"]
);

echo "</p>";

?>
