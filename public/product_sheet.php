<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';
    $productId = $_GET['productId'];

    // $requestProduct = $bdd->prepare('SELECT *
    //                                 FROM products
    //                                 WHERE id = ?');
    $requestProduct = $bdd->prepare("SELECT *, p.id AS pId , p.user_id AS pUserId
                                    FROM products AS p
                                    LEFT JOIN category AS c ON c.id = p.category_id
                                    LEFT JOIN matiere  AS m ON m.id = p.product_matiere_id
                                    LEFT JOIN users ON p.user_id = users.id
                                    WHERE p.id = ?
    ");
    $requestProduct->execute([$productId]);

    $requestProductFeatures = $bdd->prepare("SELECT *
                                        FROM products_features AS pf
                                        LEFT JOIN features AS f ON f.id = pf.feature_id
                                        WHERE pf.product_id = ?
    ");
    $requestProductFeatures->execute([$productId]);
?>
<?php include 'elements/header.php' ; ?>

<?php while ( $product = $requestProduct->fetch()) : ?>
<div class="mainSheetContainer">
    <h1>fiche utilisateur</h1>

    <div class="mainSheetContainer__child">
        <article class="mainSheetContainer__child__articleContainer">
            <div class="mainSheetContainer__child__articleContainer__imgC">
                <img src="/public/assets/img/productimg/<?= $product['product_photo'] ;?>" alt="Photo de <?= $product['product_name'] ;?>">
            </div>
            <div class="mainSheetContainer__child__articleContainer__block">
                <h3><span>Désignation : </span>  <?= $product['product_name'] ;?></h3>
                <h6><span>Marque / Artiste : </span> <?= $product['product_brand'] ;?></h6>
                <h6><span>Modèle : </span> <?= $product['product_model'] ;?></h6>
                <h6><span>Période : </span> <?= $product['product_period'] ;?></h6>
                <h6><span>Catégorie : </span> <?= $product['categorie_name'] ;?></h6>
                <h6><span>Matière : </span> <?= $product['label'] ;?></h6>
            </div>
            <div class="mainSheetContainer__child__articleContainer__block">
                <h6><span>Informations : </span> <?= $product['product_description'] ;?></h6>
                <h6><span>Histoire : </span> <?= $product['product_history'] ;?></h6>
                <?php while ( $pfeatures = $requestProductFeatures->fetch()) : ?>
                <h6><span>Caractéristiques : </span> <?= $pfeatures['feature_label'] ;?></h6>
                <!-- <pre><?php //var_dump($pfeatures); ;?></pre> -->
                <?php endwhile ; ?>
            </div>
            <div class="mainSheetContainer__child__articleContainer__block">
                <h6><span>Date d'enregistrement : </span> <?= $product['product_date_create'] ;?></h6>
                <h6><span>Date dernière modification : </span> <?= $product['product_date_update'] ;?></h6>
            </div>
        </article>
        <div class="mainSheetContainer__btns">
            <a href="product_modify.php?productId=<?= $product['pId'] ;?>&userIdAuth=<?= $product['pUserId'] ;?>">Modifier</a>
            <a href="product_del.php?productId=<?= $product['pId'] ;?>&userIdAuth=<?= $product['pUserId'] ;?>">Supprimer</a>
        </div>
    </div>
</div>
<?php endwhile ; ?>
<?php include_once 'elements/footer.php'; ?>
