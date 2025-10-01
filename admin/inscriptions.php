<?php
require_once '../config.php';

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id_inscription = $_POST['id_inscription'] ?? '';
    if ($id_inscription) {
        $stmt = $pdo->prepare("DELETE FROM inscription WHERE id = ?");
        if ($stmt->execute([$id_inscription])) {
            $message = 'Inscription supprimée avec succès.';
        } else {
            $error = 'Erreur lors de la suppression.';
        }
    }
}

// Fetch all inscriptions with details
$stmt = $pdo->query("
    SELECT i.id, i.nom, i.prenom, i.adresse, i.telephone, i.email, i.date_naissance, i.lieu_naissance, i.pere, i.mere, i.tuteur, i.date_inscription, c.designation as centre_designation, s.designation as section_designation
    FROM inscription i
    JOIN section s ON i.id_section = s.id
    JOIN centre c ON s.id_centre = c.id
    ORDER BY i.date_inscription DESC
");
$inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Inscriptions - Admin CEM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 0.2rem 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        .main-content { background: #f8f9fa; min-height: 100vh; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 15px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4"><i class="fas fa-cogs me-2"></i>Admin CEM</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Tableau de bord</a>
                        <a class="nav-link" href="centres.php"><i class="fas fa-building me-2"></i>Gestion des centres</a>
                        <a class="nav-link" href="sections.php"><i class="fas fa-list me-2"></i>Gestion des sections</a>
                        <a class="nav-link active" href="inscriptions.php"><i class="fas fa-users me-2"></i>Inscriptions</a>
                        <hr class="my-3">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-0">
                <div class="p-4">
                    <h2 class="mb-4"><i class="fas fa-users me-2"></i>Gestion des Inscriptions</h2>

                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nom complet</th>
                                            <th>Téléphone</th>
                                            <th>Centre</th>
                                            <th>Section</th>
                                            <th>Date d'inscription</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($inscriptions)): ?>
                                            <tr><td colspan="12" class="text-center text-muted">Aucune inscription trouvée.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($inscriptions as $inscription): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold"><?= htmlspecialchars($inscription['prenom'] . ' ' . $inscription['nom']) ?></div>
                                                        <small class="text-muted"><?= htmlspecialchars($inscription['email']) ?></small>
                                                    </td>
                                                    <td><?= htmlspecialchars($inscription['telephone']) ?></td>
                                                    <td><?= htmlspecialchars($inscription['centre_designation']) ?></td>
                                                    <td><?= htmlspecialchars($inscription['section_designation']) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($inscription['date_inscription'])) ?></td>
                                                    <td class="d-flex gap-2">
                                                        <button class="btn btn-sm btn-primary view-details" data-id="<?= $inscription['id'] ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="id_inscription" value="<?= $inscription['id'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour afficher les détails -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Détails de l'inscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="inscriptionDetails">
                    Chargement en cours...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gérer le clic sur le bouton Voir les détails
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const inscriptionId = this.getAttribute('data-id');
                const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                
                // Charger les détails via AJAX
                fetch(`get_inscription_details.php?id=${inscriptionId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const inscription = data.inscription;
                            const dateNaissance = new Date(inscription.date_naissance).toLocaleDateString('fr-FR');
                            const dateInscription = new Date(inscription.date_inscription).toLocaleString('fr-FR');
                            
                            let html = `
                                <div class="row mb-4">
                                    <div class="col-12 text-center">
                                        <div class="bg-primary bg-opacity-10 p-4 rounded-3">
                                            <h4 class="mb-0">${inscription.prenom} ${inscription.nom}</h4>
                                            <div class="text-muted">Inscrit(e) le ${dateInscription}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-user-circle me-2"></i>Informations personnelles
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex mb-3">
                                                    <div class="me-3">
                                                        <i class="fas fa-calendar-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Date de naissance</h6>
                                                        <p class="text-muted mb-0">${dateNaissance}</p>
                                                        <p class="text-muted small">${inscription.lieu_naissance}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-3">
                                                    <div class="me-3">
                                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Adresse</h6>
                                                        <p class="text-muted">${inscription.adresse}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-phone text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Contact</h6>
                                                        <p class="text-muted mb-0">${inscription.telephone}</p>
                                                        <p class="text-muted mb-0">${inscription.email}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-users me-2"></i>Famille
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex mb-3">
                                                    <div class="me-3">
                                                        <i class="fas fa-male text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Père</h6>
                                                        <p class="text-muted">${inscription.pere || 'Non renseigné'}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex mb-3">
                                                    <div class="me-3">
                                                        <i class="fas fa-female text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Mère</h6>
                                                        <p class="text-muted">${inscription.mere || 'Non renseigné'}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="me-3">
                                                        <i class="fas fa-user-tie text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">Tuteur</h6>
                                                        <p class="text-muted">${inscription.tuteur || 'Non renseigné'}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-graduation-cap me-2"></i>Scolarité
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                                                <i class="fas fa-school text-primary fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">Centre</h6>
                                                                <p class="text-muted mb-0">${inscription.centre_designation}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                                                <i class="fas fa-layer-group text-success fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">Section</h6>
                                                                <p class="text-muted mb-0">${inscription.section_designation}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            document.getElementById('inscriptionDetails').innerHTML = html;
                        } else {
                            document.getElementById('inscriptionDetails').innerHTML = 
                                '<div class="alert alert-danger">Erreur lors du chargement des détails.</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        document.getElementById('inscriptionDetails').innerHTML = 
                            '<div class="alert alert-danger">Une erreur est survenue lors du chargement des détails.</div>';
                    });
                
                modal.show();
            });
        });
    });
    </script>
</body>
</html>
