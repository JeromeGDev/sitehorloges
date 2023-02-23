<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

	$productId = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '';
    $requestProduct = $bdd->query("SELECT * FROM productq WHERE product.id= ?");
    $requestProduct->execute([$productId]);

    $products = $requestProduct->fetchAll();
    $requestProduct = $bdd->prepare("SELECT * , products.feature_id as productFeatureId, products.category_id as productCategoryId
                                    FROM products
                                    LEFT JOIN features ON productFeatureId = features.id
                                    LEFT JOIN category ON productCategoryId = category.id
    ");
    //dd($products);

?>
