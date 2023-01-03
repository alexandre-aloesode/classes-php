<?php 

    require 'User-pdo.php';

    if(isset($_POST['inscription'])) {

        if(session_id() == '') {
            session_start();          
        }

        $user = new Userpdo();
        $user->register();
    }
    
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="formulaires.css" rel = "stylesheet">
    <link href="index.css" rel = "stylesheet">
    <link href="header.css" rel = "stylesheet">
    <link href="footer.css" rel = "stylesheet">
    <title>Inscription</title>
</head>

<body>

    <?php include 'header.php' ?>

    <main> 

        <form method="post" class ="formulaire">

            <h2>
                <?php

                    if(isset($_POST['inscription']) && isset($_SESSION['user'])) {
                        echo 'Félicitations!';
                    }

                    else {
                        echo 'INSCRIPTION';
                    }

                ?> 
            </h2>

            <h3>
                <?php if(isset($_POST['inscription'])) echo $user->message ?>
            </h3>

            <?php if(!isset($_POST['inscription']) || $user->check !== 1): ?>

                <label for="login">Pseudo* :</label>
                <input type="text" name="login">
                <br>

                <label for="email">Email* :</label>
                <input type="text" name="email">
                <br>

                <label for="firstname">Prénom:</label>
                <input type="text" name="firstname">
                <br>

                <label for="lastname">Nom:</label>
                <input type="text" name="lastname">
                <br>

                <label for="password">Mot de passe* :</label>
                <input type="password" name="mdp">
                <br>
                
                <label for="password">Confirmation mot de passe* :</label>
                <input type="password" name="mdp_confirm">
                <br>

                <button type="submit" name="inscription">S'inscrire</button>

        </form>
            
            <?php endif; ?>
    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>


