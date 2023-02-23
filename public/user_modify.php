<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

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
        //if ($value['users_id'] == $_SESSION['userId'] || $_SESSION['role'] == 5 || $_SESSION['role'] == 4) {
            if (isset($_POST['userLastName']) ||
             isset($_POST['userFirstName']) || 
             isset($_POST['userPseudo']) ||
             isset($_POST['userMail'])
             ) {
                $userLastName = htmlspecialchars(($_POST['userLastName']));
                $userFirstName = htmlspecialchars($_POST['userFirstName']);
                $userPseudo = htmlspecialchars(trim($_POST['userPseudo']));
                $userEmail = htmlspecialchars(strtolower(trim($_POST['userMail'])));
                $userInfos = htmlspecialchars($_POST['userInfos']);
        
        // Enregistrement Photo
        if (isset($_FILES['userPhoto']) ) {
            $absPath = '../../public/assets/img/usersimg/';
            // NOM DU FICHIER IMAGE
            $userPhoto = htmlspecialchars(trim($_FILES['userPhoto']['name']));
            $imageTmp = $_FILES['userPhoto']['tmp_name']; // NOM TEMPORAIRE DU FICHIER IMAGE
            $infoImage = pathinfo($userPhoto); //TABLEAU QUI DECORTIQUE LE NOM DE FICHIER
            $extImage = $infoImage['extension']; //EXTENSION 
            $imageName = $infoImage['filename']; //NOM DU FICHIER SANS L'EXTENSION
            //GENERATION D'UN NOM DE FICHIER UNIQUE
            $uniquePhotoName = $imageName . time() . rand(1, 1000) . "." . $extImage;
            // var_dump($absPath);
            // var_dump($userPhoto);
            // var_dump($imageTmp);
            // var_dump($infoImage);
            // var_dump($extImage);
            // var_dump($imageName);
            // var_dump($uniquePhotoName);
            // exit();
            move_uploaded_file($imageTmp, $absPath . $uniquePhotoName);
        }

                $request = $bdd->prepare('UPDATE users
                                    SET user_lastname = :user_lastname , user_firstname = :user_firstname,user_pseudo = :user_pseudo,user_mail = :user_mail,user_role = :user_role,user_photo = :user_photo,user_infos = :user_infos
                                    WHERE id = :id');

                $request->execute(array(
                    'user_lastname'     => $userLastName,
                    'user_firstname'    => $userFirstName,
                    'user_pseudo'       => $userPseudo,
                    'user_mail'         => $userEmail,
                    'user_role'         => $userRole,
                    'user_photo'        => $uniquePhotoName,
                    'user_infos'        => $userInfos,
                    'id'                => $userId
                ));
                header("Location: user_list?successmodify=1");
            } //else {
            //header('Location: user_list.php?success=2');
        }
    //}

?>
    <?php
        include_once('../elements/head.php')
    ?>
    <?php
        include_once('../elements/header.php');
    ?>
    <?php //if (isset($sessionUserName)){ ?>
        <h1>Modification de la fiche utilisateur</h1>
        <p>Pour changer le mot de passe utilisateur, marci de cliquer ici : <a href="user_change_password.php<?= '?id=' . $userId ?>">Changer le mot passe utilisateur</a></p>
        <!--Formulaire de modification-->
        <div class="formBlocksContainer">
        <h2>Formulaire de modification</h2>
        <blockquote>Attention : toutes les données saisies ici entraîneront des modifications définitives et une perte totale des anciennes données</blockquote>
        <div class="formBlocks">
            <div class="formBlock">
                <h3>Merci de remplir tous les champs du formulaire</h3>

                <form action="user_modify.php<?= '?id=' . $userId ?>" method="POST" enctype="multipart/form-data">
                    <?php foreach ($values as $value) : ?>
                    <div class="inputGroup">
                        <label for="userLastName">Nom d'utilisateur</label>
                        <input type="text" required name="userLastName" id="userLastName" value="<?= $value['user_lastname'] ?>">
                    </div>

                    <div class="inputGroup">
                        <label for="userFirstName">Prénom d'utilisateur</label>
                        <input type="text" required name="userFirstName" id="userFirstName" value="<?= $value['user_firstname'] ;?>">
                    </div>

                    <div class="inputGroup">
                        <label for="userPseudo">Pseudo de connexion</label>
                        <input type="text" required name="userPseudo" id="userPseudo" value="<?= $value['user_pseudo'] ;?>">
                    </div>

                    <div class="inputGroup">
                        <label for="userMail">email</label>
                        <input type="email" required name="userMail" id="userMail" value="<?= $value['user_mail'] ;?>">
                    </div>
                 
                    <div class="inputGroup">
                        <label for="userInfos">Informations particulières</label>
                        <textarea rows="5" cols="20" name="userInfos"><?= $value['user_infos'] ;?></textarea>
                    </div>

                    <div class="inputGroup">
                        <label for="userPhoto">Photo ID</label>
                        <img src="/assets/img/usersimg/<?= $value['user_photo'] ;?>" width="150">
                        <input type="file" name="userPhoto" id="userPhoto" value="<?= $value['user_photo'];?> "<?php //var_dump($value['user_photo'])?>>
                    </div>

                    <?php endforeach; ?>
                    <button class="connectBtn" >Valider TOUTES les modifications</button> 

                </form>
                
            </div>
        </div>
    </div>
    <?php //endif ; ?>

    <?php
        include_once('../elements/footer.php');
    ?>
