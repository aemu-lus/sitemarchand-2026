<?php
require_once("info_bdd.php");


if (isset($_GET['idProduit'])) {
    $idProduit = $_GET['idProduit'];
}
$statement = $mon_pdo->prepare("SELECT NOMPROD, NOMIMAGE, DESCRIPTION, PRIX, STOCK FROM `produit` WHERE IDPRODUIT = :id");
$statement->execute([':id' => $idProduit]);
$resultat = $statement->fetch();

?>


<div class="container py-5">
    <form action="ajout_panier_exe.php" method="post">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php?page=accueil">Accueil</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=produits">Produits</a></li>
                <li class="breadcrumb-item active"><?php echo htmlspecialchars($resultat['NOMPROD']); ?></li>
            </ol>
        </nav>

        <div class="row g-5 align-items-start">


            <div class="col-md-6 text-center">
                <div class="border rounded-4 p-4 bg-white shadow-sm">
                    <img src="Images/<?php echo htmlspecialchars($resultat['NOMIMAGE']); ?>"
                        alt="<?php echo htmlspecialchars($resultat['NOMPROD']); ?>"
                        class="img-fluid rounded-3"
                        style="max-height: 400px; object-fit: contain;">
                </div>
            </div>

            <div class="col-md-6">
                <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($resultat['NOMPROD']); ?></h1>

                <div class="mb-3">
                    <?php if ($resultat['STOCK'] > 0): ?>
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle me-1"></i>En stock (<?php echo $resultat['STOCK']; ?> disponibles)
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle me-1"></i>Rupture de stock
                        </span>
                    <?php endif; ?>
                </div>

                <hr>

                <p class="text-muted lh-lg"><?php echo htmlspecialchars($resultat['DESCRIPTION']); ?></p>
                <input type="hidden" name="idProduit" value="<?php echo $idProduit; ?>">
                <hr>

                <div class="mb-4">
                    <span class="fs-2 fw-bold text-primary"><?php echo number_format($resultat['PRIX'], 2, ',', ' '); ?> €</span>
                </div>
                <div>
                    <label for="quantite">Quantité</label>
                    <input type="number" id="quantite" name="quantite" value="1">
                </div>
                <div class="d-flex gap-3">
                    <button type="submit" class="myButton m-3"
                        <?php if ($resultat['STOCK'] == 0) echo 'disabled'; ?>>
                        <i class="bi bi-basket2-fill me-2"></i>Ajouter au panier
                    </button>
                    <a href="index.php?page=produits" class="myButton m-3">
                        <i class="bi bi-arrow-left me-1"></i>Retour
                    </a>
                </div>
            </div>

        </div>
</div>