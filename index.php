<?php
require "config.php";

// Préparation de la requête
$query = "SELECT * FROM article";

// Éxecution de la requête
$stmt = $pdo->query($query);

// Récupération des données (tableau associatif)
$article = $stmt->fetchAll(PDO::FETCH_ASSOC);

// print_r($auteur);

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = isset($_POST["name"]) ? $_POST["name"] : "" ;
    $prix = isset($_POST["prix"]) ? intval($_POST["prix"]) : "" ;
    $quantite = isset($_POST["quantite"]) ? intval($_POST["quantite"]) : "" ;

    $add = "INSERT INTO article (Designation_Article, Prix_unitaire_Article, Quantite_Article) VALUES (?, ?, ?)";
    $reqAdd = $pdo->prepare($add);

    // Vérification que le champ n'est pas vide
    if ($name !== "" && $prix !== "" && $quantite !== ""){
        // Stockage dans la session
        $retour = $reqAdd->execute(array($name, $prix, $quantite));
        if($retour){
            $_SESSION["Confirmation"] = "Message envoyé";
        }

        // Redirection vers la même page
        header("Location: index.php"); // Les ":" doivent toujours être collés à "Location"
        exit();
    } else {
        // Message d'erreur
        $_SESSION["Message"] = "Veuillez indiquer les bonnes informations !";
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="crudintro.css">
    <title>Liste des auteurs</title>
</head>
<body>
    <form action="index.php" method="post">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required placeholder="Entrez le nom"/>
        <label for="prix">Prix :</label>
        <input type="text" id="prix" name="prix" required placeholder="Entrez le prix"/>
        <label for="quantite">Quantité :</label>
        <input type="text" id="quantite" name="quantite" required placeholder="Entrez la description"/>
        <button type="submit">Envoyer</button>
    </form>
<?php if(!empty($article)): ?>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <!-- PHP -->
        <?php foreach($article as $a):  ?>
            <tr>
                <td><?= htmlspecialchars($a['Id_Article']) ?></td>
                <td><?= htmlspecialchars($a['Designation_Article']) ?></td>
                <td><?= htmlspecialchars($a['Prix_unitaire_Article']) ?></td>
                <td><?= htmlspecialchars($a['Quantite_Article']) ?></td>
            </tr>
        <?php endforeach;  ?>
        </tbody>
    </table>
<?php else:  ?>
    <p>Aucun auteur</p>
<?php endif; ?>

</body>
</html>