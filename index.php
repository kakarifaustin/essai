<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail d'inscription CEM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        .welcome-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
            color: #2c3e50;
            background: linear-gradient(45deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: #28a745;
            border: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .admin-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .admin-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .footer-bg {
            background: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .card-feature {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card-feature:hover {
            transform: translateY(-5px);
        }
        .hero-section {
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23f8f9fa"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>') no-repeat bottom;
            background-size: cover;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-bg hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="welcome-title">
                        <i class="fas fa-graduation-cap me-3"></i>
                        Bienvenue dans le portail d'inscription des apprenants dans les CEM
                    </h1>
                    <p class="lead text-center">Centre d'Enseignement des Métiers - Inscription en ligne</p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='150' height='150' viewBox='0 0 150 150'><circle cx='75' cy='75' r='70' fill='%23ffffff20' stroke='%23ffffff' stroke-width='2'/><text x='75' y='85' text-anchor='middle' fill='%23ffffff' font-size='20' font-family='Arial'>CEM</text></svg>" alt="Logo CEM" class="img-fluid">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="mb-4">Inscrivez-vous dès maintenant</h2>
                <p class="lead mb-4">Rejoignez nos centres de formation et développez vos compétences professionnelles</p>
                
                <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                    <a href="inscription.php" class="btn btn-custom btn-lg">
                        <i class="fas fa-user-plus me-2"></i>
                        S'inscrire
                    </a>
                </div>
                
                <div class="mt-4">
                    <a href="admin/login.php" class="admin-link">
                        <i class="fas fa-cog me-1"></i>
                        Administratif
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mt-5">
            <div class="col-md-4 mb-4">
                <div class="card card-feature h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Formation Technique</h5>
                        <p class="card-text">Formations spécialisées dans les métiers techniques et industriels</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-feature h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-certificate fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Certification</h5>
                        <p class="card-text">Obtenez des certifications reconnues par l'État</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card card-feature h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Insertion Professionnelle</h5>
                        <p class="card-text">Accompagnement vers l'emploi et l'entrepreneuriat</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-school me-2"></i>Ministère de l'Éducation Nationale</h5>
                    <p>Direction de la Recherche Scientifique</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5><i class="fas fa-building me-2"></i>Partenaires</h5>
                    <p>Chambre Fédérale de Commerce et d'Industrie du Burundi</p>
                    <p>Enabel - Coopération belge au développement</p>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; 2025 CEM - Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
