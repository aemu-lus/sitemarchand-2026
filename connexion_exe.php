<?php
/**
 * Traitement de la connexion à la partie Administration (côté serveur).
 *
 * Étapes : validation des données saisies, récupération de l'utilisateur par
 * son login (requête préparée), vérification du mot de passe par rapport au
 * hash stocké en base, puis ouverture de la session.
 *
 * Dépendances :
 * - info_bdd.php : fournit $mon_pdo ;
 * - gestion_mdp.php : fournit verifier_mdp() (même fichier que pour l'inscription).
 *
 * @package SiteMarchand
 */

require_once __DIR__ . '/info_bdd.php';
require_once __DIR__ . '/gestion_mdp.php';

session_save_path(__DIR__ . '/tmp');
session_start();

const LOGIN_MIN = 3;
const LOGIN_MAX = 30;
const MDP_MAX   = 72;

/**
 * Redirige vers le formulaire de connexion administration avec un message
 * d'erreur, puis arrête le script.
 *
 * @param string $message Message à afficher (générique par défaut, pour ne
 *                        jamais révéler si le login existe).
 *
 * @return void
 */
function redirigerEchec(string $message = 'Echec de connexion'): void
{
    header('Location: index.php?page=connexion&erreur=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigerEchec();
}

$login = trim($_POST['login'] ?? '');
$mdp   = $_POST['password'] ?? '';

if (
    $login === '' || $mdp === '' ||
    !preg_match('/^[A-Za-z0-9_.-]{' . LOGIN_MIN . ',' . LOGIN_MAX . '}$/', $login) ||
    strlen($mdp) > MDP_MAX
) {
    redirigerEchec();
}

try {
    $statement = $mon_pdo->prepare(
        'SELECT login, mdp_hash, nom, role FROM users WHERE login = :login'
    );
    $statement->execute([':login' => $login]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('connexion_exe.php : ' . $e->getMessage());
    redirigerEchec('Un problème est survenu lors de la connexion');
}

if ($user && verifier_mdp($mdp, $user['mdp_hash'])) {
    session_regenerate_id(true); 
    $_SESSION['login'] = $user['login'];
    $_SESSION['role']  = $user['role'];

    header('Location: index.php?page=admin');
    exit;
}

// Échec : login inconnu ou mauvais mot de passe, même message dans les deux cas
redirigerEchec();