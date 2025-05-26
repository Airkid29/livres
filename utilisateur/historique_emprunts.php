<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id'])) {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $id_utilisateur = $_SESSION['utilisateur_id'];
    $historique_emprunts = getEmpruntsUtilisateur($pdo, $id_utilisateur, true); // Le 'true' pourrait indiquer de récupérer aussi les emprunts retournés

    include '../includes/header.php';
?>

<main>
    <h1>Historique de mes emprunts</h1>

    <?php if (empty($historique_emprunts)): ?>
        <p>Vous n'avez aucun historique d'emprunt.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($historique_emprunts as $emprunt): ?>
                <li>
                    <h3><?php echo htmlspecialchars($emprunt['titre']); ?></h3>
                    <p>Auteur: <?php echo htmlspecialchars($emprunt['auteur']); ?></p>
                    <p>Date d'emprunt: <?php echo htmlspecialchars($emprunt['date_emprunt']); ?></p>
                    <p>Date de retour prévue: <?php echo htmlspecialchars($emprunt['date_retour_prevue']); ?></p>
                    <?php if ($emprunt['date_retour_effective']): ?>
                        <p>Date de retour effective: <?php echo htmlspecialchars($emprunt['date_retour_effective']); ?></p>
                    <?php else: ?>
                        <p>Statut: En cours</p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php
    include '../includes/footer.php';
?>