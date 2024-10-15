<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?php echo $_SESSION['message']; ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>


<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['tournament_id'])) {
    header('Location: dashboard.php');
    exit;
}

$tournament_id = $_SESSION['tournament_id'];

// Statistiken abrufen
$stmt = $pdo->prepare("
    SELECT 
        player_id, 
        COUNT(CASE WHEN winner = player_id THEN 1 END) as wins, 
        COUNT(CASE WHEN loser = player_id THEN 1 END) as losses 
    FROM matches 
    WHERE tournament_id = ? 
    GROUP BY player_id
");
$stmt->execute([$tournament_id]);
$statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Statistiken</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Statistiken für Turnier ID: <?php echo $tournament_id; ?></h1>
    
    <table>
        <tr>
            <th>Spieler ID</th>
            <th>Siege</th>
            <th>Niederlagen</th>
        </tr>
        <?php foreach ($statistics as $stat): ?>
            <tr>
                <td><?php echo $stat['player_id']; ?></td>
                <td><?php echo $stat['wins']; ?></td>
                <td><?php echo $stat['losses']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
