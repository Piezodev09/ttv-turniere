<?php
session_start();
require '../scripts/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $csv_file = $_FILES['csv_file']['tmp_name'];

    // Datei öffnen und Zeilen durchgehen
    if (($handle = fopen($csv_file, "r")) !== FALSE) {
        fgetcsv($handle); // Erste Zeile (Header) überspringen
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // CSV-Daten in Variablen speichern (Vorname, Nachname, Geburtsdatum, QTTR)
            $first_name = $data[0];
            $last_name = $data[1];
            $birthdate = $data[2];
            $qttr = $data[3];

            // In die Datenbank einfügen
            $stmt = $pdo->prepare("INSERT INTO players (first_name, last_name, birthdate, qttr) VALUES (?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $birthdate, $qttr]);
        }
        fclose($handle);
        $_SESSION['message'] = "Spieler erfolgreich importiert!";
    } else {
        $_SESSION['error'] = "Fehler beim Öffnen der Datei.";
    }
}

header('Location: manage_players.php');
exit;
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Spielerverwaltung</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <h1>Spieler verwalten</h1>

  <!-- CSV-Import-Formular -->
  <form action="manage_players.php" method="post" enctype="multipart/form-data">
    <label for="csv_file">CSV-Datei hochladen:</label>
    <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
    <button type="submit">Importieren</button>
  </form>

  <!-- Anzeige aller importierten Spieler -->
  <section id="spieler-liste">
    <h2>Aktuelle Spieler</h2>
    <ul>
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM players");
          $players = $stmt->fetchAll();
          if (count($players) > 0) {
              foreach ($players as $player) {
                  echo "<li>{$player['first_name']} {$player['last_name']} - QTTR: {$player['qttr']} - Geburtsdatum: {$player['birthdate']}</li>";
              }
          } else {
              echo "<li>Es wurden noch keine Spieler importiert.</li>";
          }
      } catch (PDOException $e) {
          echo "<li>Fehler beim Laden der Spieler: " . $e->getMessage() . "</li>";
      }
      ?>
    </ul>
  </section>
</body>
</html>
