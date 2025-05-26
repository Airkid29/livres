<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliothèque en Ligne</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Bibliothèque en Ligne</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php if (isset($_SESSION['utilisateur_id'])): ?>
                    <?php if ($_SESSION['utilisateur_role'] === 'admin'): ?>
                        <li><a href="admin/index.php">Admin</a></li>
                    <?php else: ?>
                        <li><a href="utilisateur/index.php">Mon espace</a></li>
                    <?php endif; ?>
                    <li><a href="deconnexion.php">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="connexion.php">Connexion</a></li>
                    <li><a href="inscription.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <div class="container">