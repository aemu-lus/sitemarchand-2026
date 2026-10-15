<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mon site marchand</title>
        <!-- <link rel="stylesheet" href="mesStyle.css"> -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
                crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
                crossorigin="anonymous"></script>
        <link rel="stylesheet" href="mesStyle.css">
        <script src="meteo.js"></script>
        <script src="contact.js"></script>

</head>

<body>
        <?php
        $tmpPath = __DIR__ . '/tmp';
        if (is_dir($tmpPath)) {
                session_save_path($tmpPath);
        }
        session_start();
        if (isset($_SESSION['login']) || isset($_SESSION['IDCLIENT'])) {
                $connecte = true;
        } else {
                $connecte = false;
        }
        ?>


        <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                                <a class="navbar-brand" href="#">MonSite</a>
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item">
                                                <a class="nav-link" href="index.php?page=accueil">Accueil</a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link" href="index.php?page=produits">Produits</a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link" href="index.php?page=contact">Contact</a>
                                        </li>
                                        <li class="nav-item">
                                                <a class="nav-link" href="index.php?page=connexion">Administration</a>
                                        </li>
                                </ul>
                                <div class="d-flex">
                                        <?php if ($connecte) { ?>
                                                <form action="deconnexion.php" method="post">
                                                        <button type="submit" class="btn">
                                                                <h4><i class="bi bi-box-arrow-right"></i>&nbsp;Déconnexion</h4>
                                                        </button>
                                                </form>

                                        <?php } else { ?>
                                                <a href="index.php?page=connexionClient" class="btn">
                                                        <h4><i class="bi bi-person-circle"></i>&nbsp;Connexion</h4>
                                                </a>
                                        <?php } ?>
                                </div>
                        </div>
                </div>
        </nav>