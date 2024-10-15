<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['tournament_id'])) {
    header('Location: dashboard.php');
    exit;
}

$tournament_id = $_SESSION['tournament_id'];

// Alle Matches für das Turnier abrufen
$stmt = $pdo->prepare("SELECT * FROM matches WHERE tournament_id = ?");
$stmt->execute([$tournament_id]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ergebnisse aktualisieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_results'])) {
    $match_id = $_POST['match_id'];
    $winner_id = $_POST['winner_id'];

    $stmt = $pdo->prepare("UPDATE matches SET winner = ? WHERE id = ?");
    $stmt->execute([$winner_id, $match_id]);

    $_SESSION['message'] = "Ergebnis erfolgreich aktualisiert!";
    header('Location: live_results.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Live Ergebnisse</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Live Ergebnisse für Turnier ID: <?php echo $tournament_id; ?></h1>
    
    <table>
        <tr>
            <th>Runde</th>
            <th>Spieler 1</th>
            <th>Spieler 2</th>
            <th>Aktion</th>
        </tr>
        <?php foreach ($matches as $match): ?>
            <tr>
                <td><?php echo $match['round']; ?></td>
                <td><?php echo $match['player1']; ?></td>
                <td><?php echo $match['player2']; ?></td>
                <td>
                    <form action="live_results.php" method="post">
                        <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                        <select name="winner_id" required>
                            <option value="">Wähle einen Gewinner</option>
                            <option value="<?php echo $match['player1']; ?>"><?php echo $match['player1']; ?></option>
                            <option value="<?php echo $match['player2']; ?>"><?php echo $match['player2']; ?></option>
                        </select>
                        <button type="submit" name="update_results">Ergebnis aktualisieren</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
