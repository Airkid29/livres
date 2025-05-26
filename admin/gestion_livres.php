<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $message = '';

    // Suppression d'un livre
    if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
        $id_livre_supprimer = $_GET['supprimer'];
        if (supprimerLivre($pdo, $id_livre_supprimer)) {
            $message = "<div class='success-message'>Le livre a été supprimé avec succès.</div>";
        } else {
            $message = "<div class='error-message'>Erreur lors de la suppression du livre.</div>";
        }
    }

    $livres = listerLivres($pdo);

    include '../includes/header.php';
?>

<main>
    <h1>Gestion des livres</h1>

    <?php echo $message; ?>

    <p><a href="ajouter_livre.php" class="button">Ajouter un nouveau livre</a></p>

    <?php if (empty($livres)): ?>
        <p>Aucun livre n'est enregistré dans la bibliothèque.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>ISBN</th>
                    <th>Exemplaires</th>
                    <th>Disponibles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($livre['id_livre']); ?></td>
                        <td><?php echo htmlspecialchars($livre['titre']); ?></td>
                        <td><?php echo htmlspecialchars($livre['auteur']); ?></td>
                        <td><?php echo htmlspecialchars($livre['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($livre['nombre_exemplaires']); ?></td>
                        <td><?php echo htmlspecialchars($livre['nombre_disponibles']); ?></td>
                        <td>
                            <a href="modifier_livre.php?id=<?php echo htmlspecialchars($livre['id_livre']); ?>" class="button small">Modifier</a>
                            <a href="gestion_livres.php?supprimer=<?php echo htmlspecialchars($livre['id_livre']); ?>" class="button small red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')">Supprimer</a>
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