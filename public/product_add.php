<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    $userId = isset($_SESSION['userId']) ? htmlspecialchars($_SESSION['userId']) : '';

    $requestMat = $bdd->query('SELECT * FROM matiere');
    $matieres = $requestMat->fetchAll();
    
    $requestCat = $bdd->query('SELECT * FROM category');
    $categories = $requestCat->fetchAll();

    $requestFeat = $bdd->query('SELECT *
                                FROM features
    ');
    $features = $requestFeat->fetchAll();

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
        //dd($productFeatures);
        // automatique
        $productDateCreate = date('Y-m-d H:i:s');
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
        
        // REQU??TE INSERTION PRODUITS
        $requestProduct = $bdd->prepare("INSERT INTO products(product_name, product_period, product_brand, product_model, product_style, product_description, product_history, product_photo, product_date_create, product_date_update, product_matiere_id, category_id,user_id )
                                        VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?)
        ");
        // REQU??TE EXECUTION DE L'INSERTION DES PRODUITS
        $requestProduct -> execute([$productName,$productPeriod,$productBrand,$productModel,$productStyle,$productDesc,$productHistory,$uniquePhotoName,$productDateCreate,$productDateUpdate,$productMatiere,$productCategory,$userId]);

        // R??cup??ration de l'id du produit ins??r??
        $last_insert_id = $bdd->lastInsertId();
        // D??bogage $last_insert_id => v??rification de la r??cup??ration de la bonne ID product
        //dd($last_insert_id);

        // REQU??TE INSERTION CARACT??RISTIQUES
        $requestFeature = $bdd->prepare('INSERT INTO products_features(product_id,feature_id)
                                        VALUES (? , ?)
        ');
        // REQU??TE EXECUTION CARACT??RISTIQUES
        foreach($productFeatures as $productFeature){
            //var_dump($productFeature);
            $requestFeature->execute([$last_insert_id,$productFeature]);
        }//exit();  

    }
?>
<?php require_once 'elements/header.php'; ?>
<!-- <?php //if (isset($sessionuserLastName)): ?> -->
<div class="formBlocksContainer">
    <h2>Enregistrement d'une nouvelle pendule</h2>
    <div class="formBlocksContainer__formBlock">
        <h3>Merci de remplir tous les champs du formulaire</h3>

        <form class="formBlocksContainer__formBlock__form" action="product_add.php" method="POST" enctype="multipart/form-data">

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productName">D??signation de l'Horloge</label>
                <input type="text" required name="productName" id="productName" placeholder="" value="">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productPeriod">Ann??e de fabrication</label>
                <input type="text" required name="productPeriod" id="productPeriod" placeholder="" value="">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productStyle">Style</label>
                <input type="text" required name="productStyle" id="productStyle" placeholder="">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productBrand">Marque, Artiste ou fabriquant</label>
                <input type="text" required name="productBrand" id="productBrand" placeholder="" value="">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productModel">Mod??le</label>
                <input type="text" required name="productModel" id="productModel" placeholder="" value="">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productDesc">Description</label>
                <textarea rows="5" cols="20" id="productDesc" name="productDesc" placeholder=""></textarea>
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productHistory">Historique / histoire</label>
                <textarea rows="5" cols="20" id="productHistory" name="productHistory" placeholder=""></textarea>
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productPhoto">Photo</label>
                <input type="file" name="productPhoto" id="productPhoto">
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productMatiere">Mati??re</label>
                <select required name="productMatiere" id="productMatiere">
                    <?php foreach($matieres as $matiere) : ?>
                        <option value="<?= $matiere['id'] ;?>"><?= $matiere['label'] ;?></option>
                    <?php endforeach ; ?>
                </select>
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label for="productCategory">Cat??gorie</label>
                <select requiredid="productCategory" name="productCategory">
                    <?php foreach($categories as $categorie) : ?>
                        <option value="<?= $categorie['id'];?>"><?= $categorie['categorie_name'];?></option>
                    <?php endforeach ;?>
                </select>
            </div>

            <div class="formBlocksContainer__formBlock__form__inputGroup">
                <label>Caract??ristiques</label>
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
    </div>
</div>
<!-- <?php //endif ; ?> -->
<?php require_once 'elements/header.php'; ?>
