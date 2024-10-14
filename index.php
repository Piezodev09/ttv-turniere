<?php
include './includes/db.php';

// Aktuelle Turniere abrufen
$sql = "SELECT id, name FROM turniere ORDER BY datum DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tischtennis Turniere</title>
    <link rel="stylesheet" href="style.css"> <!-- Verlinke eine CSS-Datei für das Design -->
    <style>
        /* Einfaches Styling für die Seite */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .dropdown {
            float: left;
            overflow: hidden;
        }
        .dropdown .dropbtn {
            font-size: 16px;
            border: none;
            outline: none;
            color: white;
            padding: 14px 20px;
            background-color: inherit;
            margin: 0;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            float: none;
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        .dropdown-content a:hover {
            background-color: #ddd;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .hero {
            height: 500px;
            background: url('hero-image.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            text-align: center;
        }
        .hero h1 {
            font-size: 50px;
            margin: 0;
        }
        .cta {
            margin-top: 20px;
        }
        .cta a {
            background-color: #ff6347;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 18px;
            border-radius: 5px;
            margin: 10px;
        }
        .cta a:hover {
            background-color: #ff4500;
        }
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <div class="dropdown">
            <button class="dropbtn">Aktuelle Turniere 
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<a href='turnier_details.php?id=" . $row['id'] . "'>" . $row['name'] . "</a>";
                    }
                } else {
                    echo "<a href='#'>Keine Turniere</a>";
                }
                ?>
            </div>
        </div> 
        <a href="turnier_anlegen.php">Turnier anlegen</a>
        <a href="login.php">Login</a>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <div>
            <h1>Tischtennis Turniere</h1>
            <p>Organisiere und verwalte deine Tischtennis-Turniere einfach und schnell.</p>
            <div class="cta">
                <a href="turnier_anlegen.php">Turnier anlegen</a>
                <a href="register.php">Jetzt registrieren</a>
            </div>
        </div>
    </div>

    <!-- News or Highlights Section -->
    <div class="container" style="padding: 20px; text-align: center;">
        <h2>Neuigkeiten aus der Tischtennis-Welt</h2>
        <p>Bleib auf dem Laufenden mit den neuesten Turnierergebnissen und Highlights.</p>
        <!-- Du kannst hier später aktuelle News oder Highlights hinzufügen -->
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Tischtennis Turniere | Alle Rechte vorbehalten.</p>
    </div>

</body>
</html>

<?php
$conn->close();
?>
