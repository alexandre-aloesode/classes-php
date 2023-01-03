<?php 

    require 'User-pdo.php';

    if(isset($_POST['inscription'])) {

        session_id() == '' ? session_start() : null;

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

            <h2> <?= isset($_POST['inscription']) && isset($_SESSION['user']) ? 'Félicitations !' : 'Inscription' ?> </h2>

            <h3> <?= (isset($_POST['inscription'])) ? $user->message : null ?> </h3>

            <?php if(!isset($_POST['inscription']) || $user->check !== 1): ?>

                <?php 

                    require 'Form.php';

                    $form = new Form($_POST);

                    echo $form->label('login', 'Pseudo* :');
                    echo $form->inputPOST('text', 'login');

                    echo $form->label('email', 'Email* :');
                    echo $form->inputPOST('text', 'email');

                    echo $form->label('firstname', 'Prénom :');
                    echo $form->inputPOST('text', 'firstname');

                    echo $form->label('lastname', 'Nom :');
                    echo $form->inputPOST('text', 'lastname');

                    echo $form->label('mdp', 'Mot de passe* :');
                    echo $form->inputPOST('password', 'mdp');

                    echo $form->label('mdp_confirm', 'Confirmation mot de passe* :');
                    echo $form->inputPOST('password', 'mdp_confirm');

                    echo $form->button('inscription', 'S\'inscrire')

                ?>
                
            <?php endif; ?>

        </form>
            
            
            
    </main>

    <?php include 'footer.php' ?>
    
</body>
</html>


