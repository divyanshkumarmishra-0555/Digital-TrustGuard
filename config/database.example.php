
<?php

/*
 * Digital TrustGuard - Database Configuration Example
 *
 * Copy this file to database.php and update
 * the settings for your local MySQL installation.
 */

$host = "localhost";
$dbname = "trustguard";
$username = "YOUR_MYSQL_USERNAME";
$password = "YOUR_MYSQL_PASSWORD";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die("Database connection failed. Check your configuration.");

}
