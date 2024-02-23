<?php
// Connexion à la base de données (à adapter selon votre configuration)
$conn = new mysqli("db", "root", "example", "jeux-cyber");

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupération de la donnée vulnérable
$dataVulnerable = $_POST['data4'];

$sql = "SELECT * FROM content WHERE nom = '$dataVulnerable'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les résultats
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Nom: " . $row["nom"]. "<br>";

        // Les données de l'image sont stockées dans la colonne 'contenu'
        $imageData = $row['contenu'];

        // Vérifiez si l'image existe
        if ($imageData) {
            // Afficher l'image
            // Le type MIME exact (par exemple, 'image/png' ou 'image/jpeg') dépend du format de vos images
            echo '<img src="data:image/png;base64,' . base64_encode($imageData) . '" alt="Image"/><br>';
        }
    }
} else {
    echo "0 résultats";
}

$conn->close();
?>
