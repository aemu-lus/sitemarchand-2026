<?php
require_once("info_bdd.php");
$idCommande = $_GET['idCommande'];
$idClient   = $_GET['idClient'];
$stmt = $mon_pdo->prepare(
    "UPDATE commande SET IDSTATUT = '2', DATECOMMANDE = NOW()
     WHERE IDSTATUT = '1' AND IDCOMMANDE = :idCommande AND IDCLIENT = :idClient"
);
$res = $stmt->execute([
    'idCommande' => $idCommande, 
    'idClient' => $idClient]);
if ($res){
    header("location:index.php?page=historique_commandes&info=Le panier a bien été validé");
} else {
    header("location:index.php?page=historique_commandes&erreur=Problème de validation");
}

?>