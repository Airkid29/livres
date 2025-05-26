<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_emprunt_retour_admin'])) {
        $id_emprunt = $_POST['id_emprunt_retour_admin'];
        if (retournerLivre($pdo, $id_emprunt)) {
            $message = "<div class='success-message'>L'emprunt a été marqué comme retourné.</div>";
        } else {
            $message = "<div class='error-message'>Erreur lors du traitement du retour.</div>";
        }
    }

    $emprunts = listerEmpruntsEnCours($pdo); // Fonction à créer dans functions.php

    include '../includes/header.php';
?>

<main>
    <h1>Gestion de tous les emprunts</h1>

    <?php echo $message; ?>

    <?php if (empty($emprunts)): ?>
        <p>Aucun emprunt en cours.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID Emprunt</th>
                    <th>Utilisateur</th>
                    <th>Livre</th>
                    <th>Date Emprunt</th>
                    <th>Retour Prévu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprunts as $emprunt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($emprunt['id_emprunt']); ?></td>
                        <td><?php echo htmlspecialchars($emprunt['prenom_utilisateur']) . ' ' . htmlspecialchars($emprunt['nom_utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($emprunt['titre_livre']); ?></td>
                        <td><?php echo htmlspecialchars($emprunt['date_emprunt']); ?></td>
                        <td><?php echo htmlspecialchars($emprunt['date_retour_prevue']); ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id_emprunt_retour_admin" value="<?php echo htmlspecialchars($emprunt['id_emprunt']); ?>">
                                <button type="submit" class="button small">Marquer comme retourné</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php
    include '../includes/footer.php';
?>