<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Benutzerdaten abrufen
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Turnierverwaltung</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Willkommen, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <nav>
            <ul>
                <li><a href="create_tournament.php">Turnier erstellen</a></li>
                <li><a href="live_results.php">Live Ergebnisse</a></li>
                <li><a href="statistics.php">Statistiken</a></li>
                <li><a href="cinema_mode.php">Kinomodus</a></li>
                <li><a href="manage_users.php">Benutzer verwalten</a></li>
                <li><a href="../scripts/logout.php">Abmelden</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Aktuelle Turniere</h2>
        <!-- Hier können wir eine Liste der aktuellen Turniere anzeigen -->
        <?php
        $stmt = $pdo->prepare("SELECT * FROM tournaments WHERE status = 'active'");
        $stmt->execute();
        $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (count($tournaments) > 0): ?>
            <ul>
                <?php foreach ($tournaments as $tournament): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($tournament['name']); ?></strong> (ID: <?php echo $tournament['id']; ?>) - 
                        <a href="live_results.php">Ergebnisse anzeigen</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Momentan gibt es keine aktiven Turniere.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Tischtennis Verein</p>
    </footer>
</body>
</html>
