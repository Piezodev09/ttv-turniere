<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['tournament_id'])) {
    header('Location: dashboard.php');
    exit;
}

$tournament_id = $_SESSION['tournament_id'];

// Alle aktuellen Matches abrufen
$stmt = $pdo->prepare("SELECT * FROM matches WHERE tournament_id = ?");
$stmt->execute([$tournament_id]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kinomodus</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Kinomodus für Turnier ID: <?php echo $tournament_id; ?></h1>
    
    <div id="cinema">
        <h2>Aktuelle Spiele</h2>
        <ul>
            <?php foreach ($matches as $match): ?>
                <li>Runde: <?php echo $match['round']; ?> - Spieler 1: <?php echo $match['player1']; ?> vs Spieler 2: <?php echo $match['player2']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
