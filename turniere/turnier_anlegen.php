<?php
include './includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $turniername = $_POST['turniername'];

    // SQL-Insert-Statement
    $sql = "INSERT INTO turniere (name) VALUES ('$turniername')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Neues Turnier erfolgreich angelegt!";
    } else {
        echo "Fehler: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- Form zum Anlegen eines Turniers -->
<form action="turnier_anlegen.php" method="post">
    <label for="turniername">Turniername:</label>
    <input type="text" id="turniername" name="turniername" required>
    <input type="submit" value="Turnier anlegen">
</form>
