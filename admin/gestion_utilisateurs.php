<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] === 'eleve' || $_SESSION['utilisateur_role'] === 'enseignant') {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $message = '';

    // Suppression d'un utilisateur
    if (isset($_GET['supprimer_utilisateur']) && is_numeric($_GET['supprimer_utilisateur'])) {
        $id_utilisateur_supprimer = $_GET['supprimer_utilisateur'];
        // Empêcher la suppression de l'administrateur actuel (optionnel, mais prudent)
        if ($id_utilisateur_supprimer != $_SESSION['utilisateur_id']) {
            if (supprimerUtilisateur($pdo, $id_utilisateur_supprimer)) {
                $message = "<div class='success-message'>L'utilisateur a été supprimé avec succès.</div>";
            } else {
                $message = "<div class='error-message'>Erreur lors de la suppression de l'utilisateur.</div>";
            }
        } else {
            $message = "<div class='error-message'>Vous ne pouvez pas supprimer votre propre compte administrateur.</div>";
        }
    }

    // Modification du rôle d'un utilisateur
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_role']) && isset($_POST['utilisateur_id_role']) && isset($_POST['nouveau_role'])) {
        $id_utilisateur_role = $_POST['utilisateur_id_role'];
        $nouveau_role = $_POST['nouveau_role'];
        if (in_array($nouveau_role, ['eleve', 'enseignant', 'admin'])) {
            if (modifierUtilisateurRole($pdo, $id_utilisateur_role, $nouveau_role)) {
                $message = "<div class='success-message'>Le rôle de l'utilisateur a été mis à jour.</div>";
            } else {
                $message = "<div class='error-message'>Erreur lors de la modification du rôle de l'utilisateur.</div>";
            }
        } else {
            $message = "<div class='error-message'>Rôle invalide.</div>";
        }
    }

    $utilisateurs = listerUtilisateurs($pdo);

    include '../includes/header.php';
?>

<main>
    <h1>Gestion des utilisateurs</h1>

    <?php echo $message; ?>

    <?php if (empty($utilisateurs)): ?>
        <p>Aucun utilisateur n'est enregistré.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>E-mail</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['nom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($utilisateur['email']); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="modifier_role">
                                <input type="hidden" name="utilisateur_id_role" value="<?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?>">
                                <select name="nouveau_role">
                                    <option value="eleve" <?php if ($utilisateur['role'] === 'eleve') echo 'selected'; ?>>Élève</option>
                                    <option value="enseignant" <?php if ($utilisateur['role'] === 'enseignant') echo 'selected'; ?>>Enseignant</option>
                                    <option value="admin" <?php if ($utilisateur['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                                <button type="submit" class="button small">Modifier</button>
                            </form>
                        </td>
                        <td>
                            <?php if ($utilisateur['role'] !== 'admin' || $utilisateur['id_utilisateur'] !== $_SESSION['utilisateur_id']): ?>
                                <a href="gestion_utilisateurs.php?supprimer_utilisateur=<?php echo htmlspecialchars($utilisateur['id_utilisateur']); ?>" class="button small red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                            <?php else: ?>
                                <span class="disabled">Supprimer</span>
                            <?php endif; ?>
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