<?php

header("Content-Type: application/json");

require_once "../config/database.php";


/*
|--------------------------------------------------------------------------
| Get recent TrustGuard analyses
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
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
        ORDER BY created_at DESC
        LIMIT 20
    ");


    $stmt->execute();


    $analyses =
        $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );


    echo json_encode([

        "success" => true,

        "count" =>
            count($analyses),

        "analyses" =>
            $analyses

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
