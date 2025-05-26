<?php
    require 'includes/database.php';
    require 'includes/functions.php';

    session_start(); // Démarrer la session

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $mot_de_passe = $_POST['mot_de_passe'];

        if (empty($email)) {
            $errors[] = "L'adresse e-mail est requise.";
        }
        if (empty($mot_de_passe)) {
            $errors[] = "Le mot de passe est requis.";
        }

        if (empty($errors)) {
            $utilisateur = verifierUtilisateur($pdo, $email, $mot_de_passe);

            if ($utilisateur) {
                // Authentification réussie
                $_SESSION['utilisateur_id'] = $utilisateur['id_utilisateur'];
                $_SESSION['utilisateur_role'] = $utilisateur['role'];
                $_SESSION['utilisateur_nom'] = $utilisateur['nom'];
                $_SESSION['utilisateur_prenom'] = $utilisateur['prenom'];

                // Rediriger vers la page appropriée en fonction du rôle
                if ($utilisateur['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: utilisateur/index.php");
                }
                exit();
            } else {
                $errors[] = "Identifiants incorrects.";
            }
        }
    }

    include 'includes/header.php';
?>

<main>
    <h1>Connexion</h1>

    <?php if (isset($_GET['inscription_reussie']) && $_GET['inscription_reussie'] == 1): ?>
        <div class="success-message">
            <p>Votre inscription a réussi. Veuillez vous connecter.</p>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <button type="submit" class="button">Se connecter</button>
        <p>Nouveau ici? <a href="inscription.php">Inscrivez-vous</a>.</p>
    </form>
</main>

<?php
    include 'includes/footer.php';
?>