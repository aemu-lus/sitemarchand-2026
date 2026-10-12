<?php
require_once("info_bdd.php"); ?>
<h1 class="container text-center">Accès au panier
    <i class="bi bi-person-gear"></i>
</h1>
<?php
$requete_sql = "SELECT produit.IDPRODUIT, commande.IDCOMMANDE, NOMPROD, PRIX, QUANTITE, SUM(PRIX*QUANTITE) AS MontantTotal, client.IDCLIENT
                FROM produit 
                INNER JOIN contenir ON contenir.IDPRODUIT = produit.IDPRODUIT
                INNER JOIN commande ON commande.IDCOMMANDE = contenir.IDCOMMANDE
                INNER JOIN client ON client.IDCLIENT = commande.IDCLIENT
                GROUP BY produit.IDPRODUIT, commande.IDCOMMANDE, NOMPROD, PRIX, QUANTITE";
// exécution
$reponse = $mon_pdo->query($requete_sql);
// récupère tous les résultats
$resultat = $reponse->fetchAll(); 
?>
<!-- <form action="valider_commande_exe.php" method="post"> -->
<div class="text-center">
<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
    <table class="table table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Article</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>

            <?php
            foreach ($resultat as $ligne) { 
                $idCommande = $ligne['IDCOMMANDE'];
                $idClient = $ligne['IDCLIENT'];
                ?>

                <tr>
                    <td><?php echo $ligne['NOMPROD']; ?></td>
                    <td><?php echo $ligne['PRIX']; ?></td>
                    <td><?php echo $ligne['QUANTITE']; ?></td>
                    <td><?php echo $ligne['MontantTotal']; ?></td>
                    <td><a href="index.php?page=supp_panier_exe&idCommande=<?php echo $ligne['IDCOMMANDE']; ?>&idProduit=<?php echo $ligne['IDPRODUIT']; ?>"><i class="bi bi-trash-fill"></i></a></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
    
</div>
<button class="myButton m-3" ><a href="index.php?page=valider_commande_exe&idCommande=<?php echo $idCommande; ?>&idClient=<?php echo $idClient; ?>" style="text-decoration: none; color: black"><i class="bi bi-basket2-fill">Valider le panier</i></a></button>
</div>