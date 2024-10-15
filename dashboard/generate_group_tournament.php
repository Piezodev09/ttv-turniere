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

// Gruppenbildung
$num_groups = $_POST['num_groups']; // Anzahl der Gruppen
$grouped_players = array_chunk($players, ceil(count($players) / $num_groups));

// Spiele innerhalb der Gruppen generieren
$matches = [];

foreach ($grouped_players as $group_index => $group) {
    for ($i = 0; $i < count($group); $i++) {
        for ($j = $i + 1; $j < count($group); $j++) {
            $matches[] = [
                'group' => $group_index + 1,
                'player1' => $group[$i]['player_id'],
                'player2' => $group[$j]['player_id'],
            ];
        }
    }
}

// Hier kannst du die Matches in die Datenbank speichern
foreach ($matches as $match) {
    $stmt = $pdo->prepare("INSERT INTO matches (tournament_id, round, player1, player2) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tournament_id, 1, $match['player1'], $match['player2']]); // 1 = Gruppenrunde
}

$_SESSION['message'] = "Gruppenphase erfolgreich generiert!";
header('Location: dashboard.php');
exit;
?>
