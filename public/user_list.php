<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    $request = $bdd->query('SELECT *
                                    FROM users');

?>

<?php include 'elements/header.php' ; ?>
<?php if(isset($GET['successcreateuser']) && ($GET['successcreateuser'] === 1)) : ?>
    Utilisateur créé avec succès
    <? elseif(isset($GET['successmodify']) && ($GET['successmodify'] === 1)) :?>
    Utilisateur modifié avec succès
    <? elseif(isset($GET['successdelete']) && ($GET['successdelete'] === 1)) :?>
    Utilisateur supprimé avec succès
    <?php endif ;?>
    
        <?php if (isset($sessionUserName)): ?>
            <h2><?= $sessionUserName ?>, voici la liste des utilisateurs</h2>
            <?php endif ; ?>
        <h3>liste des utilisateurs</h3>
            <table class="tblList">
                <thead class="tblList__header">
                    <tr class="tblList__header__row">
                        <!-- <th class="tblList__header__row__box ">numero ID</th> -->
                        <th class="tblList__header__row__box imageBox">Photo</th>
                        <th class="tblList__header__row__box">Nom</th>
                        <th class="tblList__header__row__box">Prénom</th>
                        <th class="tblList__header__row__box">Pseudo</th>
                        <th class="tblList__header__row__box">mail</th>
                        <th class="tblList__header__row__box">role</th>
                        <th class="tblList__header__row__box">infos</th>
                        <?php if(isset($userRole) & (($userRole === 'admin') || ($userRole === 'superadmin'))) :?>
                        <th class="tblList__header__row__box">Modifier</th>
                        <?php endif;?>
                    </tr
                </thead>
                <tbody class="tblList__content">
                    <?php while ( $users = $request->fetch()) : ?>
                        <tr class="tblList__content__row">
                            <!-- <td class="tblList__content__row__box tableDesc"><?= $users['id']?></td> -->
                            <td class="tblList__content__row__box imageBox"><img src="/public/assets/img/usersimg/<?= $users['user_photo'] ;?>" width="150"></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_lastname']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_firstname']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_pseudo']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_mail']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_role']?></td>
                            <td class="tblList__content__row__box tableDesc"><?= $users['user_infos']?></td>
                            <?php if(isset($userRole) & (($userRole === 'admin') || ($userRole === 'superadmin'))) :?>
                            <td class="tblList__content__row__box tableDesc"><a href="user_fiche.php?id=<?= $users['id']?>">Détails / Modifier fiche</a></td>
                            <td class="tblList__content__row__box tableDesc"><a href="user_del.php?id=<?= $users['id']?>">Supprimer</a></td>
                            <?php endif;?>
                        </tr>
                    <?php endwhile ; ?>
                </tbody>
                <tfoot class="tblList__footer">
                    <tr class="tblList__footer__row">
                        <td colspan="5" class="tblList__footer__row__box action footerBox"><a class="adder" href="signin.php">Ajouter un nouvel utilisateur</a></td></tr>
                </tfoot>
            </table>
    
<?php include_once 'elements/footer.php'; ?>

+
