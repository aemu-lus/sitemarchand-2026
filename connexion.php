<div class="container">
    <form action="connexion_exe.php" method="post">
        <h1>Connexion à la partie Administation</h1>
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

        <div class="row">
            <div class="mb-3">
                <label for="login" class="form-label">Login :</label>
                <input type="text" class="form-control" id="login" name="login">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>


            <div id="messageErreur" class="erreur"></div><br>

            <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill" onclick="verifierSaisie()">Valider</button>
        </div>