<?php
$host = 'localhost';  // Datenbank-Host
$dbname = 'turnierclient';  // Datenbankname
$user = 'root';  // Dein Datenbank-User (anpassen)
$password = '';  // Dein Datenbank-Passwort (anpassen)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}
?>
