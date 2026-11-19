<?php
/**
 * Traitement de l'inscription d'un client (côté serveur).
 *
 * Étapes : assainissement des données, validation (pseudo, nom, prénom,
 * mail, téléphone, mot de passe), protection des secrets (hachage du mot de
 * passe, chiffrement du mail et du téléphone) puis insertion en base avec une
 * requête préparée. En cas d'échec, redirection vers le formulaire avec un
 * message d'erreur.
 *
 * Dépendances :
 * - info_bdd.php : fournit $mon_pdo, hasher_mdp() et chiffrer_donnee() ;
 * - verifierCoordonnees.php : fournit verifierCoordonnees().
 *
 * @package SiteMarchand
 */

require_once __DIR__ . '/info_bdd.php';
require_once __DIR__ . '/verifierCoordonnees.php';

session_save_path(__DIR__ . '/tmp');
session_start();

// Limites à adapter à la taille réelle des colonnes de la table client

/** @var int Longueur minimale du pseudo. */
const PSEUDO_MIN = 3;
/** @var int Longueur maximale du pseudo. */
const PSEUDO_MAX = 30;
/** @var int Longueur maximale du nom et du prénom. */
const NOM_MAX = 50;
/** @var int Longueur minimale du mot de passe. */
const MDP_MIN = 12;
/** @var int Longueur maximale du mot de passe (limite de bcrypt). */
const MDP_MAX = 72;

/**
 * Redirige vers le formulaire d'inscription avec un message d'erreur,
 * puis arrête le script.
 *
 * Le exit est indispensable : sans lui, le script continuerait après
 * header() et l'insertion pourrait quand même avoir lieu.
 *
 * @param string $message Message d'erreur à afficher à l'utilisateur.
 *
 * @return void
 */
function redirigerErreur(string $message): void
{
    header('Location: index.php?page=inscription&erreur=' . urlencode($message));
    exit;
}

// 1. Accepter uniquement une soumission de formulaire
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigerErreur("Le formulaire n'a pas été soumis correctement");
}

// 2. Récupération et assainissement (trim, pas d'htmlspecialchars avant la BDD :
//    l'échappement HTML se fait à l'affichage, pas au stockage)
$pseudo     = trim($_POST['pseudo'] ?? '');
$nom        = trim($_POST['nom'] ?? '');
$prenom     = trim($_POST['prenom'] ?? '');
$mail       = trim($_POST['mail'] ?? '');
$tel        = trim($_POST['tel'] ?? '');
$mdp        = $_POST['password'] ?? '';    // jamais modifié ni trimé
$mdpConfirm = $_POST['mdpConfirm'] ?? '';

// 3. Vérifications : on accumule toutes les erreurs
$erreurs = [];

// Champs obligatoires
if ($pseudo === '' || $nom === '' || $prenom === '' || $mdp === '') {
    $erreurs[] = 'Veuillez renseigner tous les champs obligatoires';
}

// Pseudo
if ($pseudo !== '' && !preg_match('/^[A-Za-z0-9_.-]{' . PSEUDO_MIN . ',' . PSEUDO_MAX . '}$/', $pseudo)) {
    $erreurs[] = 'Le pseudo doit contenir entre ' . PSEUDO_MIN . ' et ' . PSEUDO_MAX
        . ' caractères (lettres, chiffres, . _ -)';
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

if (!empty($erreurs)) {
    redirigerErreur(implode(' / ', $erreurs));
}

// 4. Protection des secrets : hachage du mdp, chiffrement du mail et du téléphone
$mdpHash     = hasher_mdp($mdp);
$mailChiffre = chiffrer_donnee($mail, $_ENV['encryptKey']);
$telChiffre  = chiffrer_donnee($coordonnees['tel'], $_ENV['encryptKey']);

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
    // Le détail de l'erreur va dans les logs, jamais à l'écran
    error_log('inscription_exe.php : ' . $e->getMessage());
    if ($e->getCode() === '23000') { // contrainte d'unicité (pseudo déjà pris)
        redirigerErreur('Ce pseudo est déjà utilisé');
    }
    redirigerErreur("Un problème est survenu lors de l'inscription");
}

if ($resultat) {
    header('Location: index.php?page=connexionClient&info=' . urlencode('Inscription réussie. Veuillez vous authentifier'));
    exit;
}
redirigerErreur("Un problème est survenu lors de l'inscription");