<div class="container">
    <?php
    if (isset($_GET['erreur'])) {
        $erreur = $_GET['erreur'];
    ?>
        <div class="alert alert-warning" role="alert">
            <?php echo $erreur ?>
        </div>
    <?php
    }
    if (isset($_GET["info"])) {
        $info = $_GET["info"];
    ?>
        <div class="mt-2 p-3 w-75 mx-auto alert alert-success" role=" alert">
            <h2><?php echo $info ?></h2>
        </div>
    <?php
    }
    ?>
    <div class="row text-center">
        <div class="p-3">
            <form action="connexionClient_exe.php" method="post">
                <div class="mb-3">

                    <label for="login" class="form-label">Login :</label>
                    <input type="text" class="form-control" id="login" name="login">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe :</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill" onclick="verifierSaisie()">Connexion</button>
        </div>

        <hr>

        <div>
            <p>Pas encore de compte ? Inscrivez vous ! </p>
            <a href="index.php?page=inscription" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill">Inscription</a>
        </div>
    </div>
</div>