<?php
require_once '../config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer les statistiques
$stmt = $pdo->query("SELECT COUNT(*) as total_centres FROM centre");
$total_centres = $stmt->fetch()['total_centres'];

$stmt = $pdo->query("SELECT COUNT(*) as total_sections FROM section");
$total_sections = $stmt->fetch()['total_sections'];

$stmt = $pdo->query("SELECT COUNT(*) as total_inscriptions FROM inscription");
$total_inscriptions = $stmt->fetch()['total_inscriptions'];

// Récupérer les inscriptions récentes
$stmt = $pdo->query("
    SELECT i.*, s.designation as section_name, c.designation as centre_name 
    FROM inscription i 
    JOIN section s ON i.id_section = s.id 
    JOIN centre c ON s.id_centre = c.id 
    ORDER BY i.date_inscription DESC 
    LIMIT 5
");
$recent_inscriptions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Administration CEM</title>
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
        .stat-card {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .stat-card.blue {
            background: linear-gradient(135deg, #007bff, #6610f2);
        }
        .stat-card.orange {
            background: linear-gradient(135deg, #fd7e14, #e83e8c);
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
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                        </a>
                        <a class="nav-link" href="centres.php">
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
                        <h2>Tableau de bord</h2>
                        <span class="text-muted">Bienvenue, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="stat-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?= $total_centres ?></h3>
                                        <p class="mb-0">Centres</p>
                                    </div>
                                    <i class="fas fa-building fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card blue">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?= $total_sections ?></h3>
                                        <p class="mb-0">Sections</p>
                                    </div>
                                    <i class="fas fa-list fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card orange">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><?= $total_inscriptions ?></h3>
                                        <p class="mb-0">Inscriptions</p>
                                    </div>
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Inscriptions -->
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-clock me-2"></i>Inscriptions récentes
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recent_inscriptions)): ?>
                                <p class="text-muted text-center">Aucune inscription pour le moment</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Nom complet</th>
                                                <th>Centre</th>
                                                <th>Section</th>
                                                <th>Date d'inscription</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_inscriptions as $inscription): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($inscription['nom'] . ' ' . $inscription['prenom']) ?></td>
                                                    <td><?= htmlspecialchars($inscription['centre_name']) ?></td>
                                                    <td><?= htmlspecialchars($inscription['section_name']) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($inscription['date_inscription'])) ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
