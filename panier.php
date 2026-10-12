<?php
$idCommande = null;
$idClient = null;
?>

<div class="text-center">
<?php if (!empty($resultat)) { ?>
    <table class="table table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Article</th><th>Prix Unitaire</th><th>Quantité</th><th>Total</th><th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($resultat as $ligne) {
            $idCommande = $ligne['IDCOMMANDE'];
            $idClient = $ligne['IDCLIENT']; ?>
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

    <a class="myButton m-3" href="index.php?page=valider_commande_exe&idCommande=<?php echo $idCommande; ?>&idClient=<?php echo $idClient; ?>" style="text-decoration: none; color: black">
        <i class="bi bi-basket2-fill"></i> Valider le panier
    </a>
<?php } else {
    echo "Aucun article n'a été commandé";
} ?>
</div>