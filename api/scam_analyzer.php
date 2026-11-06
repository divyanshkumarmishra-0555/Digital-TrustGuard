<?php

/*
|--------------------------------------------------------------------------
| Digital TrustGuard
| Scam / Phishing Analyzer
|--------------------------------------------------------------------------
|
| This analyzer looks for common social-engineering
| and scam patterns in text.
|
| It is intentionally rule-based for the first version.
| Later, Python NLP/ML can be added here.
|
|--------------------------------------------------------------------------
*/


function analyzeScamText($text)
{

    /*
    |--------------------------------------------------------------------------
    | Normalize text
    |--------------------------------------------------------------------------
    */

    $text =
        strtolower(
            trim($text)
        );


    /*
    |--------------------------------------------------------------------------
    | Evidence storage
    |--------------------------------------------------------------------------
    */

    $evidence = [];


    /*
    |--------------------------------------------------------------------------
    | Risk points
    |--------------------------------------------------------------------------
    */

    $riskPoints = 0;


    /*
    |--------------------------------------------------------------------------
    | URGENCY DETECTION
    |--------------------------------------------------------------------------
    */

    $urgencyPatterns = [

        "urgent",
        "immediately",
        "act now",
        "verify now",
        "respond now",
        "within 24 hours",
        "today only",
        "last warning",
        "final warning",
        "expires today",
        "do not delay"

    ];


    $urgencyFound = false;


    foreach (
        $urgencyPatterns
        as $pattern
    ) {

        if (
            strpos(
                $text,
                $pattern
            ) !== false
        ) {

            $urgencyFound = true;

            break;

        }

    }


    if ($urgencyFound) {

        $riskPoints += 15;


        $evidence[] = [

            "category" =>
                "Urgency",

            "severity" =>
                "Medium",

            "explanation" =>
                "The message uses urgent or time-pressure language designed to encourage immediate action."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | THREAT DETECTION
    |--------------------------------------------------------------------------
    */

    $threatPatterns = [

        "account will be blocked",
        "account will be suspended",
        "account has been suspended",
        "legal action",
        "police action",
        "your account will be closed",
        "you will be arrested",
        "security alert",
        "unauthorized activity",
        "your access will be revoked"

    ];


    $threatFound = false;


    foreach (
        $threatPatterns
        as $pattern
    ) {

        if (
            strpos(
                $text,
                $pattern
            ) !== false
        ) {

            $threatFound = true;

            break;

        }

    }


    if ($threatFound) {

        $riskPoints += 20;


        $evidence[] = [

            "category" =>
                "Threatening Language",

            "severity" =>
                "High",

            "explanation" =>
                "The message threatens account loss, legal consequences, suspension or other negative consequences."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | OTP / PASSWORD DETECTION
    |--------------------------------------------------------------------------
    */

    $credentialPatterns = [

        "otp",
        "one time password",
        "one-time password",
        "password",
        "pin",
        "passcode",
        "verification code",
        "security code"

    ];


    $credentialFound = false;


    foreach (
        $credentialPatterns
        as $pattern
    ) {

        if (
            strpos(
                $text,
                $pattern
            ) !== false
        ) {

            $credentialFound = true;

            break;

        }

    }


    if ($credentialFound) {

        $riskPoints += 25;


        $evidence[] = [

            "category" =>
                "Credential Request",

            "severity" =>
                "High",

            "explanation" =>
                "The message refers to sensitive credentials such as OTPs, passwords, PINs or verification codes."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | PAYMENT DETECTION
    |--------------------------------------------------------------------------
    */

    $paymentPatterns = [

        "send money",
        "transfer money",
        "make a payment",
        "pay now",
        "payment required",
        "credit card",
        "debit card",
        "bank transfer",
        "upi",
        "refund fee",
        "processing fee",
        "payment link"

    ];


    $paymentFound = false;


    foreach (
        $paymentPatterns
        as $pattern
    ) {

        if (
            strpos(
                $text,
                $pattern
            ) !== false
        ) {

            $paymentFound = true;

            break;

        }

    }


    if ($paymentFound) {

        $riskPoints += 20;


        $evidence[] = [

            "category" =>
                "Payment Request",

            "severity" =>
                "High",

            "explanation" =>
                "The message requests or discusses financial transactions, payment information or money transfers."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | FINANCIAL / BRAND IMPERSONATION DETECTION
    |--------------------------------------------------------------------------
    */

    $financialPatterns = [

        "bank",
        "banking",
        "credit card",
        "debit card",
        "tax department",
        "income tax",
        "government",
        "courier",
        "delivery",
        "paypal",
        "google",
        "microsoft",
        "apple",
        "amazon"

    ];


    $financialFound = false;


    foreach (
        $financialPatterns
        as $pattern
    ) {

        if (
            strpos(
                $text,
                $pattern
            ) !== false
        ) {

            $financialFound = true;

            break;

        }

    }


    if ($financialFound) {

        $riskPoints += 10;


        $evidence[] = [

            "category" =>
                "Possible Impersonation",

            "severity" =>
                "Medium",

            "explanation" =>
                "The message references a recognizable organization, financial service or authority."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | SUSPICIOUS LINK DETECTION
    |--------------------------------------------------------------------------
    */

    $urlPattern =
        "/https?:\/\/[^\s]+/i";


    if (
        preg_match(
            $urlPattern,
            $text
        )
    ) {

        $riskPoints += 15;


        $evidence[] = [

            "category" =>
                "Suspicious Link",

            "severity" =>
                "Medium",

            "explanation" =>
                "The message contains a clickable website URL. Verify the destination before opening it."

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | CAP RISK SCORE
    |--------------------------------------------------------------------------
    */

    if (
        $riskPoints > 100
    ) {

        $riskPoints = 100;

    }


    /*
    |--------------------------------------------------------------------------
    | Convert risk into trust score
    |--------------------------------------------------------------------------
    */

    $trustScore =
        100 - $riskPoints;


    /*
    |--------------------------------------------------------------------------
    | Risk level
    |--------------------------------------------------------------------------
    */

    if (
        $trustScore <= 20
    ) {

        $riskLevel =
            "CRITICAL RISK";

    }

    elseif (
        $trustScore <= 40
    ) {

        $riskLevel =
            "HIGH RISK";

    }

    elseif (
        $trustScore <= 70
    ) {

        $riskLevel =
            "MEDIUM RISK";

    }

    else {

        $riskLevel =
            "LOW RISK";

    }


    /*
    |--------------------------------------------------------------------------
    | Classification
    |--------------------------------------------------------------------------
    */

    if (
        $riskPoints >= 60
    ) {

        $classification =
            "Likely Scam / Phishing";

    }

    elseif (
        $riskPoints >= 30
    ) {

        $classification =
            "Potential Scam";

    }

    elseif (
        $riskPoints > 0
    ) {

        $classification =
            "Requires Verification";

    }

    else {

        $classification =
            "No Major Scam Indicators";

    }


    /*
    |--------------------------------------------------------------------------
    | Recommendation
    |--------------------------------------------------------------------------
    */

    if (
        $riskPoints >= 60
    ) {

        $recommendation =
            "Do not click links, share OTPs or passwords, or send money. Verify the sender through an official channel.";

    }

    elseif (
        $riskPoints >= 30
    ) {

        $recommendation =
            "Be cautious. Independently verify the sender and organization before taking any action.";

    }

    else {

        $recommendation =
            "No major scam indicators were detected by this basic analysis. Continue using normal security precautions.";

    }


    /*
    |--------------------------------------------------------------------------
    | Return result
    |--------------------------------------------------------------------------
    */
/*
|--------------------------------------------------------------------------
| Add database result field
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Add database result field
|--------------------------------------------------------------------------
*/

foreach ($evidence as &$item) {

    $severity =
        strtolower(
            $item["severity"] ?? "low"
        );


    if ($severity === "high") {

        $item["result"] =
            "Warning";

    }

    elseif ($severity === "medium") {

        $item["result"] =
            "Notice";

    }

    else {

        $item["result"] =
            "Passed";

    }

}

unset($item);



/*
|--------------------------------------------------------------------------
| Return result
|--------------------------------------------------------------------------
*/

return [

    "success" =>
        true,

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

    return [

        "success" =>
            true,

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
function extractURLs($text)
{
    $pattern = '/https?:\/\/[^\s]+/i';

    preg_match_all(
        $pattern,
        $text,
        $matches
    );

    return $matches[0] ?? [];
}

?>
