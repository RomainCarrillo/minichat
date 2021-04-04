<?php
    setcookie('pseudo', $_POST['pseudo'], time() + 120); 
    

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=minichat;chartset=utf8','root','root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    if (!empty($_POST['pseudo']) AND !empty($_POST['message'])) {
        $req = $bdd->prepare('INSERT INTO minichat (pseudo, message) VALUES (:pseudo, :message)');
        $req->execute(array(
            'pseudo' => htmlspecialchars($_POST['pseudo']),
            'message' => htmlspecialchars($_POST['message'])
        ));
        
        $req->closeCursor();

        header('location:minichat.php');
    } else {
        echo '<p><strong>Erreur : Pseudo ou message non défini.</strong></P>';
    }
    
?>