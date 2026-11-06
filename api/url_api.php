<?php

header("Content-Type: application/json");

require_once __DIR__ . "/url_analyzer.php";
require_once __DIR__ . "/../config/database.php";


/*
|--------------------------------------------------------------------------
| Only allow POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    echo json_encode([

        "success" => false,

        "message" =>
            "Only POST requests are allowed."

    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Read JSON request
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Get URL
|--------------------------------------------------------------------------
*/

$url =
    trim(
        $input["url"] ?? ""
    );


if ($url === "") {

    echo json_encode([

        "success" => false,

        "message" =>
            "Please provide a URL."

    ]);

    exit;

}


/*
|--------------------------------------------------------------------------
| Analyze URL
|--------------------------------------------------------------------------
*/

$result =
    analyzeURL($url);

/*
|--------------------------------------------------------------------------
| Get AI analysis from TrustGuard Python service
|--------------------------------------------------------------------------
*/

$aiResult = null;

$aiPayload = json_encode([
    "content" => $url,
    "input_type" => "url"
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

$curlError = curl_error($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$decodedAI = null;

if ($aiResponse !== false) {
    $decodedAI = json_decode($aiResponse, true);

    if (
        is_array($decodedAI) &&
        ($decodedAI["success"] ?? false) === true
    ) {
        $aiResult = $decodedAI;
    }
}

curl_close($ch);
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
    $input["input_type"] ?? "url",

        ":input_content" =>
            $url,

        ":trust_score" =>
            $result["trust_score"],

        ":risk_level" =>
            $result["risk_level"],

        ":classification" =>
            $result["classification"],

        ":recommendation" =>
            $result["recommendation"]

    ]);


    $analysisId =
        $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | Save URL evidence
    |--------------------------------------------------------------------------
    */

    if (
        isset($result["evidence"]) &&
        is_array($result["evidence"])
    ) {

        foreach (
            $result["evidence"]
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
                    $item["severity"] ?? "low"

            ]);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Return successful result
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        

        "success" =>
            true,

        "analysis_id" =>
            $analysisId,

        "trust_score" =>
            $result["trust_score"],

        "risk_level" =>
            $result["risk_level"],

        "classification" =>
            $result["classification"],

        "recommendation" =>
            $result["recommendation"],

        "evidence" =>
            $result["evidence"] ?? [],
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

        "success" =>
            false,

        "message" =>
            "Database error: " .
            $e->getMessage()

    ]);

}

?>