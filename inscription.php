<?php
require 'config.php';

$message = '';
$error = '';

// Fetch centers for the dropdown
try {
    $stmt = $pdo->query("SELECT id, designation FROM centre ORDER BY designation");
    $centres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur: Impossible de charger les centres.";
    $centres = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data according to the new DB structure
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $lieu_naissance = trim($_POST['lieu_naissance'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id_section = filter_input(INPUT_POST, 'id_section', FILTER_VALIDATE_INT);
    $pere = trim($_POST['pere'] ?? '');
    $mere = trim($_POST['mere'] ?? '');
    $tuteur = trim($_POST['tuteur'] ?? '');

    // Basic Validation
    if (empty($nom) || empty($prenom) || empty($date_naissance) || empty($lieu_naissance) || empty($adresse) || empty($pere) || empty($mere) || !$id_section) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "L'adresse email n'est pas valide.";
    } else {
        // Insert into database
        try {
            $sql = "INSERT INTO inscription (nom, prenom, date_naissance, lieu_naissance, adresse, telephone, email, id_section, pere, mere, tuteur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $date_naissance, $lieu_naissance, $adresse, $telephone, $email, $id_section, $pere, $mere, $tuteur]);
            $message = "Inscription réussie !";
        } catch (PDOException $e) {
            $error = "Erreur lors de l'enregistrement: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; }
        .card { margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center bg-primary text-white">
                <h3><i class="fas fa-user-plus"></i> Formulaire d'inscription</h3>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="inscription.php" method="post">
                    <h5 class="mt-4">Informations de l'Apprenant</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de Naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lieu_naissance" class="form-label">Lieu de Naissance</label>
                            <input type="text" class="form-control" id="lieu_naissance" name="lieu_naissance" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>

                    <h5 class="mt-4">Informations des Parents / Tuteur</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pere" class="form-label">Nom et Prénom du Père</label>
                            <input type="text" class="form-control" id="pere" name="pere" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mere" class="form-label">Nom et Prénom de la Mère</label>
                            <input type="text" class="form-control" id="mere" name="mere" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tuteur" class="form-label">Nom et Prénom du Tuteur (si différent)</label>
                        <input type="text" class="form-control" id="tuteur" name="tuteur">
                    </div>

                    <h5 class="mt-4">Choix de la Formation</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_centre" class="form-label">Centre</label>
                            <select class="form-select" id="id_centre" name="id_centre" required>
                                <option value="">Sélectionnez un centre</option>
                                <?php foreach ($centres as $centre): ?>
                                    <option value="<?= $centre['id'] ?>"><?= htmlspecialchars($centre['designation']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="id_section" class="form-label">Section</label>
                            <select class="form-select" id="id_section" name="id_section" required disabled>
                                <option value="">Sélectionnez d'abord un centre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.getElementById("id_centre").addEventListener("change", function () {
    let idCentre = this.value;
    let sectionSelect = document.getElementById("id_section");

    // Réinitialiser la liste des sections
    sectionSelect.innerHTML = '<option value="">Chargement...</option>';
    sectionSelect.disabled = true;

    if (idCentre) {
        fetch("get_sections.php?id_centre=" + idCentre)
            .then(response => response.json())
            .then(data => {
                sectionSelect.innerHTML = '<option value="">Sélectionnez une section</option>';

                if (data.error) {
                    sectionSelect.innerHTML = '<option value="">Erreur : ' + data.error + '</option>';
                } else if (data.length > 0) {
                    data.forEach(section => {
                        let opt = document.createElement("option");
                        opt.value = section.id;
                        opt.textContent = section.designation;
                        sectionSelect.appendChild(opt);
                    });
                } else {
                    sectionSelect.innerHTML = '<option value="">Aucune section disponible</option>';
                }
                sectionSelect.disabled = false;
            })
            .catch(error => {
                sectionSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                console.error("Erreur fetch:", error);
            });
    } else {
        sectionSelect.innerHTML = '<option value="">Sélectionnez d\'abord un centre</option>';
    }
});
</script>

</body>
</html>
