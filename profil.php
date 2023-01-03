<?php

    include 'User-pdo.php';

    if(session_id() == '') {

        session_start();
    }

//Je crée ma classe User et grâce à la $_SESSION['userID'] ma requête sql du dessous me permet de récupérer les infos de l'utilisateur connecté.
    $user = new Userpdo();
    $user->get_user_info();
    
    if(isset($_POST['profile_change'])) {

        $user->update_profile();
        $user->get_user_info();
//Pour prendre en compte les informations modifiées, s'il y en a, je suis obligé de récupérer une 2ème fois les infos après que la modif ait été prise en compte.
    }

    if(isset($_POST['confirm_delete'])) {

        $user->delete();
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="formulaires.css" rel="stylesheet">
    <link href="index.css" rel = "stylesheet">
    <link href="header.css" rel = "stylesheet">
    <link href="footer.css" rel = "stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Profil</title>
</head>
<body>

    <?php include 'header.php'?>

    <main>

        <form method="post" class ="formulaire">
        
        <?php if(isset($_SESSION['userID']) && isset($_POST['delete_profile'])): ?>
            
            <h3>Vous êtes sur le point de supprimer votre profil, ainsi que vos réservations.</h3>

            <button type="submit" id="cancel_delete" name="cancel_delete">Annuler</button>

            <button type="submit" id="confirm_delete" name="confirm_delete">Confirmer</button>


        <?php elseif(isset($_SESSION['userID']) || isset($_POST['cancel_delete'])): ?>

            <h2>MODIFICATION DE PROFIL</h2>

            <h3>
            <?php 
                    if(isset($_POST['profile_change'])) {

                        echo $user->message;
                    }
                ?>
            </h3>

                <label for="login">Pseudo* : </label>
                <input type="text" name="login" value="<?= $user->login ?>" >
                <br>   
                
                <label for="email">Email* :</label>
                <input type="text" name="email" value="<?= $user->email ?>">
                <br>

                <label for="firstname">Prénom:</label>
                <input type="text" name="firstname" value="<?= $user->firstname ?>">
                <br>

                <label for="lastname">Nom:</label>
                <input type="text" name="lastname" value="<?= $user->lastname ?>">
                <br>

                <label for="new_mdp">Nouveau mot de passe* : </label>
                <input type="password" name="new_mdp">
                <br>

                <label for="new_mdp_confirm">Confirmez votre nouveau mot de passe*</label>
                <input type="password" name="new_mdp_confirm">
                <br>

                <label for="mdp">Tapez votre ancien mot de passe pour confirmer les changements*</label>
                <input type="password" name="mdp">
                <br>

                <button type="submit" name="profile_change">Modifier</button>
            
            </form>

            <form method="post" class="formulaire">
                    <button type="submit" id="delete_profile" name="delete_profile">Supprimer mon compte</button>
        

        <?php elseif(!isset($_SESSION['userID'])) : ?>

            <h3> Pas de compte, pas de profil ! </h3>

        <?php endif ?>
        
        </form>

    </main>

    <?php include 'footer.php' ?>

</body>
</html>