<?php
/**
 * Traitement de l'inscription d'un client (côté serveur).
 *
 * Dépendances :
 * - info_bdd.php : fournit $mon_pdo ;
 * - gestion_mdp.php : fournit hasher_mdp() et chiffrer_donnee() ;
 * - verifierCoordonnees.php : fournit verifierCoordonnees().
 *
 * @package SiteMarchand
 */

require_once __DIR__ . '/info_bdd.php';
require_once __DIR__ . '/gestion_mdp.php';
require_once __DIR__ . '/verifierCoordonnees.php';

session_save_path(__DIR__ . '/tmp');
session_start();

// Limites à adapter à la taille réelle des colonnes de la table client
const PSEUDO_MIN = 3;
const PSEUDO_MAX = 30;
const NOM_MAX    = 50;
const MDP_MIN    = 12;
const MDP_MAX    = 72;

/**
 * Redirige vers le formulaire d'inscription avec un message d'erreur,
 * puis arrête le script.
 *
 * @param string $message Message d'erreur à afficher.
 *
 * @return void
 */
function redirigerErreur(string $message): void
{
    header('Location: index.php?page=inscription&erreur=' . urlencode($message));
    exit;
}

/**
 * Indique si un pseudo est déjà utilisé par un client.
 *
 * @param PDO    $pdo    Connexion à la base de données.
 * @param string $pseudo Pseudo à tester.
 *
 * @return bool true si le pseudo existe déjà.
 *
 * @throws PDOException En cas d'erreur SQL.
 */
function pseudoDejaUtilise(PDO $pdo, string $pseudo): bool
{
    $statement = $pdo->prepare('SELECT COUNT(*) FROM client WHERE PSEUDO = :pseudo');
    $statement->execute([':pseudo' => $pseudo]);
    return (int) $statement->fetchColumn() > 0;
}

// 1. Accepter uniquement une soumission de formulaire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigerErreur("Le formulaire n'a pas été soumis correctement");
}

// 2. Récupération des données (trim, sans htmlspecialchars avant la BDD)
$pseudo     = trim($_POST['pseudo'] ?? '');
$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$mail       = trim($_POST['mail'] ?? '');
$tel        = trim($_POST['tel'] ?? '');
$mdp        = $_POST['password'] ?? '';
$mdpConfirm = $_POST['mdpConfirm'] ?? '';

// 3. Vérifications : on accumule toutes les erreurs
$erreurs = [];

// Champs obligatoires
if ($pseudo === '' || $nom === '' || $prenom === '' || $mdp === '') {
    $erreurs[] = 'Veuillez renseigner tous les champs obligatoires';
}

// Pseudo : format, puis disponibilité (uniquement si le format est valide)
if ($pseudo !== '') {
    if (!preg_match('/^[A-Za-z0-9_.-]{' . PSEUDO_MIN . ',' . PSEUDO_MAX . '}$/', $pseudo)) {
        $erreurs[] = 'Le pseudo doit contenir minimum ' . PSEUDO_MIN .
            ' caractères (lettres, chiffres, . _ -)';
    } else {
        try {
            if (pseudoDejaUtilise($mon_pdo, $pseudo)) {
                $erreurs[] = 'Ce pseudo est déjà utilisé';
            }
        } catch (PDOException $e) {
            error_log('inscription_exe.php : ' . $e->getMessage());
            $erreurs[] = "Un problème est survenu lors de l'inscription";
        }
    }
}

// Nom et prénom
$regexNom = '/^[\p{L}][\p{L} \'’-]{0,' . (NOM_MAX - 1) . '}$/u';
if ($nom !== '' && !preg_match($regexNom, $nom)) {
    $erreurs[] = 'Nom invalide (lettres uniquement, ' . NOM_MAX . ' caractères maximum)';
}
if ($prenom !== '' && !preg_match($regexNom, $prenom)) {
    $erreurs[] = 'Prénom invalide (lettres uniquement, ' . NOM_MAX . ' caractères maximum)';
}

// Mail et téléphone (retourne les erreurs + le téléphone normalisé)
$coordonnees = verifierCoordonnees($mail, $tel);
$erreurs = array_merge($erreurs, $coordonnees['erreurs']);

// Mot de passe
if ($mdp !== $mdpConfirm) {
    $erreurs[] = 'Les 2 mots de passe doivent être identiques';
}
if (strlen($mdp) < MDP_MIN || strlen($mdp) > MDP_MAX) {
    $erreurs[] = 'Le mot de passe doit contenir minimum ' . MDP_MIN . ' caractères';
}
if (
    !preg_match('/[a-z]/', $mdp) || !preg_match('/[A-Z]/', $mdp) ||
    !preg_match('/\d/', $mdp) || !preg_match('/[^A-Za-z0-9]/', $mdp)
) {
    $erreurs[] = 'Le mot de passe doit contenir une minuscule, une majuscule, un chiffre et un caractère spécial';
}
if ($pseudo !== '' && stripos($mdp, $pseudo) !== false) {
    $erreurs[] = 'Le mot de passe ne doit pas contenir le pseudo';
}

// Toutes les vérifications sont faites AVANT cette redirection
if (!empty($erreurs)) {
    redirigerErreur(implode(' / ', $erreurs));
}

// 4. Protection des secrets : hachage du mdp, chiffrement du mail et du téléphone
$mdpHash     = hasher_mdp($mdp);
$mailChiffre = chiffrer_donnee($mail, $_ENV['chiffreKey']);
$telChiffre  = chiffrer_donnee($coordonnees['tel'], $_ENV['chiffreKey']);

if ($mdpHash === false || $mailChiffre === false || $telChiffre === false) {
    error_log('inscription_exe.php : échec du hachage ou du chiffrement');
    redirigerErreur("Un problème est survenu lors de l'inscription");
}

// 5. Insertion (requête préparée)
try {
    $requete = "INSERT INTO client (NOMCLI, PRENOM, PSEUDO, MDP, MAIL, TEL)
                VALUES (:nom, :prenom, :pseudo, :mdp, :mail, :telephone)";
    $statement = $mon_pdo->prepare($requete);
    $resultat = $statement->execute([
        ':nom'       => $nom,
        ':prenom'    => $prenom,
        ':pseudo'    => $pseudo,
        ':mdp'       => $mdpHash,
        ':mail'      => $mailChiffre,
        ':telephone' => $telChiffre,
    ]);
} catch (PDOException $e) {
    error_log('inscription_exe.php : ' . $e->getMessage());
    if ($e->getCode() === '23000') { // filet de sécurité : pseudo déjà pris
        redirigerErreur('Ce pseudo est déjà utilisé');
    }
    redirigerErreur("Un problème est survenu lors de l'inscription");
}

if ($resultat) {
    header('Location: index.php?page=connexionClient&info=' . urlencode('Inscription réussie. Veuillez vous authentifier'));
    exit;
}
redirigerErreur("Un problème est survenu lors de l'inscription");