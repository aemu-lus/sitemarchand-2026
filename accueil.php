<script src="meteo.js"></script>
<div class="container">
    <h1 class="m-3 text-center text-white p-3" style="background-color:
#cc95cc;">Bienvenue sur mon site
        marchand</h1>
    <h5 class="m-5 text-center p-3" style="background-color: rgb(169, 140, 197);">Bienvenue sur l'accueil</h5>


    <div class="row">
        <div class="col">
            <img class="float-start rounded" id="imgMeteoJour">
            <h3 class="card-title" id="titreVille"></h3>
            <h5 id="temperature1"></h5>
        </div>



        <script>
            fetchMeteo()
        </script>