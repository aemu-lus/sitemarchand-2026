<?php
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    /**
     * Hache un mot de passe avec HMAC-SHA256 et une clé secrète (pepper).
     */
    function hasher_mdp(string $mdp): string {
        return hash_hmac('sha256', $mdp, $_ENV['hashKey']);
    }

    function verifier_mdp(string $mdp, string $hash): bool {
        return hash_equals(hasher_mdp($mdp), $hash);
    }

    function chiffrer_donnee(string $donnee, string $cle_chiffrement): string {
        $iv = random_bytes(16);
        $chiffre = openssl_encrypt($donnee, 'aes-256-cbc', $cle_chiffrement, 0, $iv);
        return base64_encode($iv . base64_decode($chiffre));
    }

    function dechiffrer_donnee(string $donnee_chiffree, string $cle_dechiffrement): string {
        $brut = base64_decode($donnee_chiffree);
        $iv = substr($brut, 0, 16);
        $contenu = base64_encode(substr($brut, 16));
        return openssl_decrypt($contenu, 'aes-256-cbc', $cle_dechiffrement, 0, $iv);
    }