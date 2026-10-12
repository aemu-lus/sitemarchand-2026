<?php
require_once("info_bdd.php");
session_save_path(__DIR__ . '/tmp');
session_start();

if (isset($_POST["login"]) && isset($_POST["password"])) {

    $pseudo = trim($_POST["login"]);
    $mdp = trim($_POST["password"]);

    try {
        $statement = $mon_pdo->prepare(
            "SELECT IDCLIENT, NOMCLI, PRENOM, PSEUDO FROM client
        WHERE PSEUDO = :loginRequete
        AND MDP = :mdpRequete"
        );
        $statement->execute([
            'loginRequete' => $pseudo,
            'mdpRequete' => $mdp
        ]);
    } catch (PDOException $monException) {
        echo "Erreur SQL : " . $monException->getMessage();
    }
    $client = $statement->fetch(PDO::FETCH_ASSOC);


    if ($client == false) {
        // Connexion KO
        echo("connexion KO");
        header("location:index.php?page=connexionClient&&erreur=Problème de connexion");
    } else {
        // Connexion OK
        echo("connexion ok");
        $_SESSION['IDCLIENT'] = $client['IDCLIENT'];
        $_SESSION['PRENOM'] = $client['PRENOM'];
        header('location:index.php?page=produits');
    }
}
