<?php

header("Content-Type: application/json");

require_once "../config/database.php";

require_once __DIR__ . "/scam_analyzer.php";
require_once __DIR__ . "/trust_engine.php";
require_once __DIR__ . "/url_analyzer.php";


/*
|--------------------------------------------------------------------------
| 1. Only allow POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([
        "success" => false,
        "message" => "Only POST requests are allowed."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| 2. Read request data
|--------------------------------------------------------------------------
*/

$inputType = "";
$inputContent = "";


/*
|--------------------------------------------------------------------------
| Image / QR upload
|--------------------------------------------------------------------------
*/

if (
    isset($_POST["input_type"]) &&
    isset($_FILES["file"])
) {

    $inputType =
        $_POST["input_type"];

    $uploadedFile =
        $_FILES["file"];


    /*
    |--------------------------------------------------------------------------
    | Check upload error
    |--------------------------------------------------------------------------
    */

    if (
        $uploadedFile["error"] !== UPLOAD_ERR_OK
    ) {

        echo json_encode([

            "success" => false,

            "message" =>
                "Unable to upload the selected file."

        ]);

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | Get original filename
    |--------------------------------------------------------------------------
    */

    $inputContent =
    $uploadedFile["name"];

/*
|--------------------------------------------------------------------------
| Image analysis
|--------------------------------------------------------------------------
*/

if ($inputType === "image") {

    $imageInfo =
        getimagesize(
            $uploadedFile["tmp_name"]
        );


    if ($imageInfo === false) {

        echo json_encode([

            "success" => false,

            "message" =>
                "The uploaded file is not a valid image."

        ]);

        exit;

    }


    $imageWidth =
        $imageInfo[0];

    $imageHeight =
        $imageInfo[1];

    $imageMime =
        $imageInfo["mime"];

    $imageSize =
        $uploadedFile["size"];


    /*
    |--------------------------------------------------------------------------
    | Basic image trust score
    |--------------------------------------------------------------------------
    */

    $imageScore = 100;

    $imageEvidence = [];

/*
|--------------------------------------------------------------------------
| Basic image risk signals
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Check file size
|--------------------------------------------------------------------------
*/

if ($imageSize < 5000) {

    $imageScore -= 10;

    $imageEvidence[] = [

        "category" =>
            "File Size",

        "result" =>
            "Very small image file",

        "explanation" =>
            "The uploaded image is unusually small in file size.",

        "severity" =>
            "low"

    ];

}


/*
|--------------------------------------------------------------------------
| Check very large images
|--------------------------------------------------------------------------
*/

if ($imageSize > 10000000) {

    $imageScore -= 5;

    $imageEvidence[] = [

        "category" =>
            "File Size",

        "result" =>
            "Large image file",

        "explanation" =>
            "The uploaded image is larger than 10 MB.",

        "severity" =>
            "low"

    ];

}
    /*
    |--------------------------------------------------------------------------
    | Check image dimensions
    |--------------------------------------------------------------------------
    */

    if (
        $imageWidth < 200 ||
        $imageHeight < 200
    ) {

        $imageScore -= 10;

        $imageEvidence[] = [

            "category" =>
                "Image Quality",

            "result" =>
                "Small image dimensions",

            "explanation" =>
                "The uploaded image has relatively small dimensions.",

            "severity" =>
                "low"

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Image information evidence
    |--------------------------------------------------------------------------
    */

    $imageEvidence[] = [

    "category" =>
        "Image Analysis",

    "result" =>
        "Image successfully inspected",

    "explanation" =>
        "TrustGuard detected a valid " .
        $imageMime .
        " image measuring " .
        $imageWidth .
        " × " .
        $imageHeight .
        " pixels, with a file size of " .
        round($imageSize / 1024, 2) .
        " KB.",

    "severity" =>
        "info"

];


    /*
    |--------------------------------------------------------------------------
    | Keep score inside 0-100
    |--------------------------------------------------------------------------
    */

    $imageScore =
        max(
            0,
            min(
                100,
                $imageScore
            )
        );


    /*
    |--------------------------------------------------------------------------
    | Determine risk
    |--------------------------------------------------------------------------
    */

    $imageRisk =
        getRiskLevel(
            $imageScore
        );


    $imageClassification =
        getTrustClassification(
            $imageScore
        );


    $imageRecommendation =
        "The image was successfully inspected. " .
        "Further image content analysis can be added later.";


    /*
    |--------------------------------------------------------------------------
    | Save image analysis
    |--------------------------------------------------------------------------
    */

    try {

        $stmt = $pdo->prepare("
            INSERT INTO analyses
            (
                input_type,
                input_content,
                trust_score,
                risk_level,
                classification,
                recommendation
            )
            VALUES
            (
                :input_type,
                :input_content,
                :trust_score,
                :risk_level,
                :classification,
                :recommendation
            )
        ");


        $stmt->execute([

            ":input_type" =>
                $inputType,

            ":input_content" =>
                $inputContent,

            ":trust_score" =>
                $imageScore,

            ":risk_level" =>
                $imageRisk,

            ":classification" =>
                $imageClassification,

            ":recommendation" =>
                $imageRecommendation

        ]);


        $analysisId =
            $pdo->lastInsertId();


        echo json_encode([

            "success" =>
                true,

            "analysis_id" =>
                $analysisId,

            "trust_score" =>
                $imageScore,

            "risk_level" =>
                $imageRisk,

            "classification" =>
                $imageClassification,

            "recommendation" =>
                $imageRecommendation,

            "evidence" =>
                $imageEvidence,

            "image_info" => [

                "width" =>
                    $imageWidth,

                "height" =>
                    $imageHeight,

                "mime_type" =>
                    $imageMime,

                "file_size" =>
                    $imageSize

            ]

        ]);

        exit;

    }

    catch (PDOException $e) {

        echo json_encode([

            "success" =>
                false,

            "message" =>
                "Database error: " .
                $e->getMessage()

        ]);

        exit;

    }

}
/*
|--------------------------------------------------------------------------
| Basic image validation
|--------------------------------------------------------------------------
*/

if ($inputType === "image") {

    $allowedTypes = [

        "image/jpeg",
        "image/png",
        "image/webp"

    ];

    if (
        !in_array(
            $uploadedFile["type"],
            $allowedTypes
        )
    ) {

        echo json_encode([

            "success" => false,

            "message" =>
                "Only JPG, PNG and WEBP images are supported."

        ]);

        exit;

    }

}
}


/*
|--------------------------------------------------------------------------
| Normal JSON request
|--------------------------------------------------------------------------
*/

else {

    $input = json_decode(
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


    $inputType =
        $input["input_type"] ?? "";


    $inputContent =
        trim(
            $input["input_content"] ?? ""
        );

}


/*
|--------------------------------------------------------------------------
| 3. Validate input
|--------------------------------------------------------------------------
*/

if ($inputContent === "") {

    echo json_encode([
        "success" => false,
        "message" => "Please provide something to analyze."
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| 4. Convert text to lowercase
|--------------------------------------------------------------------------
*/

$text = strtolower($inputContent);

/*
|--------------------------------------------------------------------------
| Website URL mode
|--------------------------------------------------------------------------
|
| If the user selected Website URL, analyze it directly
| instead of treating the URL itself as a suspicious
| message/link.
|
|--------------------------------------------------------------------------
*/

if ($inputType === "url") {

    $urlResult =
        analyzeURL(
            $inputContent
        );


    /*
    |--------------------------------------------------------------------------
    | Save URL analysis to database
    |--------------------------------------------------------------------------
    */

    try {

        $stmt =
            $pdo->prepare("
                INSERT INTO analyses
                (
                    input_type,
                    input_content,
                    trust_score,
                    risk_level,
                    classification,
                    recommendation
                )
                VALUES
                (
                    :input_type,
                    :input_content,
                    :trust_score,
                    :risk_level,
                    :classification,
                    :recommendation
                )
            ");


        $stmt->execute([

            ":input_type" =>
                "url",

            ":input_content" =>
                $inputContent,

            ":trust_score" =>
                $urlResult["trust_score"],

            ":risk_level" =>
                $urlResult["risk_level"],

            ":classification" =>
                $urlResult["classification"],

            ":recommendation" =>
                $urlResult["recommendation"]

        ]);


        $analysisId =
            $pdo->lastInsertId();


        /*
        |--------------------------------------------------------------------------
        | Save URL evidence
        |--------------------------------------------------------------------------
        */

        if (
            isset($urlResult["evidence"]) &&
            is_array($urlResult["evidence"])
        ) {

            foreach (
                $urlResult["evidence"]
                as $item
            ) {

                $evidenceStmt =
                    $pdo->prepare("
                        INSERT INTO evidence
                        (
                            analysis_id,
                            category,
                            result,
                            explanation,
                            severity
                        )
                        VALUES
                        (
                            :analysis_id,
                            :category,
                            :result,
                            :explanation,
                            :severity
                        )
                    ");


                $evidenceStmt->execute([

                    ":analysis_id" =>
                        $analysisId,

                    ":category" =>
                        $item["category"] ?? "",

                    ":result" =>
                        $item["result"] ?? "",

                    ":explanation" =>
                        $item["explanation"] ?? "",

                    ":severity" =>
                        $item["severity"] ?? "Low"

                ]);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Send URL result back to JavaScript
        |--------------------------------------------------------------------------
        */

        echo json_encode([

            "success" =>
                true,

            "analysis_id" =>
                $analysisId,

            "trust_score" =>
                $urlResult["trust_score"],

            "risk_level" =>
                $urlResult["risk_level"],

            "classification" =>
                $urlResult["classification"],

            "recommendation" =>
                $urlResult["recommendation"],

            "evidence" =>
                $urlResult["evidence"]

        ]);

    }

    catch (PDOException $e) {

        echo json_encode([

            "success" =>
                false,

            "message" =>
                "Database error: " .
                $e->getMessage(),

            "error_code" =>
                $e->getCode()

        ]);

    }

    exit;

}

/*
|--------------------------------------------------------------------------
| 5. Scam Analysis
|--------------------------------------------------------------------------
*/

$scamResult =
    analyzeScamText(
        $inputContent
    );


/*
|--------------------------------------------------------------------------
| 6. Get Scam Signal
|--------------------------------------------------------------------------
|
| The scam analyzer produces a Trust Score from 0-100.
|
*/

$scamScore =
    (float)
    $scamResult["trust_score"];

$evidence =
    $scamResult["evidence"];

$signals = [
    "scam" =>
        $scamScore
];
/*
|--------------------------------------------------------------------------
| 7A. Analyze URLs found inside the message
|--------------------------------------------------------------------------
*/

$extractedURLs =
    extractURLs(
        $inputContent
    );


if (
    count($extractedURLs) > 0
) {

    /*
    |--------------------------------------------------------------------------
    | Analyze the first URL
    |--------------------------------------------------------------------------
    |
    | For the first version we analyze only the first
    | detected URL.
    |
    */

    $url =
        $extractedURLs[0];


    $urlResult =
        analyzeURL(
            $url
        );


    /*
    |--------------------------------------------------------------------------
    | Add URL signal to Trust Engine
    |--------------------------------------------------------------------------
    */

    $signals["url"] =
        (float)
        $urlResult["trust_score"];


    /*
    |--------------------------------------------------------------------------
    | Add URL evidence
    |--------------------------------------------------------------------------
    */

    if (
        isset(
            $urlResult["evidence"]
        )
    ) {

        foreach (
            $urlResult["evidence"]
            as $urlEvidence
        ) {

            $evidence[] =
                $urlEvidence;

        }

    }

}



$trustScore =
    calculateTrustScore(
        $signals
    );


/*
|--------------------------------------------------------------------------
| 8. Determine Risk Level
|--------------------------------------------------------------------------
*/

$riskLevel =
    getRiskLevel(
        $trustScore
    );


/*
|--------------------------------------------------------------------------
| 9. Determine Classification
|--------------------------------------------------------------------------
*/

$classification =
    getTrustClassification(
        $trustScore
    );


/*
|--------------------------------------------------------------------------
| 10. Get Evidence
|--------------------------------------------------------------------------
*/




/*
|--------------------------------------------------------------------------
| 11. Recommendation
|--------------------------------------------------------------------------
*/

$recommendation =
    $scamResult["recommendation"];
/*
|--------------------------------------------------------------------------
| 11A. TrustGuard AI Analysis
|--------------------------------------------------------------------------
*/

$aiResult = null;

if ($inputType === "text") {

    $aiPayload = json_encode([
        "content" => $inputContent,
        "input_type" => "text"
    ]);

    $ch = curl_init("http://127.0.0.1:8000/analyze");

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $aiPayload,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json"
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 90
    ]);

    $aiResponse = curl_exec($ch);

    if ($aiResponse !== false) {

        $decodedAI = json_decode(
            $aiResponse,
            true
        );

        if (
            is_array($decodedAI) &&
            ($decodedAI["success"] ?? false) === true
        ) {
            $aiResult = $decodedAI;
        }
    }

    curl_close($ch);
}



/*
|--------------------------------------------------------------------------
| 12. Save analysis to database
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
        INSERT INTO analyses
        (
            input_type,
            input_content,
            trust_score,
            risk_level,
            classification,
            recommendation
        )
        VALUES
        (
            :input_type,
            :input_content,
            :trust_score,
            :risk_level,
            :classification,
            :recommendation
        )
    ");


    $stmt->execute([

        ":input_type" => $inputType,

        ":input_content" => $inputContent,

        ":trust_score" => $trustScore,

        ":risk_level" => $riskLevel,

        ":classification" => $classification,

        ":recommendation" => $recommendation

    ]);


    $analysisId = $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | 13. Save evidence
    |--------------------------------------------------------------------------
    */

    foreach ($evidence as $item) {

        $evidenceStmt = $pdo->prepare("
            INSERT INTO evidence
            (
                analysis_id,
                category,
                result,
                explanation,
                severity
            )
            VALUES
            (
                :analysis_id,
                :category,
                :result,
                :explanation,
                :severity
            )
        ");


        $evidenceStmt->execute([

            ":analysis_id" => $analysisId,

            ":category" => $item["category"],

            ":result" => $item["result"],

            ":explanation" => $item["explanation"],

            ":severity" => $item["severity"]

        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | 14. Send result back to JavaScript
    |--------------------------------------------------------------------------
    */

    echo json_encode([

    "success" => true,

    "analysis_id" => $analysisId,

    "trust_score" => $trustScore,

    "risk_level" => $riskLevel,

    "classification" => $classification,

    "recommendation" => $recommendation,

    "evidence" => $evidence,

    "active_signals" =>
    getActiveSignals($signals),

"ai_analysis" =>
    $aiResult["analysis"] ?? null,

"ai_provider" =>
    $aiResult["provider"] ?? null,

"ai_model" =>
    $aiResult["model"] ?? null,

"memory_used" =>
    $aiResult["memory_used"] ?? false,

"memory_saved" =>
    $aiResult["memory_saved"] ?? false

]);



}
catch (PDOException $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Database error: " . $e->getMessage(),

        "error_code" =>
            $e->getCode()

    ]);

}


?>
