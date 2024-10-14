<?php
include './includes/db.php';

if (isset($_GET['id'])) {
    $turnier_id = $_GET['id'];

    // SQL-Befehl zum Löschen des Turniers
    $sql = "DELETE FROM turniere WHERE id='$turnier_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Turnier erfolgreich gelöscht!";
    } else {
        echo "Fehler: " . $conn->error;
    }
}

$conn->close();
header("Location: turnier_uebersicht.php"); // Zurück zur Übersicht
?>
