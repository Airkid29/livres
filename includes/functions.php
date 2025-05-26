<?php

function listerLivres(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM livres");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function enregistrerUtilisateur(PDO $pdo, string $nom, string $prenom, string $email, string $mot_de_passe, string $role = 'eleve'): bool {
    $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $email, $hashedPassword, $role]);
}

function verifierUtilisateur(PDO $pdo, string $email, string $mot_de_passe): ?array {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        return $utilisateur;
    } else {
        return null;
    }
}




// ... (fonctions précédentes : listerLivres, enregistrerUtilisateur, verifierUtilisateur) ...

function estLivreDisponible(PDO $pdo, int $id_livre): bool {
    $stmt = $pdo->prepare("SELECT nombre_disponibles FROM livres WHERE id_livre = ?");
    $stmt->execute([$id_livre]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['nombre_disponibles'] > 0;
}

function emprunterLivre(PDO $pdo, int $id_livre, int $id_utilisateur, string $date_retour_prevue): bool {
    $pdo->beginTransaction();
    try {
        // Créer l'enregistrement d'emprunt
        $stmtEmprunt = $pdo->prepare("INSERT INTO emprunts (id_livre, id_utilisateur, date_retour_prevue) VALUES (?, ?, ?)");
        $stmtEmprunt->execute([$id_livre, $id_utilisateur, $date_retour_prevue]);

        // Décrémenter le nombre de livres disponibles
        $stmtLivre = $pdo->prepare("UPDATE livres SET nombre_disponibles = nombre_disponibles - 1 WHERE id_livre = ? AND nombre_disponibles > 0");
        $stmtLivre->execute([$id_livre]);

        if ($stmtLivre->rowCount() > 0) {
            $pdo->commit();
            return true;
        } else {
            $pdo->rollBack();
            return false;
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

function retournerLivre(PDO $pdo, int $id_emprunt): bool {
    $pdo->beginTransaction();
    try {
        // Mettre à jour la date de retour effective dans la table emprunts
        $stmtEmprunt = $pdo->prepare("UPDATE emprunts SET date_retour_effective = CURRENT_TIMESTAMP WHERE id_emprunt = ? AND date_retour_effective IS NULL");
        $stmtEmprunt->execute([$id_emprunt]);

        if ($stmtEmprunt->rowCount() > 0) {
            // Incrémenter le nombre de livres disponibles
            $stmtLivre = $pdo->prepare("UPDATE livres SET nombre_disponibles = nombre_disponibles + 1 WHERE id_livre = (SELECT id_livre FROM emprunts WHERE id_emprunt = ?)");
            $stmtLivre->execute([$id_emprunt]);
            $pdo->commit();
            return true;
        } else {
            $pdo->rollBack();
            return false;
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

function getEmpruntsUtilisateur(PDO $pdo, int $id_utilisateur, bool $historique = false): array {
    $sql = "SELECT e.*, l.titre, l.auteur FROM emprunts e JOIN livres l ON e.id_livre = l.id_livre WHERE e.id_utilisateur = ?";
    if (!$historique) {
        $sql .= " AND e.date_retour_effective IS NULL";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_utilisateur]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// ... (fonctions précédentes) ...

function ajouterLivre(PDO $pdo, string $titre, string $auteur, ?string $isbn, ?string $date_publication, int $nombre_exemplaires): bool {
    $stmt = $pdo->prepare("INSERT INTO livres (titre, auteur, isbn, date_publication, nombre_exemplaires, nombre_disponibles) VALUES (?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$titre, $auteur, $isbn, $date_publication, $nombre_exemplaires, $nombre_exemplaires]);
}

function supprimerLivre(PDO $pdo, int $id_livre): bool {
    $stmt = $pdo->prepare("DELETE FROM livres WHERE id_livre = ?");
    return $stmt->execute([$id_livre]);
}

function modifierLivre(PDO $pdo, int $id_livre, string $titre, string $auteur, ?string $isbn, ?string $date_publication, int $nombre_exemplaires): bool {
    $stmt = $pdo->prepare("UPDATE livres SET titre = ?, auteur = ?, isbn = ?, date_publication = ?, nombre_exemplaires = ? WHERE id_livre = ?");
    return $stmt->execute([$titre, $auteur, $isbn, $date_publication, $nombre_exemplaires, $id_livre]);
}

function getLivreDetails(PDO $pdo, int $id_livre): ?array {
    $stmt = $pdo->prepare("SELECT * FROM livres WHERE id_livre = ?");
    $stmt->execute([$id_livre]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function listerUtilisateurs(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM utilisateurs");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function supprimerUtilisateur(PDO $pdo, int $id_utilisateur): bool {
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
    return $stmt->execute([$id_utilisateur]);
}

function modifierUtilisateurRole(PDO $pdo, int $id_utilisateur, string $role): bool {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET role = ? WHERE id_utilisateur = ?");
    return $stmt->execute([$role, $id_utilisateur]);
}

function listerEmpruntsEnCours(PDO $pdo): array {
    $sql = "SELECT e.*, l.titre AS titre_livre, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur
            FROM emprunts e
            JOIN livres l ON e.id_livre = l.id_livre
            JOIN utilisateurs u ON e.id_utilisateur = u.id_utilisateur
            WHERE e.date_retour_effective IS NULL";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ... (autres fonctions) ...

// ... (autres fonctions) ...
// Ajoutez ici d'autres fonctions au fur et à mesure du développement...