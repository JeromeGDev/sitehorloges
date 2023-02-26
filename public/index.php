<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'session.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

	$requestProduct = $bdd->query("SELECT * FROM products");

	// $products = $requestProduct->fetch();
	// var_dump($products);
?>
<body>

<?php require_once 'elements/header.php';?>

<h2>Page d'accueil</h2>

<?php while($products = $requestProduct->fetch()) : ?>

<article>
	<div class="containerBlocks">
		<h2><?= $products['product_name'] ?></h2>
		<div class="containerBlocks__imgBlock">
			<img src="<?= $products['product_photo'] ?>" alt="Fiche produit de <?= $products['product_name'] ?>">
		</div>
		<div class="containerBlocks__blockText">
			<h3><?= $products['product_brand'] ?></h3>
			<h3><?= $products['product_period'] ?></h3>
			<p><?= $products['product_description'] ?></p>
			<h3><?= $products['product_history'] ?></h3>
			<a href="productsheet.php?id=<?= $products['id'] ?>"> En savoir plus</a>
		</div>
	</div>
</article>

<?php endwhile ; ?>
<?php require_once 'elements/footer.php';?>
