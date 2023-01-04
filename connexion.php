<?php

    require 'User-pdo.php';

    require 'Form.php';

    
    session_id() == '' ? session_start() : null;

    !isset($_SESSION['user']) ? $user = new Userpdo() : null;

    isset($_POST['connexion']) ? $user->connect() : null;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="index.css" rel = "stylesheet">
    <link href="formulaires.css" rel = "stylesheet">
    <link href="header.css" rel = "stylesheet">
    <link href="footer.css" rel = "stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" 
    integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1Sphttp://localhost/classes-php/connexion.phpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" 
    crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <title>Page de connexion</title>
</head>
<body>

    <?php include 'header.php' ?>
    
        <main>

            <form method="post" class="formulaire">

                <h2><?= $user->check !== 2 ? 'CONNEXION' : null ?></h2>

                <h3> <?= isset($_POST['connexion']) ? $user->message : null ?> </h3>

                <?php if(isset($_POST['connexion']) && $user->check == 2): ?>

                    <h2>Bonjour et bienvenue <?= $_POST['login'] ?> !</h2>
                
                <?php else: ?>

                    <?php
                               
                    $form = new Form($_POST);

                    echo $form->label('login', 'Pseudo :');
                    echo $form->inputPOST('text', 'login');

                    echo $form->label('mdp', 'Mot de passe :');
                    echo $form->passwordWithEye('mdp');

                    echo $form->button('connexion', 'Se connecter');

                    ?>
                
                <?php endif ?>

            </form>   

        </main>

    <?php include 'footer.php' ?>
    
</body>
</html>
<script>
        const passwordInput = document.querySelector(".password_with_eye")
        const eye = document.querySelector(".fa-eye")

        eye.addEventListener("click", function(){
            this.classList.toggle("fa-eye-slash")
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
            passwordInput.setAttribute("type", type)
        })
</script>