<?php
session_start();
require '../scripts/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Benutzerinformationen abrufen
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['message'] = "Benutzer nicht gefunden!";
        header('Location: manage_users.php');
        exit;
    }
}

// Benutzer aktualisieren
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password']; // Passwort nur aktualisieren, wenn eingegeben

    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->execute([$username, $password, $user_id]);

    $_SESSION['message'] = "Benutzer erfolgreich aktualisiert!";
    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer bearbeiten</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Benutzer bearbeiten</h1>

    <!-- Feedback-Meldung -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="post">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <input type="password" name="password" placeholder="Neues Passwort (optional)">
        <button type="submit" name="update_user">Benutzer aktualisieren</button>
    </form>

    <a href="manage_users.php">Zurück zur Benutzerverwaltung</a>
</body>
</html>
