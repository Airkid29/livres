<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id'])) {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    include '../includes/header.php';
?>

<main>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['utilisateur_prenom']) . ' ' . htmlspecialchars($_SESSION['utilisateur_nom']); ?></h1>
    <p>Vous êtes connecté en tant que <?php echo htmlspecialchars($_SESSION['utilisateur_role']); ?>.</p>

    <ul>
        <li><a href="emprunter_livre.php">Emprunter un livre</a></li>
        <li><a href="retourner_livre.php">Retourner un livre</a></li>
        <li><a href="historique_emprunts.php">Historique de mes emprunts</a></li>
        </ul>
</main>

<?php
    include '../includes/footer.php';
?>