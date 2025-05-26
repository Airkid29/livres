<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id'])) {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_livre = $_POST['id_livre'];
        $date_retour_prevue = $_POST['date_retour_prevue'];
        $id_utilisateur = $_SESSION['utilisateur_id'];

        if (estLivreDisponible($pdo, $id_livre)) {
            if (emprunterLivre($pdo, $id_livre, $id_utilisateur, $date_retour_prevue)) {
                $message = "<div class='success-message'>L'emprunt a été enregistré avec succès.</div>";
            } else {
                $message = "<div class='error-message'>Erreur lors de l'enregistrement de l'emprunt.</div>";
            }
        } else {
            $message = "<div class='error-message'>Ce livre n'est pas disponible pour l'emprunt.</div>";
        }
    }

    $livres_disponibles = listerLivres($pdo); // Vous pourriez filtrer pour afficher seulement les livres avec nombre_disponibles > 0

    include '../includes/header.php';
?>

<main>
    <h1>Emprunter un livre</h1>

    <?php echo $message; ?>

    <h2>Livres disponibles</h2>
    <?php if (empty($livres_disponibles)): ?>
        <p>Aucun livre n'est actuellement disponible pour l'emprunt.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($livres_disponibles as $livre): ?>
                <?php if ($livre['nombre_disponibles'] > 0): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                        <p>Auteur: <?php echo htmlspecialchars($livre['auteur']); ?></p>
                        <p>ISBN: <?php echo htmlspecialchars($livre['isbn']); ?></p>
                        <form method="post">
                            <input type="hidden" name="id_livre" value="<?php echo htmlspecialchars($livre['id_livre']); ?>">
                            <div class="form-group">
                                <label for="date_retour_prevue">Date de retour prévue:</label>
                                <input type="date" id="date_retour_prevue" name="date_retour_prevue" required>
                            </div>
                            <button type="submit" class="button">Emprunter ce livre</button>
                        </form>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php
    include '../includes/footer.php';
?>