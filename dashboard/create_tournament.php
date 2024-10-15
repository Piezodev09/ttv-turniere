<?php
session_start();
require '../scripts/db_connection.php';

// Daten vom Formular verarbeiten
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tournament_name = $_POST['tournament_name'];
    $tournament_type = $_POST['tournament_type'];
    $players = $_POST['players']; // Spieler-IDs oder Namen
    $num_groups = $_POST['num_groups'] ?? 0; // Anzahl der Gruppen für Gruppenphase

    // Turnier in der Datenbank speichern
    $stmt = $pdo->prepare("INSERT INTO tournaments (name, type, num_groups) VALUES (?, ?, ?)");
    $stmt->execute([$tournament_name, $tournament_type, $num_groups]);

    // Spieler zuordnen (hier kannst du später die Spieler-ID verwenden)
    $tournament_id = $pdo->lastInsertId();
    foreach ($players as $player_id) {
        $stmt = $pdo->prepare("INSERT INTO tournament_players (tournament_id, player_id) VALUES (?, ?)");
        $stmt->execute([$tournament_id, $player_id]);
    }

    $_SESSION['message'] = "Turnier erfolgreich erstellt!";
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnier erstellen</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Turnier erstellen</h1>

    <form action="create_tournament.php" method="post">
        <label for="tournament_name">Turniername:</label>
        <input type="text" id="tournament_name" name="tournament_name" required>

        <label for="tournament_type">Turnierart:</label>
        <select id="tournament_type" name="tournament_type" required>
            <option value="ko">K.O.</option>
            <option value="double_ko">Doppel-K.O.</option>
            <option value="group">Gruppenphase</option>
        </select>

        <label for="num_groups">Anzahl der Gruppen (für Gruppenphase):</label>
        <input type="number" id="num_groups" name="num_groups" min="2">

        <label for="players">Spieler auswählen (IDs oder Namen):</label>
        <input type="text" id="players" name="players[]" required placeholder="Spieler durch Kommas trennen">

        <button type="submit">Turnier erstellen</button>
    </form>

    <?php
// Hier kannst du das K.O.-Turnier generieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_ko'])) {
    $_SESSION['tournament_id'] = $tournament_id; // Speichere die Turnier-ID in der Session
    header('Location: generate_ko_tournament.php');
    exit;
}
?>

<!-- K.O.-Turnier-Generierung -->
<form action="create_tournament.php" method="post">
    <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
    <button type="submit" name="generate_ko">K.O.-Turnier generieren</button>
</form>

<?php
// Hier kannst du die Gruppenphase generieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_group'])) {
    $_SESSION['tournament_id'] = $tournament_id; // Speichere die Turnier-ID in der Session
    header('Location: generate_group_tournament.php');
    exit;
}
?>

<!-- Gruppenphase-Generierung -->
<form action="create_tournament.php" method="post">
    <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
    <label for="num_groups">Anzahl der Gruppen:</label>
    <input type="number" id="num_groups" name="num_groups" min="2" required>
    <button type="submit" name="generate_group">Gruppenphase generieren</button>
</form>

<?php
// Hier kannst du das Doppel-K.O.-Turnier generieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_double_ko'])) {
    $_SESSION['tournament_id'] = $tournament_id; // Speichere die Turnier-ID in der Session
    header('Location: generate_double_ko_tournament.php');
    exit;
}
?>

<!-- Doppel-K.O.-Turnier-Generierung -->
<form action="create_tournament.php" method="post">
    <input type="hidden" name="tournament_id" value="<?php echo $tournament_id; ?>">
    <button type="submit" name="generate_double_ko">Doppel-K.O.-Turnier generieren</button>
</form>

</body>
</html>
