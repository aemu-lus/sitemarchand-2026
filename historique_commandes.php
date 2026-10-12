<?php
require_once("info_bdd.php"); ?>
<?php
        if (isset($_GET['erreur'])) {
            $erreur = $_GET['erreur'];
        ?>
            <div class="alert alert-warning" role="alert">
                <?php echo $erreur ?>
            </div>
        <?php
        }
        if (isset($_GET["info"])) {
            $info = $_GET["info"];
        ?>
            <div class="mt-2 p-3 w-75 mx-auto alert alert-success" role=" alert">
                <h2><?php echo $info ?></h2>
            </div>
        <?php
        }
        ?>
<h1 class="container text-center">Historique des paniers
    <i class="bi bi-person-gear"></i>
</h1>
<?php
$requete_sql = "SELECT commande.IDCOMMANDE, DATECOMMANDE, SUM(quantite) as nb_article, statut.LIBELLESTATUT, SUM(PRIX*QUANTITE) AS MontantTotal
                FROM commande 
                INNER JOIN contenir ON commande.IDCOMMANDE = contenir.IDCOMMANDE
                INNER JOIN produit ON produit.IDPRODUIT = commande.IDPRODUIT
                INNER JOIN statut ON statut.IDSTATUT = commande.IDSTATUT
                GROUP BY produit.IDPRODUIT
                Order by DATECOMMANDE, commande.IDCOMMANDE";
// exécution
$reponse = $mon_pdo->query($requete_sql);
// récupère tous les résultats
$resultat = $reponse->fetchAll(); 
?>
<div class="text-center">
<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
    <table class="table table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Numéro de commande</th>
                <th>Date</th>
                <th>Nombre d'article</th>
                <th>Statut</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($resultat as $ligne) { ?>

                <tr>
                    <td><?php echo $ligne['IDCOMMANDE']; ?></td>
                    <td><?php echo $ligne['DATECOMMANDE']; ?></td>
                    <td><?php echo $ligne['nb_article']; ?></td>
                    <td><?php echo $ligne['LIBELLESTATUT']; ?></td>
                    <td><?php echo $ligne['MontantTotal']; ?></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
    
</div>
<!-- <button class="myButton m-3" ><a href="index.php?page=historique_commandes" style="text-decoration: none; color: black"><i class="bi bi-basket2-fill">Valider le panier</i></a></button> -->
</div>