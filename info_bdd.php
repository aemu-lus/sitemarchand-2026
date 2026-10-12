<?php
$nom_base='site-marchand-e';
$user_bdd='root';
$mdp='';

$mon_pdo = new PDO(
"mysql:host=localhost;dbname=$nom_base;charset=utf8",
$user_bdd,
$mdp
);
?>