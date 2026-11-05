<?php
/**
 * Page d'accueil du site marchand.
 *
 * Fragment inclus par index.php : affiche le message de bienvenue et le
 * widget météo (image, ville, température). Les données météo sont
 * récupérées côté navigateur par la fonction JavaScript `fetchMeteo()`
 * définie dans meteo.js, qui remplit les éléments `#imgMeteoJour`,
 * `#titreVille` et `#temperature1`.
 *
 * Cette page ne manipule aucune donnée personnelle ni aucun secret.
 *
 * @package    SiteMarchand
 * @subpackage Pages
 * @author     Emy Philippe-Durand
 * @see        meteo.js
 */
?>
<!-- Script du widget météo : expose fetchMeteo() -->
<script src="meteo.js"></script>
<div class="container">
    <h1 class="m-3 text-center text-white p-3" style="background-color:
#cc95cc;">Bienvenue sur mon site
        marchand</h1>
    <h5 class="m-5 text-center p-3" style="background-color: rgb(169, 140, 197);">Bienvenue sur l'accueil</h5>

<<<<<<< HEAD
=======

    <!-- Bloc météo : rempli dynamiquement par fetchMeteo() -->
>>>>>>> 7ea465e4537cd0e76758a69146d1aa30c9c8a20b
    <div class="row">
        <div class="col">
            <img class="float-start rounded" id="imgMeteoJour">
            <h3 class="card-title" id="titreVille"></h3>
            <h5 id="temperature1"></h5>
        </div>



        <!-- Lance la récupération de la météo au chargement de la page -->
        <script>
            fetchMeteo()
        </script>