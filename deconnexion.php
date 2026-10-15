<?php
session_save_path(__DIR__ . '/tmp');
session_start();
unset($_SESSION['login']);
session_destroy();
header("location:index.php?page=connexionClient&&info=Déconnexion réussie");
?>