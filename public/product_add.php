<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    $userId = isset($_SESSION['userId']) ? htmlspecialchars($_SESSION['userId']) : '';

    $requestMat = $bdd->query('SELECT * FROM matiere');
    $matieres = $requestMat->fetchAll();
    
    $requestCat = $bdd->query('SELECT * FROM category');
    $categories = $requestCat->fetchAll();

    $requestFeat = $bdd->query('SELECT * FROM features');
    $features = $requestFeat->fetchAll();

    if(
        isset($_POST['productName']) && 
        isset($_POST['productPeriod']) && 
        isset($_POST['productStyle']) && 
        isset($_POST['productBrand']) &&
        isset($_POST['productModel']) && 
        isset($_POST['productMatiere']) && 
        isset($_POST['productCategory']) && 
        isset($_POST['productFeatures'])
        ){
            $productName = htmlspecialchars($_POST['productName']);
            $productPeriod = htmlspecialchars($_POST['productPeriod']);
            $productStyle = htmlspecialchars($_POST['productStyle']);
            $productBrand = htmlspecialchars($_POST['productBrand']);
            $productModel = htmlspecialchars($_POST['productModel']);
            $productMatiere = htmlspecialchars($_POST['productMatiere']);
            $productCategory = htmlspecialchars($_POST['productCategory']);
            
            $productPhoto = htmlspecialchars($_POST['productPhoto']);
            $productHistory = htmlspecialchars($_POST['productHistory']);
            $productDesc = htmlspecialchars($_POST['productDesc']);
            $productDateCreate = date('Y-m-d H:i:s');
            $productDateUpdate = date('Y-m-d H:i:s');



        // Enregistrement Photo
        if (isset($_FILES['userPhoto']) ) {
            $absPath = 'assets/img/productimg/';
            // NOM DU FICHIER IMAGE
            $productPhoto = htmlspecialchars(trim(strtolower($_FILES['productPhoto']['name'])));
            
            $imageTmp = $_FILES['userPhoto']['tmp_name']; // NOM TEMPORAIRE DU FICHIER IMAGE
            $infoImage = pathinfo($productPhoto); //TABLEAU QUI DECORTIQUE LE NOM DE FICHIER
            $extImage = $infoImage['extension']; //EXTENSION
            if(file_exists($absPath. DIRECTORY_SEPARATOR.$productPhoto)) {
                $imageName = $infoImage['filename']; //NOM DU FICHIER SANS L'EXTENSION
                //GENERATION D'UN NOM DE FICHIER UNIQUE
                $uniquePhotoName = $imageName . time() . rand(1, 1000) . "." . $extImage;
            } else {
                $uniquePhotoName = $productPhoto;
            }
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
        dd($uniquePhotoName);
        //var_dump($categories);var_dump($features);var_dump($matieres);exit();
        $requestProduct = $bdd->prepare("INSERT INTO products(product_name, product_period, product_brand, product_model, product_style, product_description, product_history, product_photo, product_date_create, product_date_update, product_matiere_id, category_id, product_feature_id, user_id )
                                        VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?);
        ");
        //dd($products);

            // Traitement checkbox
            $productFeatures=[];
            foreach($productFeatures as $productFeature){
                $productFeature[] = htmlspecialchars($_POST['productFeatures']);
            }
            $requestFeatureLink = $bdd->prepare('INSERT INTO products_features(feature_id)
            SELECT product_id
            WHERE product_id = LAST_INSERT_ID(),feature_id = ? ');
            $requestFeatureLink->execute([$productId,$productFeatures]);
            
        $requestAdd -> execute([$productName,$productPeriod,$productBrand,$productModel,$productStyle,$productDesc,$productHistory,$uniquePhotoName,$productDateCreate,$productDateUpdate,$productMatiere,$productCategory,$productFeatures,$userId]);
    }
?>
<?php require_once 'elements/header.php'; ?>
<!-- <?php //if (isset($sessionuserLastName)): ?> -->
<div class="formBlocksContainer">
        <h2>Enregistrement d'une nouvelle pendule</h2>
        <div class="formBlocks">

            <div class="formBlock">
                <h3>Merci de remplir tous les champs du formulaire</h3>

                <form action="signin.php" method="POST" enctype="multipart/form-data">

                    <div class="inputGroup">
                        <label for="productName">Désignation de l'Horloge</label>
                        <input type="text" required name="productName" id="productName" placeholder="" value="">
                    </div>

                    <div class="inputGroup">
                        <label for="productPeriod">Année de fabrication</label>
                        <input type="text" required name="productPeriod" id="productPeriod" placeholder="" value="">
                    </div>

                    <div class="inputGroup">
                        <label for="productStyle">Style</label>
                        <input type="text" required name="productStyle" id="productStyle" placeholder="">
                    </div>

                    <div class="inputGroup">
                        <label for="productBrand">Marque, Artiste ou fabriquant</label>
                        <input type="text" required name="productBrand" id="productBrand" placeholder="" value="">
                    </div>

                    <div class="inputGroup">
                        <label for="productModel">Modèle</label>
                        <input type="text" required name="productModel" id="productModel" placeholder="" value="">
                    </div>

                    <div class="inputGroup">
                        <label for="productDesc">Description</label>
                        <textarea rows="5" cols="20" name="productDesc" placeholder=""></textarea>
                    </div>

                    <div class="inputGroup">
                        <label for="productHistory">Historique / histoire</label>
                        <textarea rows="5" cols="20" name="productHistory" placeholder=""></textarea>
                    </div>

                    <div class="inputGroup">
                        <label for="productPhoto">Photo</label>
                        <input type="file" name="productPhoto" id="productPhoto">
                    </div>

                    <div class="inputGroup">
                        <label for="productMatiere">Matière</label>
                        <select name="productMatiere" id="productMatiere">
                            <?php foreach($matieres as $matiere) : ?>
                                <option value="<?= $matiere['id'] ;?>"><?= $matiere['label'] ;?></option>
                            <?php endforeach ; ?>
                        </select>
                    </div>

                    <div class="inputGroup">
                        <label for="productCategory">Catégorie</label>
                        <select name="" id="productCategory" name="productCategory">
                            <?php foreach($categories as $categorie) : ?>
                                <option value="<?= $categorie['id'];?>"><?= $categorie['categorie_name'];?></option>
                            <?php endforeach ;?>
                        </select>
                    </div>

                    <div class="inputGroup">
                        <label>Caractéristiques</label>
                        <div class="inputGroup">
                            <?php foreach ($features as $feature) : ?>
                            <label for="productFeatures">"<?= $feature['feature_label'] ;?>"</label>
                            <?php //var_dump($feature) ;?>
                                <input type="checkbox" name="productFeatures[]" id="productFeatures" value="<?= $feature['feature_label'] ;?>">
                            <?php endforeach ;?>
                        </div>
                    </div>

                    <button class="" type="submit">Ajouter le produit</button>

                </form>
            </div>
        </div>
    </div>
<!-- <?php //endif ; ?> -->
<?php require_once 'elements/header.php'; ?>
