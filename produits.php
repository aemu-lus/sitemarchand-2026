<?php
require_once("info_bdd.php");


$requete_sql = "SELECT NOMPROD, NOMIMAGE, IDPRODUIT FROM `produit`;";
// exécution
$reponse = $mon_pdo->query($requete_sql);
// récupère tous les résultats
$resultat = $reponse->fetchAll();

?>
<div class="p-3 mb-2 bg-light text-dark">
        <h2 class="text-center">Bienvenue sur mon site marchand</h2>
</div>
<div class="text-center p-2">
        <div class="p-5">
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 g-3">
                        <?php foreach ($resultat as $ligne) { ?>
                                <div class="col">
                                        <div class="card">
                                                <a class="nav-link" href="index.php?page=produits_details&&idProduit=<?php echo $ligne['IDPRODUIT'] ?>">
                                                        <img src="Images/<?php echo $ligne['NOMIMAGE']; ?>" class="card-img-top"
                                                                style="height: 250px; object-fit: contain;" alt="produit <?php echo $ligne['IDPRODUIT'] ?>">

                                                        <div class="card-body" style="background-color: pink;">
                                                                <div class="card-text"><?php echo $ligne['NOMPROD']; ?></div>
                                                </a>
                                        </div>
                                </div>
                </div>
        <?php } ?>

        </div>
</div>


<button class="myButton m-3"><a href="index.php?page=panier" style="text-decoration: none; color: black"><i class="bi bi-basket2-fill">Voir mon panier</i></a></button>
<!-- <button type="button" class="btn btn-outline-pink m-3"
                        style=""> Voir mon
                                panier </i></button> -->
</div>