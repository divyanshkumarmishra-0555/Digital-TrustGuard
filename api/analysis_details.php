<?php

header("Content-Type: application/json");

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Get analysis ID
|--------------------------------------------------------------------------
*/

$id =
    isset($_GET["id"])
        ? (int) $_GET["id"]
        : 0;


/*
|--------------------------------------------------------------------------
| Validate ID
|--------------------------------------------------------------------------
*/

if ($id <= 0) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Invalid analysis ID."

    ]);

    exit;

}


try {

    /*
    |--------------------------------------------------------------------------
    | Get analysis
    |--------------------------------------------------------------------------
    */

    $stmt =
        $pdo->prepare("
            SELECT
                id,
                input_type,
                input_content,
                trust_score,
                risk_level,
                classification,
                recommendation,
                created_at
            FROM analyses
            WHERE id = :id
            LIMIT 1
        ");


    $stmt->execute([

        ":id" => $id

    ]);


    $analysis =
        $stmt->fetch(
            PDO::FETCH_ASSOC
        );


    /*
    |--------------------------------------------------------------------------
    | Check analysis exists
    |--------------------------------------------------------------------------
    */

    if (!$analysis) {

        echo json_encode([

            "success" => false,

            "message" =>
                "Analysis not found."

        ]);

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | Get evidence
    |--------------------------------------------------------------------------
    */

    $evidenceStmt =
        $pdo->prepare("
            SELECT
                category,
                result,
                explanation,
                severity
            FROM evidence
            WHERE analysis_id = :analysis_id
            ORDER BY id ASC
        ");


    $evidenceStmt->execute([

        ":analysis_id" => $id

    ]);


    $evidence =
        $evidenceStmt->fetchAll(
            PDO::FETCH_ASSOC
        );


    /*
    |--------------------------------------------------------------------------
    | Return complete analysis
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "analysis" =>
            $analysis,

        "evidence" =>
            $evidence

    ]);

}


catch (PDOException $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            "Database error: " .
            $e->getMessage()

    ]);

}

?>
