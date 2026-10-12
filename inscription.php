<?php
if (isset($_GET["erreur"])) {
    $erreur = $_GET["erreur"];
?>
    <div class="mt-2 p-3 w-75 mx-auto alert alert-danger" role=" alert">
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
} ?>
<div class="container">
<form action="inscription_exe.php" method="post">
    <h1>Page d'inscription</h1>
    <div class="card p-4">
        <div class="mb-3">
            <label for="login" class="form-label">Nom : *</label>
            <input type="text" required class="form-control" id="nom" name="nom">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Prenom : *</label>
            <input type="text" required class="form-control" id="prenom" name="prenom">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Pseudo : *</label>
            <input type="text" required class="form-control" id="pseudo" name="pseudo">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe : *</label>
            <input type="password" required class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Confirmation du mot de passe : *</label>
            <input type="password" required class="form-control" id="mdpConfirm" name="mdpConfirm">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Email :</label>
            <input type="text" class="form-control" id="mail" name="mail">
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Téléphone :</label>
            <input type="text" class="form-control" id="Tel" name="tel">
        </div>
        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill" onclick="verifierSaisie()">Valider</button>
    </div>
</div>