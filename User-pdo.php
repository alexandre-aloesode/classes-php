<?php

class Userpdo {

    private $id;

    public $login;

    public $email;

    public $firstname;

    public $lastname;

    public $password;

    public $bdd;

    public $message;

    public $check;
    

    public function __construct() {

        try {

        $this->bdd = new PDO('mysql:host=localhost;dbname=classes;charset=utf8', 'root','');
        $this->bdd->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        }

        catch (PDOException $e) {

            echo 'Echec de la connexion : ' . $e->getMessage();
            exit;
        }
        
    }



    public function register() {

        $this->login = $_POST['login'];

        $this->email = $_POST['email'];

        $this->firstname = $_POST['firstname'];

        $this->lastname = $_POST['lastname'];

        $this->password = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

        $this->check = 1;

        if(empty($_POST['login']) || empty($_POST['mdp']) || empty($_POST['email']) || trim($_POST['login']) == '' || trim($_POST['mdp']) == '' || trim($_POST['email']) == '') {
                
            $this->check = 0;
            $this->message = 'Certains champs indispensables sont vides';
        }

        if($_POST['mdp'] !== $_POST['mdp_confirm']) {

            $this->check = 0;
            $this->message =  'Les mots de passe ne correspondent pas';
        }

        if($this->check == 1) {

            $request_check_login= "SELECT login FROM utilisateurs";

            $query_check_login = $this->bdd->prepare($request_check_login);
            $query_check_login->execute();

            $result_check_login = $query_check_login->fetchAll();

            for($x = 0; isset($result_check_login[$x]); $x++ ) {
                    if($result_check_login[$x][0] == $_POST['login']) {

                        $this->check = 0 ;
                        $this->message = 'Ce pseudo existe déjà';
                    }
            } 
        }

        if($this->check == 1) {
  
            $request_add_user = "INSERT INTO utilisateurs(login, password, email, firstname, lastname) VALUES (:login, :password, :email, :firstname, :lastname)";

            $query_add_user = $this->bdd->prepare($request_add_user);

            $query_add_user->execute(array(':login' => $this->login, ':password' => $this->password, 
            ':email' => $this->email, ':firstname' => $this->firstname, ':lastname' => $this->lastname));

            $this->message = 'Compte créé avec succès. <br> Vous êtes désormais connecté.';

            $_SESSION['user'] = $this->login;

        }
//Je souhaite connecter directement l'utilisateur qui crée son compte, les lignes suivantes me permettent de récupérer son id
        if(isset($_SESSION['user']) && !isset($_SESSION['userID'])) {

        $request_ID_user = "SELECT id FROM utilisateurs WHERE login = '$_SESSION[user]'";

        $query_ID_user = $this->bdd->prepare($request_ID_user);
        $query_ID_user->execute();

        $result_ID_user = $query_ID_user->fetchAll();

        $_SESSION['userID'] = $result_ID_user[0][0];
        }  
    }



    
    public function connect() {
    
        $this->check = 0;

        $this->login = $_POST['login'];
      
        $request_login= "SELECT * FROM utilisateurs";

        $query_login = $this->bdd->prepare($request_login);
        $query_login->execute();

        $result_login = $query_login->fetchAll();
    
        for($x = 0; isset($result_login[$x]); $x++){
    
            if($result_login[$x][1] == $this->login){
                    
                $this->check ++;
    
                    if(password_verify($_POST['mdp'], $result_login[$x][2])) {

                        $this->check ++;

                        $_SESSION['userID'] = $result_login[$x][0];
                        $_SESSION['user'] = $this->login;
                    }
            }       
        }
    
        if($this->check == 0){

            $this->message = "Ce nom d'utilisateur n'existe pas.";
        } 
            
        elseif($this->check == 1){

            $this->message = "Le nom d'utilisateur et le mot de passe ne correspondent pas.";
        } 
            
        elseif($this->check == 2){

            $this->message = "Connexion réussie.";
            $_SESSION['user'] = $_POST['login'];
        }
    }



    public function disconnect() {

        session_destroy();
        header('Location: index.php');

    }




    public function get_user_info() {

        $request_fetch_user_info= "SELECT * FROM utilisateurs where id = '$_SESSION[userID]'";

        $query_fetch_user_info = $this->bdd->prepare($request_fetch_user_info);
        $query_fetch_user_info->execute();
        
        $result_fetch_user_info = $query_fetch_user_info->fetchAll();

        $this->id = $result_fetch_user_info[0][0];
        $this->login = $result_fetch_user_info[0][1];
        $this->password = $result_fetch_user_info[0][2];
        $this->email = $result_fetch_user_info[0][3];
        $this->firstname = $result_fetch_user_info[0][4];
        $this->lastname = $result_fetch_user_info[0][5];
    }




    public function update_profile() {
 
    $this->check = 1 ;

        if(empty($_POST['login']) || empty($_POST['mdp']) || empty($_POST['email']) || trim($_POST['login']) == '' || trim($_POST['mdp']) == '' || trim($_POST['email']) == '') {

            $this->check = 0;
            $this->message = 'Certains champs indispensables sont vides';
        }

        if($_POST['new_mdp'] !== $_POST['new_mdp_confirm']) {

            $this->check = 0;
            $this->message = 'Les nouveaux mots de passe ne correspondent pas';
        }

        if($this->check == 1) {

            $this->get_user_info();

            if(!password_verify($_POST['mdp'], $this->password)) {

                $this->check = 0;
                $this->message = 'Ancien mot de passe incorrect';
            }

            if($this->check == 1) {   

                $request_user_info= "SELECT id, login FROM `utilisateurs`";

                $query_user_info = $this->bdd->prepare($request_user_info);
                $query_user_info->execute();

                $result_user_info = $query_user_info->fetchAll();

                for($x = 0; isset($result_user_info[$x]); $x++ ) {

                        if($result_user_info[$x][1] == $_POST['login'] && $result_user_info[$x][0] !== $_SESSION['userID']) {

                            $this->check = 0;
                            $this->message = 'Ce pseudo existe déjà';
                        }
                }
            }
        }

        if($this->check == 1) {
                
        $modified_mdp_hashed = password_hash($_POST['new_mdp'], PASSWORD_DEFAULT);

        $update_user_profile = "UPDATE utilisateurs 
        SET login = :login, email = :email, firstname = :firstname, lastname = :lastname, password = :password 
        WHERE id = :id";

        $query_update_user_profile = $this->bdd->prepare($update_user_profile);
        
        $query_update_user_profile->execute(array(':login' => $_POST['login'], ':email' => $_POST['email'], ':firstname' => $_POST['firstname'], 
        ':lastname' => $_POST['lastname'], ':password' => $modified_mdp_hashed, ':id' => $_SESSION['userID']));

        $this->message = "informations modifiées.";
        }
    }




    public function delete() {

        $request_delete_profile = "DELETE FROM utilisateurs WHERE utilisateurs.id = '$this->id'";

        $query_delete_profile = $this->bdd->prepare($request_delete_profile);
        $query_delete_profile->execute();

        session_destroy();

        header('Location: index.php');
    }



    public function isConnected() {

        isset($_SESSION['userID']) ? true : false;
    }


    public function getLogin() {

        $this->get_user_info();
        return $this->login;
    }


    public function getEmail() {

        $this->get_user_info();
        return $this->email;
    }


    public function getFirstName() {

        $this->get_user_info();
        return $this->firstname;
    }
    

    public function getLastName() {

        $this->get_user_info();
        return $this->lastname;
    }

}

?>