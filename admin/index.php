<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    include '../includes/header.php';
?>

<main>
    <h1>Tableau de bord Administrateur</h1>

    <ul>
        <li><a href="gestion_livres.php">Gestion des livres</a></li>
        <li><a href="gestion_utilisateurs.php">Gestion des utilisateurs</a></li>
        <li><a href="gestion_emprunts.php">Gestion des emprunts</a></li>
    </ul>
</main>

<?php
    include '../includes/footer.php';
?>