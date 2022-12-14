<?php

use modele\dao\Bdd;
use modele\dao\UtilisateurDAO;
use modele\dao\TypeDAO;
use modele\dao\AimerDAO;
use modele\dao\TypeAimerDAO;
use modele\dao\PrefererDAO;

/**
 * Contrôleur updProfil
 * Page d'affichage des caractéristiques d'un utilisateur
 * 
 * Vues contrôlées : vueUpdProfil, vueAuthentification.php
 * 
 * @version 07/2021 intégration couche modèle objet
 * @version 08/2021 gestion erreurs
 */
Bdd::connecter();

// creation du menu burger
$menuBurger = array();
$menuBurger[] = Array("url" => "./?action=profil", "label" => "Consulter mon profil");
$menuBurger[] = Array("url" => "./?action=updProfil", "label" => "Modifier mon profil");

// Initialisations 
$titre = "Mon profil";

// Si un utilisateur est connecté
if (isLoggedOn()) {
    // récupérer son identité
    $idU = getIdULoggedOn();
    $util = UtilisateurDAO::getOneById($idU);

    // Mise à jour de l'objet Utilisateur $util en fonction des saisies
    // Nouveau pseudo
    $pseudoU = "";
    if (isset($_POST["pseudoU"])) {
        $pseudoU = $_POST["pseudoU"];
        if ($pseudoU != "") {
            $util->setPseudoU($pseudoU);
            UtilisateurDAO::update($util);
        }
    }

    // Nouveau mot de passe
    $mdpU = "";
    if (isset($_POST["mdpU"]) && isset($_POST["mdpU2"])) {
        if ($_POST["mdpU"] != "") {
            if (($_POST["mdpU"] == $_POST["mdpU2"])) {
                $mdpU = $_POST["mdpU"];
                UtilisateurDAO::updateMdp($idU, $mdpU);
                logout();
            } else {
                ajouterMessage("Mise à jour du profil : erreur de saisie du mot de passe");
            }
        }
    }

    // Restaurants à supprimer de la liste des restaurants aimés
    if (isset($_POST["lstidR"])) {
        $lstidR = $_POST["lstidR"];
        for ($i = 0; $i < count($lstidR); $i++) {
            AimerDAO::delete($idU, $lstidR[$i]);
        }
    }


    if (isset($_POST["lstidT"])) {
        $lstidT = $_POST["lstidT"];
        for ($i = 0; $i < count($lstidT); $i++) {
            if (!in_array($lstidT[$i], TypeAimerDAO::getTypesAimesByIdU($idU))) {
                $id = TypeDAO::getTypeByLibelle($lstidT[$i]);
                TypeAimerDAO::insert($idU, $id->getIdTC());
            }
        }

        $unselected = array_diff(TypeDAO::getAll(), $lstidT);

        foreach ($unselected as $elm) {
            if (in_array($elm, TypeAimerDAO::getTypesAimesByIdU($idU))) {
                $id = TypeDAO::getTypeByLibelle($elm);
                TypeAimerDAO::delete($idU, $id->getIdTC());
            }
        }
    }
    

// Si on a changé le mot de passe, il faut se reconnecter
    if (!isLoggedOn()) {
        // Construction de la vue
        require_once "$racine/vue/entete.html.php";
        require_once "$racine/vue/vueAuthentification.php";
        require_once "$racine/vue/pied.html.php";
    } else {
        // Sinon, on revient sur le formulaire
        // Recharger les données
        $util = UtilisateurDAO::getOneById($idU);
        $mesRestosAimes = $util->getLesRestosAimes();

        $lesTypes = TypeDAO::getAll();
        $mesTypesAimes = TypeAimerDAO::getTypesAimesByIdU($idU);
        
        // $lesAutresTypesCuisine = TypeDAO::getAllNonPreferesByIdU($idU);

        // Construction de la vue
        require_once "$racine/vue/entete.html.php";
        require_once "$racine/vue/vueUpdProfil.php";
        require_once "$racine/vue/pied.html.php";
    }
} else {
    // Si aucun utilisateur n'est connecté
    // Construction de la vue vide
    ajouterMessage("Update profil : aucun utilisateur n'est connecté");
    require_once "$racine/vue/entete.html.php";
    require_once "$racine/vue/pied.html.php";
}
?>