<?php
require_once("info_bdd.php");
require_once("gestion_secrets.php");   // contient verifier_mdp()

session_save_path(__DIR__ . '/tmp');
session_start();

// On ne traite que les formulaires envoyés en POST avec les deux champs remplis
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && !empty($_POST["login"])
    && !empty($_POST["password"])) {

    $login = trim($_POST["login"]);
    $mdp   = $_POST["password"];   // pas de trim() : on ne modifie pas le mot de passe

    // 1. On récupère l'utilisateur à partir de son login uniquement
    $statement = $mon_pdo->prepare(
        "SELECT login, mdp, nom, role FROM users WHERE login = :login"
    );
    $statement->execute(['login' => $login]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // 2. On compare le mot de passe saisi avec le hash stocké en base
    if ($user && verifier_mdp($mdp, $user['mdp'])) {
        session_regenerate_id(true);   // protège contre le vol de session
        $_SESSION['login'] = $user['login'];
        $_SESSION['role']  = $user['role'];

        header("Location: index.php?page=admin");
        exit();
    }
}

// Échec (champs manquants, login inconnu ou mauvais mot de passe) :
// même message dans tous les cas, pour ne pas révéler si le login existe
header("Location: index.php?page=connexion&erreur=" . urlencode("Echec de connexion"));
exit();