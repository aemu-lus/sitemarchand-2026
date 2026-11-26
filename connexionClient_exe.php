<?php
/**
 * Traitement de la connexion d'un client (côté serveur).
 *
 * Étapes : validation des données saisies, récupération du client par son
 * pseudo (requête préparée), vérification du mot de passe par rapport au hash
 * stocké en base, journalisation de chaque cas avec error_log(), puis
 * ouverture de la session.
 *
 * Les lignes du journal sont écrites dans le fichier défini par la directive
 * error_log du php.ini (PHP ajoute lui-même la date et l'heure en début de ligne).
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

// Adresse IP du visiteur, écrite dans chaque ligne du journal
$ip = $_SERVER['REMOTE_ADDR'] ?? '-';

// L'écriture dans le journal se fait AVANT la redirection : redirigerEchec()
// contient un exit, donc tout ce qui est écrit après n'est jamais exécuté.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    error_log('AUTH | WARNING | Accès à la connexion sans formulaire POST | ip=' . $ip . ' | utilisateur=-');
    redirigerEchec();
}

$pseudo = trim($_POST['login'] ?? '');
$mdp    = $_POST['password'] ?? '';   // jamais modifié, et jamais écrit dans le journal

// Pseudo nettoyé pour le journal : un visiteur ne peut pas y glisser de faux
// retours à la ligne ni de séparateur. Les caractères interdits deviennent « ? ».
$pseudoLog = preg_replace('/[^A-Za-z0-9_.-]/', '?', substr($pseudo, 0, PSEUDO_MAX));
if ($pseudoLog === '') {
    $pseudoLog = '-';
}

if (
    $pseudo === '' || $mdp === '' ||
    !preg_match('/^[A-Za-z0-9_.-]{' . PSEUDO_MIN . ',' . PSEUDO_MAX . '}$/', $pseudo) ||
    strlen($mdp) > MDP_MAX
) {
    error_log('AUTH | WARNING | Echec de connexion : saisie invalide | ip=' . $ip . ' | utilisateur=' . $pseudoLog);
    redirigerEchec();
}

try {
    $statement = $mon_pdo->prepare(
        'SELECT IDCLIENT, NOMCLI, PRENOM, PSEUDO, MDP FROM client WHERE PSEUDO = :pseudo'
    );
    $statement->execute([':pseudo' => $pseudo]);
    $client = $statement->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $erreurSql = str_replace(["\r", "\n"], ' ', $e->getMessage());
    error_log('AUTH | ERROR | Erreur base de données lors de la connexion : ' . $erreurSql
        . ' | ip=' . $ip . ' | utilisateur=' . $pseudoLog);
    redirigerEchec('Un problème est survenu lors de la connexion');
}

// Le journal est interne : il peut être précis, alors que le message affiché
// à l'utilisateur reste le même dans tous les cas d'échec.
if (!$client) {
    error_log('AUTH | WARNING | Echec de connexion : pseudo inconnu | ip=' . $ip . ' | utilisateur=' . $pseudoLog);
    redirigerEchec();
}

if (!verifier_mdp($mdp, $client['MDP'])) {
    error_log('AUTH | WARNING | Echec de connexion : mot de passe incorrect | ip=' . $ip . ' | utilisateur=' . $pseudoLog);
    redirigerEchec();
}

session_regenerate_id(true); // protège contre le vol de session
$_SESSION['IDCLIENT'] = $client['IDCLIENT'];
$_SESSION['PRENOM']   = $client['PRENOM'];

error_log('AUTH | INFO | Connexion réussie (IDCLIENT=' . $client['IDCLIENT'] . ') | ip=' . $ip . ' | utilisateur=' . $pseudoLog);

header('Location: index.php?page=produits');
exit;