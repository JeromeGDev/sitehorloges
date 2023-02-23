<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';
    $userId = $_GET['id'];

    $request = $bdd->prepare('SELECT *
                                    FROM users
                                    WHERE id = ?');
    $request->execute([$userId]);

?>
<?php include 'elements/header.php' ; ?>



<?php while ( $users = $request->fetch()) : ?>
<div class="mainSheetContainer">
    <h1>fiche utilisateur</h1>
    <div class="mainSheetContainer__child">
        <article>
            <div class="mainSheetContainer__child__articleContainer">
                <div class="mainSheetContainer__child__articleContainer__imgC">
                    <img src="assets/img/usersimg/<?= $users['user_photo'] ;?>" alt="Photo de <?= $users['user_lastname'] ;?> <?= $users['user_lastname'] ;?>">
                </div>
                <div class="mainSheetContainer__child__articleContainer__block">
                    <h3><span>UTILISATEUR : </span>  <?= $users['user_lastname'] ;?> <?= $users['user_lastname'] ;?></h3>
                    <h6><span>PSEUDO : </span> <?= $users['user_pseudo'] ;?></h6>
                    <h6><span>EMAIL : </span> <?= $users['user_mail'] ;?></h6>
                    <h6><span>ROLE : </span> <?= $users['user_role'] ;?></h6>
                    <h6><span>INFORMATIONS : </span> <?= $users['user_infos'] ;?></h6>
                    <div class="mainSheetContainer__btns">
                        <a href="user_modify.php?id=<?= $users['id']?>">Modifier</a>
                        <a href="user_del.php?id=<?= $users['id']?>">Supprimer</a>
                    </div>
                </div>
            </div>
        </article>
    </div>


</div>
<?php endwhile ; ?>
<?php include_once 'elements/footer.php'; ?>
