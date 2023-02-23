<?php
    include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'environnement.php';
	include_once __DIR__. DIRECTORY_SEPARATOR .'functions'. DIRECTORY_SEPARATOR .'functions.php';

    //REQUETE SELECT POUR REMPLISSAGE AUTO
    $userId = htmlspecialchars($_GET['id']);

    $rqSelect = $bdd->prepare('DELETE 
                                FROM users
                                WHERE id = ?');
    $rqSelect->execute([$userId]);

    header('Location:user_list.php?successdelete=1');

