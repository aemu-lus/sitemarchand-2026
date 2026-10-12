<?php
require_once("info_bdd.php");
session_save_path(__DIR__ . '/tmp');
session_start();

$idProduit = $_POST['idProduit'] ?? '';
$quantite = $_POST['quantite'] ?? '';



if (isset($_SESSION['IDCLIENT'])){
    $idClient = $_SESSION['IDCLIENT'];
} else {
    header("location:index.php?page=connexionClient&&erreur=Connexion requise");
}

try {
    $stmt = $mon_pdo->prepare(
        "SELECT IDCOMMANDE FROM commande 
         WHERE IDCLIENT = :idClient AND IDSTATUT = '1'"
    );
    $stmt->execute(['idClient' => $idClient]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit();
}

if (!$commande) {
    try {
        $stmt = $mon_pdo->prepare(
            "INSERT INTO commande (IDCLIENT, IDSTATUT, DATECOMMANDE) 
             VALUES (:IDCLIENT, '1', NOW())"
        );
        $stmt->execute(['IDCLIENT' => $idClient]);
    } catch (PDOException $e) {
        echo "Erreur SQL : " . $e->getMessage();
        exit();
    }
}


try {
    $stmt = $mon_pdo->prepare(
        "SELECT IDCOMMANDE FROM commande 
         WHERE IDCLIENT = :IDCLIENT AND IDSTATUT = '1'"
    );
    $stmt->execute(['IDCLIENT' => $idClient]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);
    $idCommande = $commande['IDCOMMANDE'];
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit();
}
try {
    $stmt = $mon_pdo->prepare(
        "INSERT INTO contenir (IDCOMMANDE, IDPRODUIT, QUANTITE) 
         VALUES (:IDCOMMANDE, :idProduit, :quantite)
         ON DUPLICATE KEY UPDATE 
        QUANTITE = QUANTITE + VALUES(QUANTITE)"
    );
    $resultat = $stmt->execute([
        'IDCOMMANDE' => $idCommande,
        'idProduit'  => $idProduit,
        'quantite'   => $quantite
    ]);
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
    exit();
}

if ($resultat) {
    header("location:index.php?page=produits_details&&idProduit=$idProduit&&info=Article ajouté au panier");
} else {
    header("location:index.php?page=produits_details&&idProduit=$idProduit&&erreur=Erreur lors de l'ajout");
}
exit();
?>