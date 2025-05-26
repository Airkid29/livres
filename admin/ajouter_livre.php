<?php
    session_start();
    if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'admin') {
        header("Location: ../connexion.php");
        exit();
    }

    require '../includes/database.php';
    require '../includes/functions.php';

    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = trim($_POST['titre']);
        $auteur = trim($_POST['auteur']);
        $isbn = trim($_POST['isbn']);
        $date_publication = empty($_POST['date_publication']) ? null : $_POST['date_publication'];
        $nombre_exemplaires = intval($_POST['nombre_exemplaires']);

        if (empty($titre)) {
            $errors[] = "Le titre est requis.";
        }
        if (empty($auteur)) {
            $errors[] = "L'auteur est requis.";
        }
        if (!empty($isbn)) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM livres WHERE isbn = ?");
            $stmt->execute([$isbn]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "Cet ISBN existe déjà.";
            }
        }
        if ($nombre_exemplaires < 1) {
            $errors[] = "Le nombre d'exemplaires doit être au moins 1.";
        }

        if (empty($errors)) {
            if (ajouterLivre($pdo, $titre, $auteur, $isbn, $date_publication, $nombre_exemplaires)) {
                $success = true;
                $message = "<div class='success-message'>Le livre a été ajouté avec succès. <a href='gestion_livres.php'>Retour à la gestion des livres</a></div>";
            } else {
                $errors[] = "Erreur lors de l'ajout du livre.";
            }
        }
    }

    include '../includes/header.php';
?>

<main>
    <h1>Ajouter un nouveau livre</h1>

    <?php echo $message ?? ''; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!$success): ?>
        <form method="post">
            <div class="form-group">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="auteur">Auteur:</label>
                <input type="text" id="auteur" name="auteur" required>
            </div>
            <div class="form-group">
                <label for="isbn">ISBN:</label>
                <input type="text" id="isbn" name="isbn">
            </div>
            <div class="form-group">
                <label for="date_publication">Date de publication:</label>
                <input type="date" id="date_publication" name="date_publication">
            </div>
            <div class="form-group">
                <label for="nombre_exemplaires">Nombre d'exemplaires:</label>
                <input type="number" id="nombre_exemplaires" name="nombre_exemplaires" value="1" min="1" required>
            </div>
            <button type="submit" class="button">Ajouter le livre</button>
        </form>
    <?php endif; ?>
</main>

<?php
    include '../includes/footer.php';
?>