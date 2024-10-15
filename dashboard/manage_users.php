<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Benutzerliste abrufen
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Benutzer hinzufügen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Passwort hashen

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);

    $_SESSION['message'] = "Benutzer erfolgreich hinzugefügt!";
    header('Location: manage_users.php');
    exit;
}

// Benutzer löschen
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    $_SESSION['message'] = "Benutzer erfolgreich gelöscht!";
    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzerverwaltung</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Benutzerverwaltung</h1>

    <!-- Feedback-Meldung -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <h2>Benutzer hinzufügen</h2>
    <form action="manage_users.php" method="post">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit" name="add_user">Benutzer hinzufügen</button>
    </form>

    <h2>Aktuelle Benutzer</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Benutzername</th>
            <th>Aktion</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Bearbeiten</a> | 
                    <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?');">Löschen</a>
                    <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Sind Sie sicher, dass Sie diesen Benutzer löschen möchten?');">Löschen</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
