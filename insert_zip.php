<?php
// Connexion à la base de données MariaDB
$conn = new mysqli("db", "root", "example", "jeux-cyber");

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Créer la table si elle n'existe pas déjà
$sql = "CREATE TABLE IF NOT EXISTS content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255),
    contenu LONGBLOB
)";
if ($conn->query($sql) !== TRUE) {
    echo "Erreur lors de la création de la table content: " . $conn->error . "<br>";
}

// Fonction pour insérer un fichier dans la base de données
function insererFichier($conn, $cheminFichier) {
    $nomFichier = basename($cheminFichier);
    $contenu = file_get_contents($cheminFichier); // Lire le contenu du fichier

    if ($contenu === false) {
        echo "Erreur lors de la lecture du fichier '$nomFichier'<br>";
        return;
    }

    // Préparer la requête d'insertion
    $sql = "INSERT INTO content (nom, contenu) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $null = NULL;
    $stmt->bind_param("sb", $nomFichier, $null);

    // Associer le contenu du fichier à la requête
    $stmt->send_long_data(1, $contenu);

    // Exécuter la requête
    if ($stmt->execute() === TRUE) {
        echo "Fichier '$nomFichier' inséré avec succès dans la table content<br>";
    } else {
        echo "Erreur lors de l'insertion du fichier '$nomFichier' dans la table content: " . $stmt->error . "<br>";
    }
}

// Insérer les fichiers image dans la base de données
insererFichier($conn, __DIR__ . '/good.png');

// Fermer la connexion
$conn->close();
?>
