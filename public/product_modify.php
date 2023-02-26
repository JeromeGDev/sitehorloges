<?php
    // ATTENTION AUX DATES UPDATE(A ACTUALISER) ET CREATE5NE PAS TOUCHER)
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    //REQUETE SELECT POUR REMPLISSAGE AUTO
    $productId = isset($_GET['productId']) ? $_GET['productId'] : '';
    $userIdAuth = isset($_GET['userIdAuth']) ? $_GET['userIdAuth'] : '';

    // Vérification de l'utilisateur autorisé
    $requestAllowedUser = $bdd->prepare('SELECT *, m.id AS mId, c.id AS cId
                                    FROM products AS p
                                    LEFT JOIN category AS c ON c.id = p.category_id
                                    LEFT JOIN matiere AS m ON m.id = p.product_matiere_id
                                    LEFT JOIN users ON p.user_id = users.id
                                    WHERE p.id = ?
    ');
    $requestAllowedUser->execute([$productId]);
    $productUsers = $requestAllowedUser->fetchAll();
    
    // REQUÊTE DE SELECTION DES CARACTÉRISTIQUES (FEATURES)
    // $requestProductFeatures = $bdd->prepare("SELECT *, f.feature_label AS fLabel, f.id AS fId, pf.feature_id AS pfId
    //                                         FROM features AS f
    //                                         FULL JOIN products_features AS pf ON fId = pfId
    //                                         WHERE pf.product_id = ?
    // ");
    // $requestProductFeatures->execute([$productId]);
    //$features = $requestProductFeatures->fetchAll();
    $requestFeat = $bdd->prepare('SELECT *, f.id AS fId, pf.feature_id AS pfId
                                FROM features AS f
                                LEFT JOIN products_features AS pf ON fId = pfId
                                WHERE pf.product_id = ?
    ');
    $requestFeat->execute([$productId]);
    $features = $requestFeat->fetchAll();
    // dda(['productId=',$productId,'utilisateurId=',$userIdAuth]);
    // print_r($features);
    // var_dump($features);
    // foreach($features as $feature){
    //    var_dump($feature); 
    // }
    // exit();
    // VERIFICATION DE L'UTILISATEUR CONNECTÉ PAR RAPPORT AU PROPRIÉTAIRE DE LA FICHE
    foreach($productUsers as $productUser){
        if($productUser['user_id'] === $userIdAuth){
            //dda([$productId,$userIdAuth,$productUsers]);
            // CODE A EXECUTER SI USER === USER PROPRIÉTAIRE DU PRODUIT
            if(
                isset($_POST['productName']) && 
                isset($_POST['productPeriod']) && 
                isset($_POST['productStyle']) && 
                isset($_POST['productBrand']) &&
                isset($_POST['productModel']) && 
                isset($_POST['productMatiere']) && 
                isset($_POST['productCategory'])
            ){
                
                // obligatoire
                $productName = htmlspecialchars($_POST['productName']);
                $productPeriod = htmlspecialchars($_POST['productPeriod']);
                $productStyle = htmlspecialchars($_POST['productStyle']);
                $productBrand = htmlspecialchars($_POST['productBrand']);
                $productModel = htmlspecialchars($_POST['productModel']);
                $productMatiere = htmlspecialchars($_POST['productMatiere']);
                $productCategory = htmlspecialchars($_POST['productCategory']);
                // optionnel
                $productHistory = htmlspecialchars($_POST['productHistory']);
                $productDesc = htmlspecialchars($_POST['productDesc']);
                // Traitement checkbox
                $productFeatures = $_POST['productFeatures'];
                // automatique
                $productDateUpdate = date('Y-m-d H:i:s');
        
                // Enregistrement Photo
                if (isset($_FILES['productPhoto']) ) {
                    $absPath = 'assets/img/productimg/';
                    // NOM DU FICHIER IMAGE
                    $productPhoto = htmlspecialchars(trim(strtolower($_FILES['productPhoto']['name'])));
                    
                    $imageTmp = $_FILES['productPhoto']['tmp_name']; // NOM TEMPORAIRE DU FICHIER IMAGE
                    $infoImage = pathinfo($productPhoto); //TABLEAU QUI DECORTIQUE LE NOM DE FICHIER
                    $extImage = $infoImage['extension']; //EXTENSION
                    if(file_exists($absPath . DIRECTORY_SEPARATOR . $productPhoto)) {
                        $imageName = $infoImage['filename']; //NOM DU FICHIER SANS L'EXTENSION
                        //GENERATION D'UN NOM DE FICHIER UNIQUE
                        $uniquePhotoName = $imageName . time() . rand(1, 1000) . "." . $extImage;
                    } else {
                        $uniquePhotoName = $productPhoto;
                    }
                    move_uploaded_file($imageTmp, $absPath . $uniquePhotoName);
                }


                // REQUÊTE INSERTION PRODUITS
                $requestProduct = $bdd->prepare("INSERT INTO products(product_name, product_period, product_brand, product_model, product_style, product_description, product_history, product_photo, product_date_update, product_matiere_id, category_id, user_id )
                                                VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?)
                ");
                // REQUÊTE EXECUTION DE L'INSERTION DES PRODUITS
                $requestProduct -> execute([$productName,$productPeriod,$productBrand,$productModel,$productStyle,$productDesc,$productHistory,$uniquePhotoName,$productDateUpdate,$productMatiere,$productCategory,$userId]);
        
                // Récupération de l'id du produit inséré
                $last_insert_id = $bdd->lastInsertId();
                // Débogage $last_insert_id => vérification de la récupération de la bonne ID product
                //dd($last_insert_id);
        
                // REQUÊTE INSERTION CARACTÉRISTIQUES
                $requestFeature = $bdd->prepare('INSERT INTO products_features(product_id,feature_id)
                                                    VALUES (? , ?)
                ');
                // // REQUÊTE EXECUTION DE L'INSERTION DES CARACTÉRISTIQUES
                foreach($productFeatures as $productFeature){
                    $requestFeature->execute([$last_insert_id,$productFeature]);
                }  
                header('Location:product_sheet.php?successmodify=1');
            }

            
        }/*  else {
            header('Location:product_sheet.php?successmodify=0');
        } */
    }
?>

<?php require_once 'elements/header.php'; ?>
<!-- <?php //if (isset($sessionuserLastName)): ?> -->
<div class="formBlocksContainer">
    <h2>Enregistrement d'une nouvelle pendule</h2>
    <div class="formBlocksContainer__formBlock">
        <h3>Merci de remplir tous les champs du formulaire</h3>
        <?php foreach ($productUsers  as $productUser ) : ?>
            <form class="formBlocksContainer__formBlock__form" action="product_add.php" method="POST" enctype="multipart/form-data">

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productName">Désignation de l'Horloge</label>
                    <input type="text" required name="productName" id="productName" placeholder="" value="<?= $productUser['product_name'] ;?>">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productPeriod">Année de fabrication</label>
                    <input type="text" required name="productPeriod" id="productPeriod" placeholder="" value="<?= $productUser['product_period'] ;?>">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productStyle">Style</label>
                    <input type="text" required name="productStyle" id="productStyle" placeholder="" value="<?= $productUser['product_style'] ;?>">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productBrand">Marque, Artiste ou fabriquant</label>
                    <input type="text" required name="productBrand" id="productBrand" placeholder="" value="<?= $productUser['product_brand'] ;?>">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productModel">Modèle</label>
                    <input type="text" required name="productModel" id="productModel" placeholder="" value="<?= $productUser['product_model'] ;?>">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productDesc">Description</label>
                    <textarea rows="5" cols="20" id="productDesc" name="productDesc" placeholder=""><?= $productUser['product_description'] ;?></textarea>
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productHistory">Historique / histoire</label>
                    <textarea rows="5" cols="20" id="productHistory" name="productHistory" placeholder=""><?= $productUser['product_history'] ;?></textarea>
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productPhoto">Photo</label>
                    <input type="file" name="productPhoto" id="productPhoto">
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productMatiere">Matière</label>
                    <select required name="productMatiere" id="productMatiere"><?= $productUser['label'] ;?>
                        <?php foreach($matieres as $matiere) : ?>
                            <option value="<?= $matiere['id'] ;?>"><?= $matiere['label'] ;?></option>
                        <?php endforeach ; ?>
                    </select>
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label for="productCategory">Catégorie</label>
                    <select requiredid="productCategory" name="productCategory">
                        <?php foreach($categories as $categorie) : ?>
                        <option checked value="<?= $productUser['cId'] ;?>"><?= $productUser['categorie_name'] ;?></option>
                        <option value="<?= $categorie['id'];?>"><?= $categorie['categorie_name'];?></option>
                        <?php endforeach ;?>
                    </select>
                </div>

                <div class="formBlocksContainer__formBlock__form__inputGroup">
                    <label>Caractéristiques</label>
                    <div class="formBlocksContainer__formBlock__form__inputGroup__checkbox">
                        <?php foreach ($features as $feature) : ?>
                        <label for="productFeatures">"<?= $feature['feature_label'] ;?>"</label>
                            <?php //var_dump($feature) ;?>
                        <input type="checkbox" name="productFeatures[]" id="productFeatures" value="<?= $feature['id'] ;?>" placeholder="<?= $feature['feature_label'] ;?>">
                        <?php endforeach ;?>
                    </div>
                </div>

                <button class="btn btn__form" type="submit">Ajouter le produit</button>

            </form>
        <?php endforeach ; ?>
    </div>
</div>
<!-- <?php //endif ; ?> -->
<?php require_once 'elements/header.php'; ?>
