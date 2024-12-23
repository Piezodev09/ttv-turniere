<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['tournament_id'])) {
    header('Location: dashboard.php');
    exit;
}

$tournament_id = $_SESSION['tournament_id'];

// Spieler für das Turnier abrufen
$stmt = $pdo->prepare("SELECT * FROM tournament_players WHERE tournament_id = ?");
$stmt->execute([$tournament_id]);
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Logik für K.O.-System
$matches = [];
$rounds = ceil(log(count($players), 2)); // Anzahl der Runden

for ($i = 0; $i < $rounds; $i++) {
    $matches[$i] = [];
}

// Spielermatching für die erste Runde
for ($i = 0; $i < count($players); $i += 2) {
    if (isset($players[$i + 1])) {
        $matches[0][] = [
            'player1' => $players[$i]['player_id'],
            'player2' => $players[$i + 1]['player_id'],
        ];
    } else {
        $matches[0][] = [
            'player1' => $players[$i]['player_id'],
            'player2' => null, // Freilos
        ];
    }
}

// Hier kannst du die Matches in die Datenbank speichern
foreach ($matches as $round => $round_matches) {
    foreach ($round_matches as $match) {
        $stmt = $pdo->prepare("INSERT INTO matches (tournament_id, round, player1, player2) VALUES (?, ?, ?, ?)");
        $stmt->execute([$tournament_id, $round, $match['player1'], $match['player2']]);
    }
}

$_SESSION['message'] = "K.O.-Turnier erfolgreich generiert!";
header('Location: dashboard.php');
exit;
?>
