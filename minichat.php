<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Minichat de Romain</title>
    </head>
    <body>
        <h1>Bienvenue sur mon minichat: laissez-moi un message !</h1>
        <p>Vous pouvez me laisser un message grace au formulaire ci-dessous : </p>
        <form action="minichat_post.php" method="POST">
            <input type="text" name="pseudo" />
            <input type="text" name="message" />
            <input type="submit" name="valider" />
        </form>
        <br>
        <h2>Les derniers messages :</h2>
<?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=minichat;chartset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch(Exception $e) {
        die('Erreur :' . $e->getMessage());
    }
    
    $req = $bdd->query('SELECT * FROM minichat ORDER BY id LIMIT 0, 10');
    while($donnees = $req->fetch()){
        echo '<li><strong>' . $donnees['pseudo'] . ': </strong>' . $donnees['message'] . '</li>'; 
    }

    $req->closeCursor();
?>
        </body>
        </html>
<?php
?>