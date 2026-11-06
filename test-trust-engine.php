<?php

require_once "api/trust_engine.php";


/*
|--------------------------------------------------------------------------
| Test signals
|--------------------------------------------------------------------------
*/

$signals = [

    "url" =>
        30,

    "scam" =>
        20,

    "source" =>
        80,

    "claims" =>
        40,

    "media" =>
        90

];


/*
|--------------------------------------------------------------------------
| Calculate score
|--------------------------------------------------------------------------
*/

$score =
    calculateTrustScore(
        $signals
    );


/*
|--------------------------------------------------------------------------
| Get risk level
|--------------------------------------------------------------------------
*/

$risk =
    getRiskLevel(
        $score
    );


/*
|--------------------------------------------------------------------------
| Get classification
|--------------------------------------------------------------------------
*/

$classification =
    getTrustClassification(
        $score
    );


/*
|--------------------------------------------------------------------------
| Display result
|--------------------------------------------------------------------------
*/

echo "<h1>TrustGuard Trust Engine Test</h1>";


echo "<h2>Signals</h2>";


echo "<pre>";

print_r(
    $signals
);

echo "</pre>";


echo "<h2>Trust Score</h2>";

echo "<p>";

echo $score;

echo "/100";

echo "</p>";


echo "<h2>Risk Level</h2>";

echo "<p>";

echo $risk;

echo "</p>";


echo "<h2>Classification</h2>";

echo "<p>";

echo $classification;

echo "</p>";

?>
