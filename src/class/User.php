<?php

class User {

    public $userName;

    /**
     * Fonction permettant de définir un nouvel utilisateur
     * @param string $userLastname
     * @param string $userFirstname
     * @param string $userPseudo
     * @param string $userMail
     * @param string $userPsd
     * @param string $userInfos
     * @return string nouvel utilisateur
     */
    public function createUser( string $userLastname, string $userFirstname, string $userPseudo, string $userMail, string $userPsd, string $userInfos) : string
    {
        if (
            isset($_POST['userLastName']) &&
            isset($_POST['userFirstName']) &&
            isset($_POST['userPseudo']) &&
            isset($_POST['userPsd']) &&
            isset($_POST['userPsdConfirm']) &&
            isset($_POST['userMail'])
        ) {
            // stockage de la saisie des champs dans des variables respectives
            $userLastName = htmlspecialchars(($_POST['userLastName']));
            $userFirstName = htmlspecialchars($_POST['userFirstName']);
            $userPseudo = htmlspecialchars(trim($_POST['userPseudo']));
            $userMail = htmlspecialchars(strtolower(trim($_POST['userMail'])));
            $userPsd = htmlspecialchars($_POST['userPsd']);// récupération mot de passe saisi
            $userPsdConfirm = htmlspecialchars($_POST['userPsdConfirm']); // récupération confirmation du mot de passe saisi
            $userInfos = htmlspecialchars($_POST['userInfos']);
            $userRole = "abonne"; // par défaut : role Abonné
        }

    }
}
