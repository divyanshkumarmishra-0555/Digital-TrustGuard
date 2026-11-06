<?php

/*
|--------------------------------------------------------------------------
| Digital TrustGuard
| Trust Engine
|--------------------------------------------------------------------------
|
| This engine combines different analysis signals into
| one overall Trust Score.
|
| Current analyzers:
|
| 1. URL analysis
| 2. Text/scam analysis
|
| Future analyzers:
|
| 3. Fact checking
| 4. Image analysis
| 5. Video analysis
| 6. QR analysis
| 7. Source reputation
|
|--------------------------------------------------------------------------
*/


function calculateTrustScore($signals)
{

    /*
    |--------------------------------------------------------------------------
    | Default weights
    |--------------------------------------------------------------------------
    |
    | The weights determine how much each analyzer contributes
    | to the final Trust Score.
    |
    */

    $weights = [

        "url" =>
            0.40,

        "scam" =>
            0.30,

        "source" =>
            0.15,

        "claims" =>
            0.10,

        "media" =>
            0.05

    ];


    /*
    |--------------------------------------------------------------------------
    | Score calculation
    |--------------------------------------------------------------------------
    */

    $totalScore = 0;

    $totalWeight = 0;


    foreach (
        $weights
        as $type => $weight
    ) {

        /*
        --------------------------------------------------
        Check whether this signal exists
        --------------------------------------------------
        */

        if (
            isset(
                $signals[$type]
            )
        ) {

            $score =
                (float)
                $signals[$type];


            /*
            --------------------------------------------------
            Make sure score is between 0 and 100
            --------------------------------------------------
            */

            if ($score < 0) {

                $score = 0;

            }


            if ($score > 100) {

                $score = 100;

            }


            /*
            --------------------------------------------------
            Weighted score
            --------------------------------------------------
            */

            $totalScore +=
                $score * $weight;


            $totalWeight +=
                $weight;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Avoid division by zero
    |--------------------------------------------------------------------------
    */

    if (
        $totalWeight === 0
    ) {

        return 50;

    }


    /*
    |--------------------------------------------------------------------------
    | Final score
    |--------------------------------------------------------------------------
    */

    $finalScore =
        $totalScore /
        $totalWeight;


    /*
    |--------------------------------------------------------------------------
    | Round score
    |--------------------------------------------------------------------------
    */

    $finalScore =
        round(
            $finalScore
        );


    /*
    |--------------------------------------------------------------------------
    | Return final Trust Score
    |--------------------------------------------------------------------------
    */

    return $finalScore;

}


/*
|--------------------------------------------------------------------------
| Determine risk level
|--------------------------------------------------------------------------
*/

function getRiskLevel($score)
{

    if ($score <= 20) {

        return "CRITICAL RISK";

    }


    if ($score <= 40) {

        return "HIGH RISK";

    }


    if ($score <= 70) {

        return "MEDIUM RISK";

    }


    return "LOW RISK";

}


/*
|--------------------------------------------------------------------------
| Determine classification
|--------------------------------------------------------------------------
*/

function getTrustClassification($score)
{

    if ($score <= 20) {

        return "Highly Suspicious";

    }


    if ($score <= 40) {

        return "Potentially Dangerous";

    }


    if ($score <= 70) {

        return "Requires Verification";

    }


    return "No Major Warning Signs";

}
/*
|--------------------------------------------------------------------------
| Get Active Trust Signals
|--------------------------------------------------------------------------
|
| Returns the analyzer signals that were actually available
| for the current analysis.
|
|--------------------------------------------------------------------------
*/

function getActiveSignals($signals)
{

    $activeSignals = [];


    foreach ($signals as $type => $score) {

        if ($score === null) {

            continue;

        }


        $activeSignals[] = $type;

    }


    return $activeSignals;

}

?>
