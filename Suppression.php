<?php
require "config.php";

$id = isset($_GET["id"]) ? $_GET["id"] : 0 ;

$query = "SELECT * FROM article WHERE Id_Article = ?";

$stmt = $pdo->prepare($query);

$stmt->execute([$id]);

$article = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($article)) {
    $_SESSION["Message"] = "Article introuvable !";
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $message = isset($_POST["conf"]) ? trim($_POST["conf"]) : "";

    // Vérification que le champ n'est pas vide
    if ($message === "Supprimer"){
        try{
            $sup = "DELETE FROM article WHERE Id_Article = ?";
            $reqSup = $pdo->prepare($sup);
            $retour = $reqSup->execute([$id]);
            if($retour){
                $_SESSION["Confirmation"] = "Message envoyé";
            }
        } catch(PDOException $e){
            echo 'error  est survenue '.$e->getMessage();
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
    <a href="index.php"><button>Retour</button></a>
    <form action="Suppression.php?id=<?= htmlspecialchars($id)?>" method="post">
        <label for="conf">Confirmation :</label>
        <input type="text" id="conf" name="conf" placeholder='Veuillez taper "Supprimer"'/>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>