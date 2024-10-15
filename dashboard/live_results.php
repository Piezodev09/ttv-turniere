<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Aktive Turniere abrufen
$stmt = $pdo->prepare("SELECT * FROM tournaments WHERE status = 'active'");
$stmt->execute();
$tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ergebnisse aktualisieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_result'])) {
    $match_id = $_POST['match_id'];
    $result1 = $_POST['result1'];
    $result2 = $_POST['result2'];

    // Ergebnis aktualisieren (angenommen, wir haben eine matches Tabelle)
    $stmt = $pdo->prepare("UPDATE matches SET result1 = ?, result2 = ? WHERE id = ?");
    $stmt->execute([$result1, $result2, $match_id]);

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
    <h1>Live Ergebnisse</h1>

    <!-- Feedback-Meldung -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <h2>Aktive Turniere</h2>
    <?php if (count($tournaments) > 0): ?>
        <ul>
            <?php foreach ($tournaments as $tournament): ?>
                <li>
                    <strong><?php echo htmlspecialchars($tournament['name']); ?></strong> (ID: <?php echo $tournament['id']; ?>)
                    <h3>Matches</h3>
                    <table>
                        <tr>
                            <th>Spiel ID</th>
                            <th>Spieler 1</th>
                            <th>Spieler 2</th>
                            <th>Ergebnis</th>
                            <th>Aktion</th>
                        </tr>
                        <?php
                        // Hier sollten wir die Matches für das aktuelle Turnier abrufen
                        $stmt = $pdo->prepare("SELECT * FROM matches WHERE tournament_id = ?");
                        $stmt->execute([$tournament['id']]);
                        $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($matches as $match): ?>
                            <tr>
                                <td><?php echo $match['id']; ?></td>
                                <td><?php echo htmlspecialchars($match['player1']); ?></td>
                                <td><?php echo htmlspecialchars($match['player2']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($match['result1'] . " : " . $match['result2']); ?>
                                </td>
                                <td>
                                    <form action="live_results.php" method="post">
                                        <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                                        <input type="number" name="result1" placeholder="Result 1" min="0" required>
                                        <input type="number" name="result2" placeholder="Result 2" min="0" required>
                                        <button type="submit" name="update_result">Aktualisieren</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Momentan gibt es keine aktiven Turniere.</p>
    <?php endif; ?>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
