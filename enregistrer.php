<?php
require_once "connexion.php";

$nom = $_POST["nom"];
$telephone = $_POST["telephone"];
$email = $_POST["email"];
$date = (new DateTime())->format('Y-m-d H:i:s');

if (!empty($nom) && !empty($email)) {
    // Préparer la requête SQL
    $query = "INSERT INTO candidats(nom, telephone, email, date_inscription) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        // Liaison des paramètres (s = string)
        $stmt->bind_param("ssss", $nom, $telephone, $email, $date);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Inscription réussie !<br><a href='index.php'>Retour</a>";
        } else {
            echo "Erreur lors de l'inscription : " . $stmt->error;
        }
    } else {
        echo "Erreur de préparation : " . $conn->error;
    }
} else {
    echo "Tous les champs obligatoires ne sont pas remplis.";
}
$conn->close();
