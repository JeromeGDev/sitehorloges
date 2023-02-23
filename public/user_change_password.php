<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'encode.php';

    //REQUETE SELECT POUR REMPLISSAGE AUTO
    $userId = htmlspecialchars($_GET['id']);

    $rqSelect = $bdd->prepare('SELECT *
                                FROM users
                                WHERE id = ?');
    $rqSelect->execute([$userId]);
    
    //FETCH ALL RÉCUPÈRE D'UN COUP TOUTE LES RANGÉES DE LA BDD DANS UN TABLEAU A 2 DIMENSIONS
    $values = $rqSelect->fetchAll();

    //FOREACH PERMET DE BOUCLER SUR LE TABLEAU VALUES
    foreach ($values as $value) {
        //ON VERIFIE SI LE USER EST LE CREATEUR OU L'ADMIN
        // if(
        //     isset($sessionUserRole) && isset($sessionUserName)  
        //     && (($sessionUserRole === 'admin') || ($sessionUserRole === 'superadmin')) 
        //     && (($sessionUserName == $value['user_pseudo']) || ($sessionUserName == $value['user_mail']))
        // ){
            if (isset($_POST['oldPsd']) && isset($_POST['newPsd']) && isset($_POST['newPsdConfirm'])){
                $oldPsd = htmlspecialchars(trim($_POST['oldPsd']));
                $oldPsdBdd = $value['user_password'];
                $newPsd = htmlspecialchars(trim($_POST['newPsd']));
                $newPsdConfirm = htmlspecialchars(trim($_POST['newPsdConfirm']));
                //dda([$oldPsd,$oldPsdBdd,$newPsd,$newPsdConfirm]);
                $oldPsdCrypt = encode($oldPsd);

                if($oldPsdCrypt === $oldPsdBdd){
                    $newPsdCrypt = encode($oldPsd);
                    $newPsdConfirmCrypt = encode($newPsdConfirm);                
                    //dda([$oldPsdCrypt,$oldPsdBdd,$newPsdCrypt,$newPsdConfirmCrypt]);
                    if($newPsdCrypt === $newPsdConfirmCrypt){
                        $request = $bdd->prepare('UPDATE users
                                                SET user_password = :user_password
                                                WHERE id = :id'
                        );

                        $request->execute(array(
                            'user_password'     => $newPsdCrypt,
                            'id'                => $userId
                        ));
                        header("Location: user_modify.php?id=$userId&successupdatepassword=1");
                    }
                }                    
            }

        } //else {
            //header("Location:user_modify.php?id=$userId&accessallowed=0");
        //}
    //}
?>
    <?php
        include_once('elements/header.php');
    ?>
    <?php //if (isset($sessionUserName)){ ?>
        <h1>Modification de la fiche utilisateur</h1>
        <h2>Formulaire de modification du mot de passe</h2>
        <div class="formBlocks">
            <div class="formBlock">
                <h3>Merci de remplir tous les champs du formulaire</h3>
                <form action="user_change_password.php<?= '?id=' . $userId ?>" method="POST">

                    <div class="inputGroup">
                        <label for="oldPsd">Ancien Mot de passe</label>
                        <input type="password" name="oldPsd" id="oldPsd">
                    </div>

                    <div class="inputGroup">
                        <label for="newPsd">Nouveau Mot de passe</label>
                        <input type="password" name="newPsd" id="newPsd">
                    </div>

                    <div class="inputGroup">
                        <label for="newPsdConfirm">Confirmation du nouveau Mot de passe</label>
                        <input type="password" name="newPsdConfirm" id="newPsdConfirm">
                    </div>
                    
                    <button class="connectBtn" >Valider TOUTES les modifications</button> 

                </form>
                
            </div>
        </div>
    <?php //endif ; ?>

    <?php
        include_once('elements/footer.php');
    ?>
