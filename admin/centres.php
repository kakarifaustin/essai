<?php
require_once '../config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$error = '';

// Traitement des actions
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $designation = trim($_POST['designation'] ?? '');
        if ($designation) {
            $stmt = $pdo->prepare("INSERT INTO centre (designation) VALUES (?)");
            if ($stmt->execute([$designation])) {
                $message = 'Centre créé avec succès';
            } else {
                $error = 'Erreur lors de la création du centre';
            }
        } else {
            $error = 'Veuillez saisir une désignation';
        }
    } elseif ($action === 'update') {
        $id = $_POST['id'] ?? '';
        $designation = trim($_POST['designation'] ?? '');
        if ($id && $designation) {
            $stmt = $pdo->prepare("UPDATE centre SET designation = ? WHERE id = ?");
            if ($stmt->execute([$designation, $id])) {
                $message = 'Centre modifié avec succès';
            } else {
                $error = 'Erreur lors de la modification du centre';
            }
        }
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM centre WHERE id = ?");
            if ($stmt->execute([$id])) {
                $message = 'Centre supprimé avec succès';
            } else {
                $error = 'Erreur lors de la suppression du centre';
            }
        }
    }
}

// Récupérer tous les centres
$stmt = $pdo->query("SELECT * FROM centre ORDER BY designation");
$centres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des centres - Administration CEM</title>
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
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-center mb-4">
                        <i class="fas fa-cogs me-2"></i>Admin CEM
                    </h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a class="nav-link active" href="centres.php">
                            <i class="fas fa-building me-2"></i>Gestion des centres
                        </a>
                        <a class="nav-link" href="sections.php">
                            <i class="fas fa-list me-2"></i>Gestion des sections
                        </a>
                        <a class="nav-link" href="inscriptions.php">
                            <i class="fas fa-users me-2"></i>Inscriptions
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-0">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-building me-2"></i>Gestion des centres</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fas fa-plus me-2"></i>Créer un centre
                        </button>
                    </div>

                    <?php if ($message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Liste des centres -->
                    <div class="card">
                        <div class="card-body">
                            <?php if (empty($centres)): ?>
                                <p class="text-muted text-center">Aucun centre enregistré</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Désignation</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($centres as $centre): ?>
                                                <tr>
                                                    <td><?= $centre['id'] ?></td>
                                                    <td><?= htmlspecialchars($centre['designation']) ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary me-2" 
                                                                onclick="editCentre(<?= $centre['id'] ?>, '<?= htmlspecialchars($centre['designation'], ENT_QUOTES) ?>')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteCentre(<?= $centre['id'] ?>, '<?= htmlspecialchars($centre['designation'], ENT_QUOTES) ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Créer -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Créer un nouveau centre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label for="designation" class="form-label">Désignation du centre</label>
                            <input type="text" class="form-control" id="designation" name="designation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modifier -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le centre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_designation" class="form-label">Désignation du centre</label>
                            <input type="text" class="form-control" id="edit_designation" name="designation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Supprimer -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer le centre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <p>Êtes-vous sûr de vouloir supprimer le centre "<span id="delete_name"></span>" ?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Cette action supprimera également toutes les sections et inscriptions associées.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editCentre(id, designation) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_designation').value = designation;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function deleteCentre(id, designation) {
            document.getElementById('delete_id').value = id;
            document.getElementById('delete_name').textContent = designation;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html>
