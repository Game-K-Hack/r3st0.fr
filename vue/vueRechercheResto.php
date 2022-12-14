<?php
/**
 * --------------
 * vueRechercheResto
 * --------------
 * 
 * @version 07/2021 par NB : intégration couche modèle objet
 * 
 * Variables transmises par le contrôleur rechercheResto contenant les données à afficher : 
  ----------------------------------------------------------------------------------------  */
/** @var string $critere (nom, adresse, type, multi) = critere de filtrage */
/** @var string $nomR nom du restaurant recherché */
/** @var string $villeR nom du restaurant recherché */
/** @var string $cpR nom du restaurant recherché */
/** @var string $voieAdrR nom du restaurant recherché */

use modele\dao\TypeDAO;

?>

<h1>Recherche d'un restaurant</h1>
<form action="./?action=recherche&critere=<?= $critere ?>" method="POST">


    <?php
    switch ($critere) {
        case "nom":
            ?>
            Recherche par nom : <br />
            <input type="text" name="nomR" placeholder="nom" value="<?= $nomR ?>" /><br />
            <?php
            break;
        case "adresse":
            ?>
            Recherche par adresse : <br />
            <input type="text" name="villeR" placeholder="ville" value="<?= $villeR ?>"/><br />
            <input type="text" name="cpR" placeholder="code postal" value="<?= $cpR ?>"  maxlength="5" size="5" /><br />
            <input type="text" name="voieAdrR" placeholder="rue" value="<?= $voieAdrR ?>"/><br />
            <?php
            break;
        case "type":
            ?> 
            Recherche par type de cuisine : <br />
            <?php
                foreach (TypeDAO::getAll() as $unType) {
                    echo "<input type=\"checkbox\" name=\"".str_replace(' ', '_', strval($unType))."\" value=\"1\">".$unType."<br />";
                }
            ?>
                <?php
            break;
        case "multi":
            ?>
            Recherche multi-critères<br />
            <input type="text" name="nomR" placeholder="nom du restaurant" value="<?= $nomR ?>"/>
            <input type="text" name="voieAdrR" placeholder="rue" value="<?= $voieAdrR ?>"/><br />
            <input type="text" name="cpR" placeholder="code postal" value="<?= $cpR ?>"  maxlength="5" size="5" /><br />
            <input type="text" name="villeR" placeholder="ville" value="<?= $villeR ?>"/>
            <br />
             <?php
            break;
    }
          ?>  
      
    
    <br /><br />
    <input type="submit" value="Rechercher" />

</form>