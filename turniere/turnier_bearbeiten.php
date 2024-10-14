<?php
include './includes/db.php';

// SQL-Abfrage, um alle Turniere abzurufen
$sql = "SELECT id, name, datum FROM turniere ORDER BY datum DESC";
$result = $conn->query($sql);
?>

<h2>Turnierübersicht</h2>

<table>
    <tr>
        <th>Turniername</th>
        <th>Datum</th>
        <th>Aktionen</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        // Ausgabe der Turniere in einer Tabelle
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["datum"] . "</td>";
            echo "<td><a href='turnier_bearbeiten.php?id=" . $row["id"] . "'>Bearbeiten</a> | <a href='turnier_loeschen.php?id=" . $row["id"] . "' onclick=\"return confirm('Willst du dieses Turnier wirklich löschen?');\">Löschen</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Keine Turniere gefunden.</td></tr>";
    }
    ?>

</table>

<?php
$conn->close();
?>
