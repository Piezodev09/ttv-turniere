<?php
// Dummy-Login-Daten (kann später durch eine echte Datenbank ersetzt werden)
$valid_username = 'turnierleiter';
$valid_password = 'passwort123';

// Login-Formular-Daten abrufen
$username = $_POST['username'];
$password = $_POST['password'];

// Login-Überprüfung
if ($username === $valid_username && $password === $valid_password) {
    // Erfolgreiches Login, weiterleiten zum Dashboard
    header('Location: ../dashboard/dashboard.php');
    exit;
} else {
    // Login fehlgeschlagen, zurück zur Startseite mit Fehlermeldung
    echo '<script>alert("Ungültiger Benutzername oder Passwort!"); window.location.href = "../index.html#login";</script>';
    exit;
}
?>
