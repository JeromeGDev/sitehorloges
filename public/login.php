<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'encode.php';
    if( isset( $_POST['useridsForm']) && isset( $_POST['userPassword'] ) ){
        if( !empty( $_POST['useridsForm']) && !empty( $_POST['userPassword'] ) ){
            $idToCheck = htmlspecialchars($_POST['useridsForm']);
            $psdToCheck = htmlspecialchars($_POST['userPassword']);

            $passwordCrypt = encode($psdToCheck);
         
            $requestCompare = $bdd->prepare('SELECT * FROM users WHERE user_pseudo = ? OR user_mail = ?');
            $requestCompare->execute([$idToCheck,$idToCheck]);

            while ($user = $requestCompare -> fetch()){
                // var_dump($idToCheck);
                // var_dump($passwordCrypt);
                // var_dump($user['user_password']);
                // exit();
                if( $passwordCrypt === $user["user_password"] ){
                    $sessionUserId =  $user["id"];
                    $sessionUserName = $user['user_lastname'];
                    $sessionUserRole = $user['user_role'];
                    $_SESSION["userId"] = $sessionUserId;
                    $_SESSION["userName"] = $sessionUserName;
                    $_SESSION["adminAccess"] = $sessionUserRole;
   
                    header('Location: index.php?successconnect=1');
                } else {
                    header('Location: login.php?failconnect=1');
                }
            }
        } else {
            header('Location: login.php?failconnect=2');
        }
    }
?>
<?php require_once 'elements/header.php'; ?>
<?php if(isset($_GET['failconnect']) && ($_GET['failconnect'] == 1)) : ?>
<script>alert('Mot de passe erroné') </script>
<?php elseif(isset($_GET['failconnect']) && ($_GET['failconnect'] == 2)) : ?>
<script>alert('Erreur : champ non rempli') </script>
<?php endif ;?>
<?php if(!isset($_SESSION['userId'])) :?>
<div class="formBlocksContainer">
    <div class="formBlocksContainer__formBlock">
        <form class="formBlocksContainer__formBlock__form" action="login.php" method="POST">
            <div  class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="userId">Identifiant de connexion</label>
                <input type="text" name="useridsForm" id="useridsForm">
                <label for="userPassword"></label>
                <input type="password" name="userPassword" id="userPassword">
            </div>
            <button type="submit">SE CONNECTER</button>
        </form>
    </div>
</div>
<?php else :?>
<h2>Vous êtes déjà connecté</h2>
<?php endif ;?>
<?php require_once 'elements/footer.php'; ?>
