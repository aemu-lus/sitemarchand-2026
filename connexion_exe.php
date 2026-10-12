<?php
require_once("info_bdd.php");
session_save_path(__DIR__ . '/tmp');
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {

    $nom = trim($_POST["login"]);
    $mdp = trim($_POST["password"]);

    $statement = $mon_pdo->prepare(
        "SELECT login, mdp, nom, role FROM users
        WHERE login = :loginRequete
        AND mdp = :mdpRequete"
    );
    $statement->execute([
        'loginRequete' => $nom,
        'mdpRequete' => $mdp
    ]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

  
    if ($user) {
        $_SESSION['login'] = $nom;
        $_SESSION['role'] = $user['role'];

        header("location:index.php?page=admin");
        exit();
    } else {
        header("location:index.php?page=connexion&&erreur=Echec De Connexion");
        exit();
    }
}
