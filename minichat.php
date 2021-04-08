<?php 
?>

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
            <?php
                if (empty($_COOKIE['pseudo'])) {
                    echo '<label for="pseudo">Votre pseudo : </label><br><input type="text" name="pseudo" id="pseudo"/>';
                } else {
                    echo '<label for="pseudo">Votre pseudo : </label><br><input type="text" name="pseudo" value="' . $_COOKIE['pseudo'] . '" id="pseudo"/>';
                }
            ?>
            <br><label for="message">Votre message : </label><br><input type="text" name="message" id="message" />
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

    // Version simplifiée du code 

/*     $req = $bdd->prepare('SELECT * FROM minichat ORDER BY id DESC LIMIT ?, 10');
    $rangDepart;
    settype($rangDepart, "integer");
        if (empty($_GET['page'])) {
            $rangDepart = 0;
        } else {
            $rangDepart = (($_GET['page'] - 1)*10);
        }
    $req->execute(array($rangDepart));

    while($donnees = $req->fetch()){
        echo '<li><strong>' . $donnees['pseudo'] . ': </strong>' . $donnees['message'] . '</li>'; 
    }
    $req->closeCursor();
 */


    if (empty($_GET['page'])) {
        $req = $bdd->query('SELECT * FROM minichat ORDER BY id DESC LIMIT 0, 10');

        while($donnees = $req->fetch()){
            echo '<li><strong>' . $donnees['pseudo'] . ': </strong>' . $donnees['message'] . '</li>'; 
        }

        $req->closeCursor();
    } else {
        $rangDepart = (($_GET['page'] - 1)*10);
        settype($rangDepart, "integer");
        $req = $bdd->prepare('SELECT * FROM minichat ORDER BY id DESC LIMIT :page, 10');
        $req->bindParam(':page', $rangDepart, PDO::PARAM_INT);
        $req->execute();

        while($donnees = $req->fetch()){
            echo '<li><strong>' . $donnees['pseudo'] . ': </strong>' . $donnees['message'] . '</li>'; 
        }

        $req->closeCursor();
    }


    $messageCount = $bdd->query('SELECT COUNT(*) AS nbMessages FROM minichat');
    $tabNbMessages = $messageCount->fetch();
    $nbMessages = $tabNbMessages['nbMessages'];
    $messageCount->closeCursor();

    
    echo '<h2>Voir les anciens messages</h2>';
    echo '<p>'. $nbMessages . ' messages disponibles.</p>';
    ?>
    <form action="minichat.php" method="get">
        <label for="page">Quelle page souhaitez-vous consulter ?</label>
        <select name="page" id="page">
            <?php
                
                $numeroPage = 1;
                while($numeroPage <= $nbMessages / 10 ) {
                    echo '<option value="' . $numeroPage  . '">' . $numeroPage . '</ option>';
                    $numeroPage++;
                }
                if ($nbMessages % 10 > 0) {
                    echo '<option value="' . $numeroPage  . '">' . $numeroPage . '</ option>';
                }
            ?>
        </select>
        <input type="submit" name="valider" value="Valider" />
    </form>
    <?php
    ?>
    </body>
    </html>
<?php
?>


<!-- TO DO / Idées 
Vérifier la saisie dans l'URL pour protéger $_GET
Retirer l'input submit du second formulaire
Proposer une pagination avec la liste des pages directement visible (ancres)
Factoriser la $req

Mettre en place un front
-->