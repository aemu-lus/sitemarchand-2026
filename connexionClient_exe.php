<?php
require_once("info_bdd.php");
session_save_path(__DIR__ . '/tmp');
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {

    $pseudo = trim($_POST["login"]);
    $mdp = trim($_POST["password"]);

    try {
        $statement = $mon_pdo->prepare(
            "SELECT IDCLIENT, NOMCLI, PRENOM, PSEUDO, MDP FROM client WHERE PSEUDO = :pseudo"
        );
        $statement->execute(['pseudo' => $pseudo]);
        $client = $statement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        exit("Erreur SQL : " . $e->getMessage());
    }

    if ($client) {
        $_SESSION['IDCLIENT'] = $client['IDCLIENT'];
        $_SESSION['PRENOM'] = $client['PRENOM'];
        header("Location: index.php?page=produits");
        exit();
    } else {
        // Échec : identifiants incorrects
        header("Location: index.php?page=connexionClient&erreur=" . urlencode("Identifiant ou mot de passe incorrect"));
        exit();
    }
}