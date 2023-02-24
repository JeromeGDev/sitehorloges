<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    $requestProductsList = $bdd->query('SELECT *, p.id AS productId
                                        FROM products AS p
                                        LEFT JOIN category AS c ON p.category_id = c.id
                                        LEFT JOIN matiere AS m ON p.product_matiere_id = m.id
    ');
    //$requestProductsList->execute([]);
    //$productsList = $requestProductsList->fetchAll();
    $products = $requestProductsList->fetch();
    //dd($products);
    //var_dump($_SESSION);var_dump($products);exit();
?>

<?php include 'elements/header.php' ; ?>
<?php if(isset($GET['successcreateproduct']) && ($GET['successcreateproduct'] === 1)) : ?>
    Produit créé avec succès
<? elseif(isset($GET['successmodifyproduct']) && ($GET['successmodifyproduct'] === 1)) :?>
    Produit modifié avec succès
<? elseif(isset($GET['successdeleteproduct']) && ($GET['successdeleteproduct'] === 1)) :?>
    Produit supprimé avec succès
<?php endif ;?>
    
        <?php if (isset($sessionUserName)): ?>
            <h2><?= $sessionUserName ?>, voici la liste des Produit</h2>
        <?php endif ; ?>
        <h3>liste des Produit</h3>
            <table class="tblList">
                <thead class="tblList__header">
                    <tr class="tblList__header__row">
                        <!-- <th class="tblList__header__row__box ">numero ID</th> -->
                        <th class="tblList__header__row__box imageBox">Photo</th>
                        <th class="tblList__header__row__box">Désignation</th>
                        <th class="tblList__header__row__box">Marque, artiste</th>
                        <th class="tblList__header__row__box">Modèle</th>
                        <th class="tblList__header__row__box">Période</th>
                        <th class="tblList__header__row__box">Matière</th>
                        <th class="tblList__header__row__box">Catégorie</th>
                        <?php if(
                            (isset($sessionUserRole) && ( ($sessionUserRole === 'admin') || ($sessionUserRole === 'superadmin') ))
                            ) :?>
                        <th class="tblList__header__row__box">Consulter</th>
                        <?php endif;?>
                    </tr
                </thead>
                <tbody class="tblList__content">
                    <?php while ( $products = $requestProductsList->fetch()) : ?>
                    <?php //foreach($productsList as $products) : ?>
                        <tr class="tblList__content__row">
                            <!-- <td class="tblList__content__row__box tableDesc"><?= $products['id']?></td> -->
                            <td class="tblList__content__row__box imageBox"><img src="/public/assets/img/productimg/<?= $products['product_photo'] ;?>" width="150"></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['product_name']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['product_brand']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['product_model']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['product_period']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['label']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $products['categorie_name']?></td>
                            <?php if(
                                (isset($sessionUserRole) && (($sessionUserRole === 'admin') || ($sessionUserRole === 'superadmin'))) && 
                                (isset($sessionUserId) && ($sessionUserId === $products['user_id']))) :?>
                            <td class="tblList__content__row__box tableDesc"><a href="product_sheet.php?productId=<?= $products['productId']?>">Détails fiche - modifier</a></td>
                            <?php endif;?>
                        </tr>
                    <?php endwhile ; ?><?php //endforeach ; ?>
                </tbody>
                <tfoot class="tblList__footer">
                    <tr class="tblList__footer__row">
                        <td colspan="7" class="tblList__footer__row__box action footerBox"><a class="btn btn__form" href="signin.php">Ajouter un nouvel utilisateur</a></td></tr>
                </tfoot>
            </table>
    
<?php include_once 'elements/footer.php'; ?>

+
