<?php
/**
 * Migration UNIQUE des mots de passe de la table users.
 *
 * Remplit la colonne mdp_hash avec un hash généré par hasher_mdp() à partir
 * du mot de passe en clair de la colonne mdp. À lancer une seule fois en ligne
 * de commande (php migration_mdp.php), puis à SUPPRIMER du serveur.
 *
 * @package SiteMarchand
 */

if (PHP_SAPI !== 'cli') {
    exit("Script à lancer en ligne de commande uniquement\n");
}

require_once __DIR__ . '/info_bdd.php';
require_once __DIR__ . '/gestion_mdp.php';

$utilisateurs = $mon_pdo->query('SELECT codeUser, login, mdp FROM users')->fetchAll(PDO::FETCH_ASSOC);
$maj = $mon_pdo->prepare('UPDATE users SET mdp_hash = :hash WHERE codeUser = :id');

foreach ($utilisateurs as $u) {
    if ($u['mdp'] === null || $u['mdp'] === '') {
        echo $u['login'] . " : pas de mot de passe en clair, à réinitialiser à la main\n";
        continue;
    }
    $maj->execute([':hash' => hasher_mdp($u['mdp']), ':id' => $u['codeUser']]);
    echo $u['login'] . " : mot de passe haché\n";
}
