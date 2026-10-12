<?php
require_once("info_bdd.php"); ?>
<h1 class="container text-center">Administation
    <i class="bi bi-person-gear"></i>
</h1>
<div class="alert alert-success w-50 mx-auto" role="alert">
    Bienvenue <?php echo $_SESSION['login']; ?> role : <?php echo $_SESSION['role']; ?>
</div>
<?php
$requete_sql = "SELECT login, nom, prenom, mdp, role FROM users";
// exécution
$reponse = $mon_pdo->query($requete_sql);
// récupère tous les résultats
$resultat = $reponse->fetchAll(); ?>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
    <table class="table table-striped w-50 mx-auto">
        <thead>
            <tr>
                <th>Login</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($resultat as $ligne) { ?>

                <tr>
                    <td><?php echo $ligne['login']; ?></td>
                    <td><?php echo $ligne['nom']; ?></td>
                    <td><?php echo $ligne['prenom']; ?></td>
                    <td><?php echo $ligne['role']; ?></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</div>