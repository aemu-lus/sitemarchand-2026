<?php
/**
 * Traitement de la connexion d'un client (côté serveur).
 *
 * Étapes : validation des données saisies, récupération du client par son
 * pseudo (requête préparée), vérification du mot de passe par rapport au hash
 * stocké en base, puis ouverture de la session.
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

// Mêmes limites que dans inscription_exe.php
const PSEUDO_MIN = 3;
const PSEUDO_MAX = 30;
const MDP_MAX    = 72;

/**
 * Redirige vers le formulaire de connexion avec un message d'erreur,
 * puis arrête le script.
 *
 * @param string $message Message à afficher (générique par défaut, pour ne
 *                        jamais révéler si le pseudo existe).
 *
 * @return void
 */
function redirigerEchec(string $message = 'Identifiant ou mot de passe incorrect'): void
{
    header('Location: index.php?page=connexionClient&erreur=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigerEchec();
}

$pseudo = trim($_POST['login'] ?? '');
$mdp    = $_POST['password'] ?? '';

if (
    $pseudo === '' || $mdp === '' ||
    !preg_match('/^[A-Za-z0-9_.-]{' . PSEUDO_MIN . ',' . PSEUDO_MAX . '}$/', $pseudo) ||
    strlen($mdp) > MDP_MAX
) {
    redirigerEchec();
}

try {
    $statement = $mon_pdo->prepare(
        'SELECT IDCLIENT, NOMCLI, PRENOM, PSEUDO, MDP FROM client WHERE PSEUDO = :pseudo'
    );
    $statement->execute([':pseudo' => $pseudo]);
    $client = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('connexionClient_exe.php : ' . $e->getMessage());
    redirigerEchec("Un problème est survenu lors de la connexion");
}

if ($client && verifier_mdp($mdp, $client['MDP'])) {
    session_regenerate_id(true); 
    $_SESSION['IDCLIENT'] = $client['IDCLIENT'];
    $_SESSION['PRENOM']   = $client['PRENOM'];

    header('Location: index.php?page=produits');
    exit;
}
redirigerEchec();