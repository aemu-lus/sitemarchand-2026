<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$nom_base=$_ENV['nom_base']; //@var string|false
$user_bdd=$_ENV['user_bdd']; //@var string|false
$mdp=$_ENV['mdp']; //@var string|false

$mon_pdo = new PDO(
"mysql:host=localhost;dbname=$nom_base;charset=utf8",
$user_bdd,
$mdp
);
?>

