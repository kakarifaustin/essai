<?php
require_once "connexion.php";

$result = $conn->query("SELECT * FROM candidats ORDER BY date_inscription DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des candidats</title>
</head>
<body>
    <h2>Candidats inscrits</h2>
    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Date d'inscription</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['telephone']) ?></td>
            <td><?= $row['date_inscription'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="index.php">Retour au formulaire</a>
</body>
</html>
