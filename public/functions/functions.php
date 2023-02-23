<?php
    
    /**
     * Fonction de création d'un lien de menu
     * @param string $lien
     * @param string $titre
     * @param string $linkClass
     * @return string
     */
    function nav_item (string $lien, string $titre, string $linkClass = ''): string {
        $classe = 'nav-item';
        if ($_SERVER['SCRIPT_NAME'] === $lien) {
            $classe .= ' active';
        }
        return <<<HTML
        <li class="$classe">
            <a class="$linkClass" href="$lien">$titre</a>
        </li>
HTML;
    }


    /**
     * Fonction de création du menu sur la base de la création des liens avec la fonction navitem
     * @param string $linkClass
     * @return string 
     */
    function nav_menu (string $linkClass = ''): string{
        return 
        nav_item('index.php', 'Accueil', $linkClass) .
        nav_item('signin.php', 'Créer utilisateur OK', $linkClass) .
        nav_item('user_list.php', 'Liste utilisateurs OK', $linkClass) .
        nav_item('product_add.php', 'Ajouter une horologe', $linkClass) .
        nav_item('product_list.php', 'Liste des horloges', $linkClass) .
        nav_item('type_add.php', 'Ajouter lister types OK', $linkClass) .
        nav_item('contact.php', 'Contact', $linkClass);
    }

    function nav_connect(string $linkClass = ''): string{
        if(isset($_SESSION['userId'])){
            return
            nav_item('functions/logout.php', 'Deconnexion', $linkClass);
        } elseif (!isset($_SESSION['userId'])){
            return
            nav_item('login.php', 'Connexion', $linkClass);
        }
    }

    /**
     * Fonction de vérification de l'authentification et ouverture de session avec droit admin
     * @param int $$
     */
    function checkAuth($a){
        if ($a = 'superadmin'){
            return
            $_SESSION["adminAccess"] = true;
            //$_SESSION["superAdminAccess"] = true;77
        } elseif ( $a == 'admin'){
            return
            $_SESSION["adminAccess"] = true;
            //$_SESSION["superAdminAccess"] = false;
        } elseif ($a == 'gestionnaire'){
            return
            $_SESSION["adminAccess"] = true;
            //$_SESSION["superAdminAccess"] = false;
        } elseif ( $a == 'abonne'){
            return
            $_SESSION["adminAccess"] = false;
            //$_SESSION["superAdminAccess"] = false;
        } elseif ( $a == 'visiteur') {
            return
            $_SESSION["adminAccess"] = false;
        }
    }

    /**
     * Fonction de débogage
     */
    function dd($a){
        echo '<pre>';
        var_dump($a);
        exit();
        echo '</pre>';
    }

    /**
     * Fonction de d'un tableau de vairables
     */
    function dda(array $as){
        while ( $as){
            echo '<pre>';
            var_dump($as);
            exit();
            echo '</pre>';
        }
    }
