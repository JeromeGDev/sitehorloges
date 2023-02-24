<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    //REQUETE SELECT POUR REMPLISSAGE AUTO
    $productId = htmlspecialchars($_GET['productId']);
    $userIdAuth = htmlspecialchars($_GET['userIdAuth']);

    // Vérification de l'utilisateur autorisé
    $requestAllowedUser = $bdd->prepare('SELECT * FROM products WHERE id = ?');
    $requestAllowedUser->execute([$productId]);

    $productUsers = $requestAllowedUser->fetchAll();

    foreach($productUsers as $productUser){
        if($productUser['user_id'] === $userIdAuth){
            $rqDel = $bdd->prepare('DELETE 
                                    FROM products
                                    WHERE id = ?');
                $rqDel->execute([$productId]);

            header('Location:product_list.php?successdelete=1');
        } else {
            header('Location:product_list.php?successdelete=0');
        }
    }


