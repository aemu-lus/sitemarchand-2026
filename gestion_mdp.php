<?php
/**
 * Gestion des secrets : hachage des mots de passe et chiffrement des données.
 *
 * Les clés (hashKey, chiffreKey, dechiffreKey) sont lues dans le fichier .env.
 *
 * @package SiteMarchand
 */

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Hache un mot de passe.
 *
 * @param string $mdp Mot de passe en clair.
 *
 * @return string Hash à stocker en base (colonne de 255 caractères conseillée).
 */
function hasher_mdp(string $mdp): string
{
    $mdp_poivre = hash_hmac('sha256', $mdp, $_ENV['hashKey']);
    return password_hash($mdp_poivre, PASSWORD_DEFAULT);
}

/**
 * Vérifie un mot de passe saisi par rapport au hash stocké en base.
 *
 * @param string $mdp  Mot de passe saisi (en clair).
 * @param string $hash Hash enregistré en base, créé par hasher_mdp().
 *
 * @return bool true si le mot de passe correspond au hash.
 */
function verifier_mdp(string $mdp, string $hash): bool
{
    $mdp_poivre = hash_hmac('sha256', $mdp, $_ENV['hashKey']);
    return password_verify($mdp_poivre, $hash);
}

/**
 * Chiffre une donnée (AES-256-CBC) avec un vecteur d'initialisation aléatoire.
 *
 * @param string $donnee          Donnée en clair.
 * @param string $cle_chiffrement Clé de chiffrement (chiffreKey du .env).
 *
 * @return string Donnée chiffrée, encodée en base64.
 */
function chiffrer_donnee(string $donnee, string $cle_chiffrement): string
{
    $iv = random_bytes(16);
    $chiffre = openssl_encrypt($donnee, 'aes-256-cbc', $cle_chiffrement, 0, $iv);
    return base64_encode($iv . base64_decode($chiffre));
}

/**
 * Déchiffre une donnée produite par chiffrer_donnee().
 *
 * @param string $donnee_chiffree  Donnée chiffrée (base64, IV inclus).
 * @param string $cle_dechiffrement Clé de déchiffrement (dechiffreKey du .env).
 *
 * @return string Donnée en clair.
 */
function dechiffrer_donnee(string $donnee_chiffree, string $cle_dechiffrement): string
{
    $brut = base64_decode($donnee_chiffree);
    $iv = substr($brut, 0, 16);
    $contenu = base64_encode(substr($brut, 16));
    return openssl_decrypt($contenu, 'aes-256-cbc', $cle_dechiffrement, 0, $iv);
}