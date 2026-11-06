<?php
require_once __DIR__ . "/trust_engine.php";

/*
|--------------------------------------------------------------------------
| TrustGuard URL Analyzer
|--------------------------------------------------------------------------
|
| This is our first URL analysis engine.
|
| It does NOT claim that a website is definitely malicious.
| Instead, it detects suspicious characteristics.
|
|--------------------------------------------------------------------------
*/


function analyzeURL($url)
{

    /*
    |--------------------------------------------------------------------------
    | Clean URL
    |--------------------------------------------------------------------------
    */

    $url = trim($url);


    /*
    |--------------------------------------------------------------------------
    | Basic validation
    |--------------------------------------------------------------------------
    */

    if ($url === "") {

        return [

            "success" => false,

            "message" =>
                "No URL was provided."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Add https:// if user didn't provide a protocol
    |--------------------------------------------------------------------------
    */

    if (
        !preg_match(
            "/^https?:\/\//i",
            $url
        )
    ) {

        $url =
            "https://" . $url;

    }


    /*
    |--------------------------------------------------------------------------
    | Validate URL
    |--------------------------------------------------------------------------
    */

    if (!filter_var($url, FILTER_VALIDATE_URL)) {

        return [

            "success" => false,

            "message" =>
                "The URL format appears to be invalid."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Parse URL
    |--------------------------------------------------------------------------
    */

    $parts =
        parse_url($url);


    $host =
        $parts["host"] ?? "";


    $scheme =
        strtolower(
            $parts["scheme"] ?? ""
        );


    $path =
        $parts["path"] ?? "";


    $query =
        $parts["query"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | Prepare results
    |--------------------------------------------------------------------------
    */

    $riskPoints = 0;

    $evidence = [];


    /*
    |--------------------------------------------------------------------------
    | CHECK 1 — HTTPS
    |--------------------------------------------------------------------------
    */

    if ($scheme !== "https") {

        $riskPoints += 15;

        $evidence[] = [

            "category" =>
                "No HTTPS",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The URL does not use HTTPS. Information sent to the website may not receive the same transport protection provided by HTTPS."

        ];

    }
    else {

        $evidence[] = [

            "category" =>
                "HTTPS",

            "result" =>
                "Passed",

            "severity" =>
                "Low",

            "explanation" =>
                "The URL uses HTTPS."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 2 — IP ADDRESS
    |--------------------------------------------------------------------------
    */

    if (
        filter_var(
            $host,
            FILTER_VALIDATE_IP
        )
    ) {

        $riskPoints += 25;

        $evidence[] = [

            "category" =>
                "IP Address",

            "result" =>
                "Suspicious",

            "severity" =>
                "High",

            "explanation" =>
                "The website uses an IP address instead of a normal domain name."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 3 — URL LENGTH
    |--------------------------------------------------------------------------
    */

    $urlLength =
        strlen($url);


    if ($urlLength > 150) {

        $riskPoints += 15;

        $evidence[] = [

            "category" =>
                "Long URL",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The URL is unusually long, which can sometimes be used to hide suspicious destinations or tracking parameters."

        ];

    }
    elseif ($urlLength > 100) {

        $riskPoints += 8;

        $evidence[] = [

            "category" =>
                "Long URL",

            "result" =>
                "Notice",

            "severity" =>
                "Low",

            "explanation" =>
                "The URL is relatively long and should be inspected carefully."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 4 — @ SYMBOL
    |--------------------------------------------------------------------------
    */

    if (strpos($url, "@") !== false) {

        $riskPoints += 20;

        $evidence[] = [

            "category" =>
                "Suspicious @ Symbol",

            "result" =>
                "Warning",

            "severity" =>
                "High",

            "explanation" =>
                "The URL contains an @ symbol. This can sometimes be abused to make a URL appear to point to a trusted website."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 5 — MANY SUBDOMAINS
    |--------------------------------------------------------------------------
    */

    $hostParts =
        explode(
            ".",
            $host
        );


    if (count($hostParts) >= 4) {

        $riskPoints += 10;

        $evidence[] = [

            "category" =>
                "Multiple Subdomains",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The domain contains multiple subdomain levels. This is not automatically malicious, but it deserves additional verification."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 6 — SUSPICIOUS KEYWORDS
    |--------------------------------------------------------------------------
    */

    $suspiciousKeywords = [

        "login",
        "verify",
        "verification",
        "secure",
        "account",
        "update",
        "password",
        "confirm",
        "wallet",
        "payment",
        "bank",
        "signin",
        "security"

    ];


    $matchedKeywords = [];


    $lowerURL =
        strtolower($url);


    foreach (
        $suspiciousKeywords
        as $keyword
    ) {

        if (
            strpos(
                $lowerURL,
                $keyword
            ) !== false
        ) {

            $matchedKeywords[] =
                $keyword;

        }

    }


    if (
        count($matchedKeywords) >= 2
    ) {

        $riskPoints += 15;

        $evidence[] = [

            "category" =>
                "Suspicious Keywords",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The URL contains several security or account-related keywords: "
                . implode(
                    ", ",
                    $matchedKeywords
                )

        ];

    }
    elseif (
        count($matchedKeywords) === 1
    ) {

        $riskPoints += 5;

        $evidence[] = [

            "category" =>
                "Security Keyword",

            "result" =>
                "Notice",

            "severity" =>
                "Low",

            "explanation" =>
                "The URL contains the keyword: "
                . $matchedKeywords[0]

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 7 — URL ENCODING
    |--------------------------------------------------------------------------
    */

    if (
        substr_count(
            $url,
            "%"
        ) >= 4
    ) {

        $riskPoints += 10;

        $evidence[] = [

            "category" =>
                "Encoded URL",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The URL contains a large amount of encoded content."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 8 — URL SHORTENERS
    |--------------------------------------------------------------------------
    */

    $shorteners = [

        "bit.ly",
        "tinyurl.com",
        "t.co",
        "goo.gl",
        "is.gd",
        "cutt.ly",
        "shorturl.at"

    ];


    foreach (
        $shorteners
        as $shortener
    ) {

        if (
            strtolower($host) ===
            $shortener
        ) {

            $riskPoints += 15;

            $evidence[] = [

                "category" =>
                    "URL Shortener",

                "result" =>
                    "Warning",

                "severity" =>
                    "Medium",

                "explanation" =>
                    "The URL uses a shortening service, which can hide the final destination."

            ];

            break;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK 9 — REPEATED SPECIAL CHARACTERS
    |--------------------------------------------------------------------------
    */

    if (
        preg_match(
            "/[-_]{4,}/",
            $host
        )
    ) {

        $riskPoints += 10;

        $evidence[] = [

            "category" =>
                "Unusual Domain Structure",

            "result" =>
                "Warning",

            "severity" =>
                "Medium",

            "explanation" =>
                "The domain contains an unusual sequence of repeated separators."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | LIMIT RISK
    |--------------------------------------------------------------------------
    */

    if ($riskPoints > 100) {

        $riskPoints = 100;

    }


    /*
    |--------------------------------------------------------------------------
    | TRUST SCORE
    |--------------------------------------------------------------------------
    */

    $urlSignalScore =
    100 - $riskPoints;
/*
|--------------------------------------------------------------------------
| Trust Engine
|--------------------------------------------------------------------------
|
| The URL analyzer produces a URL-specific score.
| The Trust Engine currently uses that signal to
| calculate the overall Trust Score.
|
|--------------------------------------------------------------------------
*/

$signals = [

    "url" =>
        $urlSignalScore

];


$trustScore =
    calculateTrustScore(
        $signals
    );



    /*
    |--------------------------------------------------------------------------
    | RISK LEVEL
    |--------------------------------------------------------------------------
    */

    $riskLevel =
    getRiskLevel(
        $trustScore
    );



    /*
    |--------------------------------------------------------------------------
    | CLASSIFICATION
    |--------------------------------------------------------------------------
    */

    $classification =
    getTrustClassification(
        $trustScore
    );



    /*
    |--------------------------------------------------------------------------
    | Recommendation
    |--------------------------------------------------------------------------
    */

    if ($trustScore <= 40) {

        $recommendation =
            "Do not enter passwords, OTPs, payment information, or other sensitive data until the website has been independently verified.";

    }
    elseif ($trustScore <= 70) {

        $recommendation =
            "Proceed carefully and verify the website using an independent trusted source before providing sensitive information.";

    }
    else {

        $recommendation =
            "The URL does not show major warning signs in this basic analysis. Continue using normal security precautions.";

    }


    /*
    |--------------------------------------------------------------------------
    | Return result
    |--------------------------------------------------------------------------
    */

    return [

        "success" =>
            true,

        "url" =>
            $url,

        "domain" =>
            $host,

        "trust_score" =>
            $trustScore,

        "risk_level" =>
            $riskLevel,

        "classification" =>
            $classification,

        "recommendation" =>
            $recommendation,

        "evidence" =>
            $evidence

    ];

}


/*
|--------------------------------------------------------------------------
| Direct URL Analysis API
|--------------------------------------------------------------------------
*/

if (
    basename($_SERVER["SCRIPT_FILENAME"])
    ===
    basename(__FILE__)
) {

    header("Content-Type: application/json");


    if ($_SERVER["REQUEST_METHOD"] !== "POST") {

        echo json_encode([

            "success" => false,

            "message" =>
                "Only POST requests are allowed."

        ]);

        exit;

    }


    $input =
        json_decode(
            file_get_contents("php://input"),
            true
        );


    if (!$input) {

        echo json_encode([

            "success" => false,

            "message" =>
                "Invalid request data."

        ]);

        exit;

    }


    $url =
        trim(
            $input["url"] ?? ""
        );


    $result =
        analyzeURL($url);


    echo json_encode(
        $result
    );

}

?>

