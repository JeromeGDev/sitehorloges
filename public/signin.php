<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'encode.php';

    // Vérification du formulaire
    if (
        isset($_POST['userLastName']) &&
        isset($_POST['userFirstName']) &&
        isset($_POST['userPseudo']) &&
        isset($_POST['userMail']) &&
        isset($_POST['userPsd']) &&
        isset($_POST['userPsdConfirm'])

    ) {
        // stockage de la saisie des champs dans des variables respectives
        $userLastName = htmlspecialchars(($_POST['userLastName']));
        $userFirstName = htmlspecialchars($_POST['userFirstName']);
        $userPseudo = htmlspecialchars(trim($_POST['userPseudo']));
        $userMail = htmlspecialchars(strtolower(trim($_POST['userMail'])));
        $userPsd = htmlspecialchars($_POST['userPsd']);// récupération mot de passe saisi
        $userPsdConfirm = htmlspecialchars($_POST['userPsdConfirm']); // récupération confirmation du mot de passe saisi
        $userInfos = htmlspecialchars($_POST['userInfos']);
        $userRole = "abonne"; // par défaut : role Abonné
        
        // Enregistrement Photo
        if (isset($_FILES['userPhoto'])) {
            $absPath = 'assets/img/usersimg/';
            // NOM DU FICHIER IMAGE
            $userPhoto = htmlspecialchars(trim($_FILES['userPhoto']['name']));
            $imageTmp = $_FILES['userPhoto']['tmp_name']; // NOM TEMPORAIRE DU FICHIER IMAGE
            $infoImage = pathinfo($userPhoto); //TABLEAU QUI DECORTIQUE LE NOM DE FICHIER
            $extImage = $infoImage['extension']; //EXTENSION 
            $imageName = $infoImage['filename']; //NOM DU FICHIER SANS L'EXTENSION
            //GENERATION D'UN NOM DE FICHIER UNIQUE
            $uniquePhotoName = $imageName . time() . rand(1, 1000) . "." . $extImage;
            move_uploaded_file($imageTmp, $absPath . $uniquePhotoName);
        }
        //dd($_FILES);
        //Verification des champs de mot de passe et confirmation de mdp
        if ($userPsd == $userPsdConfirm) {
            // VERIFICATION SI UTILISATEUR DEJA EXISTANT EN BDD
    
            $rqCount = $bdd->prepare('  SELECT COUNT(*) AS usercount
                                        FROM users
                                        WHERE user_mail = ?');
            $passwordCrypt = encode($userPsd);
    
            $rqCount->execute([$userMail]);

            while ($count = $rqCount->fetch()) {
                $countVerify = $count['usercount'];
    
                if ($countVerify < 1) {
                    //ENCRYPTAGE DU MOT DE PASSE grâce à la fonction encode() NA JAMAIS CHANGER CETTE FONCTION NI SON CONTENU = Perte de password - password non fonctionnels
                    $passwordCrypt = encode($userPsd);
    
                    // Requête d'insertion des valeurs dans la table user de la BDD "locations"
                    $requestAdd = $bdd->prepare('INSERT INTO users(user_lastname,user_firstname,user_pseudo,user_mail,user_role,user_password,user_photo,user_infos)
                                                    VALUES( ? , ? , ? , ? , ? , ? , ? , ?)');

                    $requestAdd -> execute([$userLastName,$userFirstName,$userPseudo,$userMail,$userRole,$passwordCrypt,$uniquePhotoName,$userInfos]);

                    header("Location:user_list.php?successcreateuser=1");
                } else {
                    header('Location:signin.php?alreadyexist=1');
                }
            }
        }
    }
?>
<?php include_once 'elements/header.php'; ?>
    <?php if(isset($GET['alreadyexist']) && ($GET['alreadyexist'] === 1)) : ?>
    L'utilisateur existe déjà
    <?php endif ;?>
<!-- <?php //if (isset($sessionuserLastName)): ?> -->
    <div class="formBlocksContainer">
        <h2>Formulaire d'inscription</h2>
        <div class="formBlocks">

            <div class="formBlock">
                <h3>Merci de remplir tous les champs du formulaire</h3>

                <form action="signin.php" method="POST" enctype="multipart/form-data">

                    <div class="inputGroup">
                        <label for="userLastName">Nom de famille</label>
                        <input type="text" required name="userLastName" id="userLastName" placeholder="DUPONT, DURANT" value="">
                    </div>

                    <div class="inputGroup">
                        <label for="userFirstName">Prénom</label>
                        <input type="text" required name="userFirstName" id="userFirstName" placeholder="Henri, Nestor...">
                    </div>

                    <div class="inputGroup">
                        <label for="userPseudo">Pseudo de connexion</label>
                        <input type="text" required name="userPseudo" id="userPseudo" placeholder="Herni241, Nest2KvC...">
                    </div>

                    <div class="inputGroup">
                        <label for="userMail">email</label>
                        <input type="email" required name="userMail" id="userMail" placeholder="henri@fai.com...">
                    </div>

                    <div class="inputGroup">
                        <label for="userInfos">Informations particulières</label>
                        <textarea rows="5" cols="20" name="userInfos" placeholder=""></textarea>
                    </div>

                    <div class="inputGroup">
                        <label for="userPhoto">Photo ID</label>
                        <input type="file" name="userPhoto" id="userPhoto">
                    </div>

                    <div class="inputGroup">
                        <label for="userPsd">Mot de passe</label>
                        <input type="password" required name="userPsd" id="userPsd">
                    </div>

                    <div class="inputGroup">
                        <label for="userPsdConfirm">Confirmation de Mot de passe</label>
                        <input type="password" required name="userPsdConfirm" id="userPsdConfirm">
                    </div>
                    <button class=""  type="submit">Valider inscription</button>

                </form>
            </div>
        </div>
    </div>
<!-- <?php //endif ; ?> -->



<?php include_once 'elements/header.php'; ?>
