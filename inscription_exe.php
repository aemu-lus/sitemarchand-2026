<?php
require("info_bdd.php");
session_save_path(__DIR__ . '/tmp');
session_start();

$loginSaisi = htmlspecialchars($_POST['pseudo']) ?? '';
$mdpSaisi = htmlspecialchars($_POST['password']) ?? '';
$mdpConfirmSaisi = htmlspecialchars($_POST['mdpConfirm']) ?? '';
$nomSaisi = htmlspecialchars($_POST['nom']) ?? '';
$prenomSaisi = htmlspecialchars($_POST['prenom']) ?? '';
$mailSaisi = htmlspecialchars($_POST['mail']) ?? '';
$telephoneSaisi = htmlspecialchars($_POST['tel']) ?? '';
if ($mdpSaisi !== $mdpConfirmSaisi) {
    header("location:index.php?page=inscription&&erreur=Les 2 mots de passe doivent être identiques");
}
if (
    strlen($loginSaisi) < 1 || strlen($mdpSaisi) < 1 || strlen($nomSaisi) < 1 ||
    strlen($prenomSaisi) < 1
) {
    header("location:index.php?page=inscription&&erreur=Veuillez renseigner tous les champs obligatoires");
}
require "./info_bdd.php";
$resultat = false;
// requête préparée
try {
    $requeteInsertion = "INSERT INTO client (NOMCLI, PRENOM, PSEUDO, MDP, MAIL, TEL)
    VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    $statement = $mon_pdo->prepare($requeteInsertion);
    $resultat = $statement->execute([
        ':nom' => $nomSaisi,
        ':prenom' => $prenomSaisi,
        ':pseudo' => $loginSaisi,
        ':mdp' => $mdpSaisi,
        ':mail' => $mailSaisi,
        ':telephone' => $telephoneSaisi
    ]);
} catch (PDOException $monException) {
    echo "Erreur SQL : " . $monException->getMessage();
}
if ($resultat) {
    // Insertion OK
    // echo("insertion ok");
    header('location:index.php?page=connexionClient&&info=Inscription réussie. Veuillez vous authentifier');
} else {
    // Insertion KO
    // echo("insertion KO");
    header("location:index.php?page=inscription&&erreur=Un problème est survenu lors de l'inscription");
}
