<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id'])) {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $message = '';
    $id_utilisateur = $_SESSION['utilisateur_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_emprunt_retour'])) {
        $id_emprunt = $_POST['id_emprunt_retour'];
        if (retournerLivre($pdo, $id_emprunt)) {
            $message = "<div class='success-message'>Le livre a été marqué comme retourné.</div>";
        } else {
            $message = "<div class='error-message'>Erreur lors du traitement du retour.</div>";
        }
    }

    $emprunts_en_cours = getEmpruntsUtilisateur($pdo, $id_utilisateur);

    include '../includes/header.php';
?>

<main>
    <h1>Retourner un livre</h1>

    <?php echo $message; ?>

    <h2>Livres actuellement empruntés</h2>
    <?php if (empty($emprunts_en_cours)): ?>
        <p>Vous n'avez aucun livre emprunté actuellement.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($emprunts_en_cours as $emprunt): ?>
                <li>
                    <h3><?php echo htmlspecialchars($emprunt['titre']); ?></h3>
                    <p>Auteur: <?php echo htmlspecialchars($emprunt['auteur']); ?></p>
                    <p>Date d'emprunt: <?php echo htmlspecialchars($emprunt['date_emprunt']); ?></p>
                    <p>Date de retour prévue: <?php echo htmlspecialchars($emprunt['date_retour_prevue']); ?></p>
                    <form method="post">
                        <input type="hidden" name="id_emprunt_retour" value="<?php echo htmlspecialchars($emprunt['id_emprunt']); ?>">
                        <button type="submit" class="button">Retourner ce livre</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php
    include '../includes/footer.php';
?>