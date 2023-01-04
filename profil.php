<?php

    include 'User-pdo.php';

    session_id() == '' ? session_start() : null;

    if(isset($_SESSION['userID'])) {


//Je crée ma classe User et grâce à la $_SESSION['userID'] ma requête sql du dessous me permet de récupérer les infos de l'utilisateur connecté.
        $user = new Userpdo();

        $user->get_user_info();
        
        if(isset($_POST['profile_change'])) {

            $user->update_profile();
            $user->get_user_info();
    //Pour prendre en compte les informations modifiées, s'il y en a, je suis obligé de récupérer une 2ème fois les infos après que la modif ait été prise en compte.
        }

        isset($_POST['confirm_delete']) ? $user->delete() : null;
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

            <h3> <?= isset($_POST['profile_change']) ? $user->message : null ?> </h3>

            <?php

            require 'Form.php';

            $form = new Form($_POST);

//A la consultation de son profil je veux afficher les infos existantes de l'utilsateur, mais s'il a effectué des modifs et la validation a échoué 
//je veux que ses modifs soient conservées, sauf pour les MDP.
            echo $form->label('login', 'Pseudo* :');
            echo isset($_POST['profile_change']) ? $form->inputPOST('text', 'login') : $form->inputWithValue('text', 'login', $user->login);


            echo $form->label('email', 'Email* :');
            echo isset($_POST['profile_change']) ? $form->inputPOST('email', 'email') : $form->inputWithValue('email', 'email', $user->email); 

            
            echo $form->label('firstname', 'Prénom :');
            echo isset($_POST['profile_change']) ? $form->inputPOST('text', 'firstname') : $form->inputWithValue('text', 'firstname', $user->firstname);


            echo $form->label('lastname', 'Nom :');
            echo isset($_POST['profile_change']) ? $form->inputPOST('text', 'lastname') : $form->inputNoValue('text', 'lastname', $user->lastname);


            echo $form->label('new_mdp', 'Nouveau MDP* :');
            echo isset($_POST['profile_change']) ? $form->inputPOST('password', 'new_mdp') : $form->inputNoValue('text', 'new_mdp');


            echo $form->label('new_mdp_confirm', 'Confirmez votre nouveau MDP* :');
            echo $form->inputNoValue('password', 'new_mdp_confirm');


            echo $form->label('mdp', 'Tapez votre ancien MDP pour confirmer les changements');
            echo $form->inputNoValue('password', 'mdp');


            echo $form->button('profile_change', 'Modifier');
            

            echo $form->buttonWithID('delete_profile', 'delete_profile', 'Supprimer mon compte')
            
            ?>
        

        <?php elseif(!isset($_SESSION['userID'])): ?>

            <h3> Pas de compte, pas de profil ! </h3>

        <?php endif ?>
        
        </form>

    </main>

    <?php include 'footer.php' ?>

</body>
</html>

