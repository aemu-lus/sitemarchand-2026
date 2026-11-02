<?php
/**
 * Gestion des secrets du site marchand.
 *
 * Regroupe les fonctions de protection des données sensibles (partie IV
 * « Gestion des secrets » du projet de sécurisation, BTS SIO SLAM - B3) :
 *  - partie A : hachage et vérification des mots de passe ;
 *  - partie B : chiffrement et déchiffrement des données à caractère
 *    personnel (nom, prénom, e-mail, téléphone) au sens du RGPD.
 *
 * Les clés (hachage, chiffrement, déchiffrement) ne sont jamais écrites en
 * dur dans le code : elles sont lues dans le fichier `.env` (non versionné,
 * listé dans `.gitignore`) grâce à la bibliothèque vlucas/phpdotenv.
 *
 * @package    SiteMarchand
 * @subpackage Securite
 * @author     Emy Philippe-Durand
 * @version    1.0
 * @see        https://github.com/vlucas/phpdotenv
 */

require __DIR__ . '/vendor/autoload.php';

// Chargement du fichier .env à la racine du projet.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Clé de hachage des mots de passe, lue dans le fichier `.env`.
 *
 * @var string|false
 */
$cleHachage = getenv('hashKey');

/**
 * Clé de chiffrement des données personnelles, lue dans le fichier `.env`.
 *
 * @var string|false
 */
$cleChiffrement = getenv('encryptKey');


/**
 * Hache un mot de passe en clair avant son stockage en base de données.
 *
 * Le hachage est une opération à sens unique : il est impossible de
 * retrouver le mot de passe d'origine à partir du hash retourné, y compris
 * pour l'administrateur de la base. Même en cas de fuite de la table,
 * les mots de passe ne sont donc pas lisibles (question 4.A.1).
 *
 * @param string $mdp Mot de passe saisi par l'utilisateur, en clair.
 *
 * @return string Hash du mot de passe, à enregistrer dans la colonne dédiée
 *                (prévoir une colonne assez longue, voir `varchar` dans init.sql).
 */
function hasher_mdp(string $mdp): string
{
    // Code à compléter
    return hash_du_mdp;
}

/**
 * Vérifie qu'un mot de passe en clair correspond à un hash enregistré.
 *
 * Utilisée à la connexion : le mot de passe saisi est comparé au hash
 * stocké en base, sans jamais déchiffrer ce dernier.
 *
 * @param string $mdp  Mot de passe saisi par l'utilisateur, en clair.
 * @param string $hash Hash du mot de passe récupéré en base de données.
 *
 * @return bool `true` si le hash correspond au mot de passe,
 *              `false` dans le cas contraire.
 */
function verifier_mdp(string $mdp, string $hash): bool
{
    // Code à compléter
}

/**
 * Chiffre une donnée à caractère personnel avant son stockage en base.
 *
 * Contrairement au hachage, le chiffrement est réversible : la donnée doit
 * pouvoir être relue (envoi d'un e-mail, appel téléphonique, affichage du
 * profil) tout en restant illisible dans la base de données (question 4.B.1).
 * Il s'agit d'un chiffrement symétrique : la même clé sert à chiffrer et à
 * déchiffrer (question 4.B.2).
 *
 * @param string $donnee         Donnée personnelle en clair (nom, prénom,
 *                               e-mail, téléphone).
 * @param string $cle_chiffrement Clé de chiffrement chargée depuis le `.env`.
 *
 * @return string Donnée chiffrée, prête à être enregistrée en base.
 */
function chiffrer_donnee(string $donnee, string $cle_chiffrement): string
{
    // Code à compléter
    return donnee_chiffree;
}

/**
 * Déchiffre une donnée à caractère personnel stockée en base.
 *
 * Opération inverse de {@see chiffrer_donnee()} : la clé fournie doit être
 * celle qui a servi au chiffrement.
 *
 * @param string $donnee_chiffree  Donnée chiffrée lue en base de données.
 * @param string $cle_dechiffrement Clé de déchiffrement chargée depuis le `.env`.
 *
 * @return string Donnée personnelle en clair.
 */
function dechiffrer_donnee(string $donnee_chiffree, string $cle_dechiffrement): string
{
    // Code à compléter
    return donnee_claire;
}
