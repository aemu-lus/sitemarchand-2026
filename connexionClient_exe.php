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

    // si MDP est en clair : $mdp === $client['MDP']
    try{
        if ($client && password_verify($mdp, $client['MDP'])) {
        $_SESSION['IDCLIENT'] = $client['IDCLIENT'];
        $_SESSION['PRENOM'] = $client['PRENOM'];
        header("location:index.php?page=produits");
        exit();
    } 
    } catch (PDOException $e) {
        header("location:index.php?page=connexionClien&erreur=" . $e->getMessage());
        exit("Erreur : " . $e->getMessage());
    }
    
    } 
    
