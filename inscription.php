<?php
    require 'includes/database.php';
    require 'includes/functions.php';

    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $mot_de_passe = $_POST['mot_de_passe'];
        $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'];

        // Validation des champs
        if (empty($nom)) {
            $errors[] = "Le nom est requis.";
        }
        if (empty($prenom)) {
            $errors[] = "Le prénom est requis.";
        }
        if (empty($email)) {
            $errors[] = "L'adresse e-mail est requise.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse e-mail n'est pas valide.";
        }
        if (empty($mot_de_passe)) {
            $errors[] = "Le mot de passe est requis.";
        } elseif (strlen($mot_de_passe) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }
        if ($mot_de_passe !== $confirmation_mot_de_passe) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        // Vérifier si l'e-mail existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "Cette adresse e-mail est déjà enregistrée.";
        }

        if (empty($errors)) {
            // Enregistrer l'utilisateur
            if (enregistrerUtilisateur($pdo, $nom, $prenom, $email, $mot_de_passe)) {
                $success = true;
                // Rediriger vers la page de connexion après l'inscription
                header("Location: connexion.php?inscription_reussie=1");
                exit();
            } else {
                $errors[] = "Une erreur s'est produite lors de l'enregistrement.";
            }
        }
    }

    include 'includes/header.php';
?>

<main>
    <h1>Inscription</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-message">
            <p>Votre compte a été créé avec succès. Vous pouvez maintenant vous <a href="connexion.php">connecter</a>.</p>
        </div>
    <?php else: ?>
        <form method="post">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe:</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="form-group">
                <label for="confirmation_mot_de_passe">Confirmer le mot de passe:</label>
                <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" required>
            </div>
            <button type="submit" class="button">S'inscrire</button>
        </form>
    <?php endif; ?>
</main>

<?php
    include 'includes/footer.php';
?>