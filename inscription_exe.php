<?php
require("info_bdd.php");
require("gestion_mdp.php");
session_save_path(__DIR__ . '/tmp');
session_start();

$loginSaisi      = trim($_POST['pseudo'] ?? '');
$mdpSaisi        = $_POST['password'] ?? '';
$mdpConfirmSaisi = $_POST['mdpConfirm'] ?? '';
$nomSaisi        = trim($_POST['nom'] ?? '');
$prenomSaisi     = trim($_POST['prenom'] ?? '');
$mailSaisi       = trim($_POST['mail'] ?? '');
$telephoneSaisi  = trim($_POST['tel'] ?? '');

if ($mdpSaisi !== $mdpConfirmSaisi) {
    header("location:index.php?page=inscription&erreur=" . urlencode("Les 2 mots de passe doivent être identiques"));
    exit;
}
if ($loginSaisi === '' || $mdpSaisi === '' || $nomSaisi === '' || $prenomSaisi === '') {
    header("location:index.php?page=inscription&erreur=" . urlencode("Veuillez renseigner tous les champs obligatoires"));
    exit;
}

$mdp_hash     = hasher_mdp($mdpSaisi);
$mail_chiffre = chiffrer_donnee($mailSaisi, $_ENV['chiffreKey']);
$tel_chiffre  = chiffrer_donnee($telephoneSaisi, $_ENV['chiffreKey']);

$resultat = false;
try {
    $requeteInsertion = "INSERT INTO client (NOMCLI, PRENOM, PSEUDO, mdp_hash, MAIL, TEL)
        VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    $statement = $mon_pdo->prepare($requeteInsertion);
    $resultat = $statement->execute([
        ':nom'       => $nomSaisi,
        ':prenom'    => $prenomSaisi,
        ':pseudo'    => $loginSaisi,
        ':mdp'       => $mdp_hash,
        ':mail'      => $mail_chiffre,
        ':telephone' => $tel_chiffre
    ]);
} catch (PDOException $monException) {
    echo "Erreur SQL : " . $monException->getMessage();   // DEBUG : à retirer ensuite
    exit;
}

if ($resultat) {
    header("location:index.php?page=connexionClient&info=" . urlencode("Inscription réussie. Veuillez vous authentifier"));
} else {
    header("location:index.php?page=inscription&erreur=" . urlencode("Un problème est survenu lors de l'inscription"));
}
exit;