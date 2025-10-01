<?php
require_once '../config.php';
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

// Vérifier que l'ID de l'inscription est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID d\'inscription invalide']);
    exit;
}

$id_inscription = (int)$_GET['id'];

try {
    // Récupérer les détails de l'inscription
    $stmt = $pdo->prepare("
        SELECT 
            i.*, 
            c.designation as centre_designation, 
            s.designation as section_designation
        FROM inscription i
        JOIN section s ON i.id_section = s.id
        JOIN centre c ON s.id_centre = c.id
        WHERE i.id = ?
    ");
    
    $stmt->execute([$id_inscription]);
    $inscription = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($inscription) {
        echo json_encode([
            'success' => true,
            'inscription' => $inscription
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Inscription non trouvée'
        ]);
    }
} catch (PDOException $e) {
    error_log('Erreur lors de la récupération des détails de l\'inscription: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la récupération des détails de l\'inscription'
    ]);
}
