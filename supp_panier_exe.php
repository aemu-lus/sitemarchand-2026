<?php
require_once("info_bdd.php");
$idCommande = $_GET['idCommande'];
$idProduit  = $_GET['idProduit'];
$stmt = $mon_pdo->prepare(
    "DELETE FROM contenir 
     WHERE IDCOMMANDE = :idCommande AND IDPRODUIT = :idProduit"
);
$res = $stmt->execute([
    'idCommande' => $idCommande, 
    'idProduit' => $idProduit]);
if ($res){
    header("location:index.php?page=panier&info=L'article a bien été supprimé du panier");
} else {
    header("location:index.php?page=panier&erreur=Problème de suppression");
}

?>