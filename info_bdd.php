<?php
$nom_base='sitemarchand_e';
$user_bdd='root';
$mdp='';

$mon_pdo = new PDO(
"mysql:host=localhost;dbname=$nom_base;charset=utf8",
$user_bdd,
$mdp
);
?>