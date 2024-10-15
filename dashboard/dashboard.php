<?php
session_start();
require '../scripts/db_connection.php';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Turnier-Dashboard</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <h1>Willkommen im Turnier-Dashboard</h1>
  <p>Hier kannst du Turniere verwalten und neue erstellen.</p>

  <!-- Turnier-Erstellung -->
  <section id="turnier-erstellen">
    <h2>Neues Turnier erstellen</h2>
    <form action="create_tournament.php" method="post">
      <label for="tournament_name">Turniername:</label>
      <input type="text" id="tournament_name" name="tournament_name" required>
      
      <label for="date">Datum:</label>
      <input type="date" id="date" name="date" required>
      
      <label for="type">Turniermodus:</label>
      <select id="type" name="type" required>
        <option value="ko">K.O.-System</option>
        <option value="double_ko">Doppel-K.O.-System</option>
        <option value="groups">Gruppenphase</option>
      </select>
      
      <label for="tables">Anzahl der Tische:</label>
      <input type="number" id="tables" name="tables" min="1" required>

      <label for="duration">Geplante Dauer (in Stunden):</label>
      <input type="number" id="duration" name="duration" min="1" required>

      <label for="groups">Anzahl der Gruppen:</label>
      <input type="number" id="groups" name="groups" min="1" required>
      
      <button type="submit">Turnier erstellen</button>
    </form>
  </section>

  <!-- Bestehende Turniere -->
  <section id="turniere-verwalten">
    <h2>Aktuelle Turniere</h2>
    <ul>
      <?php
      try {
          $stmt = $pdo->query("SELECT * FROM tournaments");
          $tournaments = $stmt->fetchAll();
          if (count($tournaments) > 0) {
              foreach ($tournaments as $tournament) {
                  echo "<li>{$tournament['name']} ({$tournament['date']}) - Modus: {$tournament['type']} - Tische: {$tournament['tables']} - Gruppen: {$tournament['groups']} - Dauer: {$tournament['duration']} Stunden</li>";
              }
          } else {
              echo "<li>Es sind noch keine Turniere vorhanden.</li>";
          }
      } catch (PDOException $e) {
          echo "<li>Fehler beim Laden der Turniere: " . $e->getMessage() . "</li>";
      }
      ?>

      
      <!-- Link zu den Live-Ergebnissen -->
<h2>Turnierverwaltung</h2>
<ul>
    <li><a href="live_results.php">Live Ergebnisse anzeigen</a></li>
</ul>

    </ul>
  </section>
</body>
</html>
