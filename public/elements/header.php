<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titlepage ?></title>
    <link rel="stylesheet" href="/public/assets/css/style.css">
    <meta name="description" content="Passionné d'horloge">
</head>
<header>
        <div class="hTop">
            <div class="hTop__btnBurgerBlock">
                <div class="btnBurger" id="burger">
                    <div class="btnBurger__top"></div>
                    <div class="btnBurger__middle"></div>
                    <div class="btnBurger__bottom"></div>
                </div>
            </div>
            <?php //include_once('nav.php'); ?>
            <?= nav_menu('nav-link'); ?>
            <?= nav_connect('nav-link'); ?>
            <div class="hTop__logoblock">
                <div div class="hTop__logoblock__logo">
                <img src="../assets/img/logo.svg" alt="" width="250" height="auto">
                </div>
                <div class="hTop__logoblock__hTexts">
                    <!--<h1>PAGE "<?= $titlepage ?>"</h1>-->
                    <h3>Administration</h3>
                </div>
            </div>
        </div> 
    </header>
    <main>
