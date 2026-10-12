<?php
if (isset($_GET['page'])){
    $page = $_GET['page'];
} else{
    $page = 'accueil';
}
include './head.php';
include $page.'.php';
include './footer.php';
?>