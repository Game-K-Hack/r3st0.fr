<?php

namespace modele\dao;

use modele\dao\Bdd;
use PDO;
use PDOException;
use Exception;

/**
 * Description of AimerDAO
 *
 * @author N. Bourgeois
 * @version 07/2021
 */
class TypeAimerDAO {

    public static function getTypesAimesByIdU(int $idU): array {
        $lesObjets = array();
        try {
            $requete = "SELECT t.* FROM type t INNER JOIN aimertype ON t.idTC = aimertype.idT INNER JOIN utilisateur ON aimertype.idU = utilisateur.idU WHERE utilisateur.idU = :idU;";
            $stmt = Bdd::getConnexion()->prepare($requete);
            $stmt->bindParam(':idU', $idU);
            $ok = $stmt->execute();
            // attention, $ok = true pour un select ne retournant aucune ligne
            if ($ok) {
                // Pour chaque enregistrement
                while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    //Instancier un nouveau restaurant et l'ajouter à la liste
                    $lesObjets[] = $enreg['libelleTC'];
                }
            }
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::getAimesByIdU : <br/>" . $e->getMessage());
        }
        return $lesObjets;
    }



    /**
     * Ajouter un couple (idU, idR) à la table aimer
     * @param int $idU identifiant de l'utilisateur qui aime le restaurant
     * @param int $idR identifiant du restaurant aimé
     * @return bool true si l'opération réussit, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function insert(int $idU, int $idT): bool {
        $resultat = false;
        try {
            $stmt = Bdd::getConnexion()->prepare("INSERT INTO `aimertype` (`idU`, `idT`) VALUES (:idU,:idT)");
            $stmt->bindParam(':idU', $idU);
            $stmt->bindParam(':idT', $idT);
            $resultat = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::insert : <br/>" . $e->getMessage());
        }
        return $resultat;
    }

    /**
     * Suppimer un couple (idU, idR) de la table aimer
     * @param int $idU identifiant de l'utilisateur
     * @param int $idR identifiant du restaurant
     * @return bool true si réussite, false sinon
     * @throws Exception transmission des erreurs PDO éventuelles
     */
    public static function delete(int $idU, int $idT): bool {
        $resultat = false;
        try {
            $stmt = Bdd::getConnexion()->prepare("DELETE FROM `aimertype` WHERE idT=:idT and idU=:idU");
            $stmt->bindParam(':idT', $idT);
            $stmt->bindParam(':idU', $idU);
            $resultat = $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erreur dans la méthode " . get_called_class() . "::delete : <br/>" . $e->getMessage());
        }
        return $resultat;
    }

}
