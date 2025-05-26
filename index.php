<?php
    require 'includes/database.php';
    require 'includes/functions.php';

    // Récupérer la liste des livres disponibles
    $livres = listerLivres($pdo);

    // Inclure l'en-tête
    include 'includes/header.php';
?>

<main>
    <h1>Bienvenue à la Bibliothèque en Ligne</h1>

    <?php if (empty($livres)): ?>
        <p>Aucun livre n'est actuellement disponible.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($livres as $livre): ?>
                <li>
                    <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                    <p>Auteur: <?php echo htmlspecialchars($livre['auteur']); ?></p>
                    <p>ISBN: <?php echo htmlspecialchars($livre['isbn']); ?></p>
                    <p>Exemplaires disponibles: <?php echo htmlspecialchars($livre['nombre_disponibles']); ?></p>
                    </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php
    // Inclure le pied de page
    include 'includes/footer.php';
?>