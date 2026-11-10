                                                                  
<?php
    require __DIR__ . '/vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    $var = $_ENV['hashKey']; //@var string|false
    $cle_chiffrement = $_ENV['chiffreKey']; //@var string|false
    $cle_dechiffrement = $_ENV['dechiffreKey']; //@var string|false
    $mdp=$_POST['password']; //@var string|false



    function hasher_mdp (string $mdp): string { //@param string $mdp Mot de passe saisi par l'utilisateur, en clair.
        $hash_du_mdp = hash($mdp, $var);  //@param hash le mdp recupere depuis la fonction avec une cle de hash 
        return $hash_du_mdp;             //@return le mdp haché
}

    function verifier_mdp (string $mdp, string $hash): bool { //@param string $mdp  Mot de passe saisi par l'utilisateur, en clair.
                                                                //@param string $hash Hash du mot de passe récupéré en base de données.
        return password_verify($mdp, $hash);            //@return bool `true` si le hash correspond au mot de passe,
                                                                   //`false` dans le cas contraire.
}   
    function chiffrer_donnee (string $donnee, string $cle_chiffrement): string { //@param string $donnee Donnée personnelle en clair (nom, prénom,e-mail, téléphone).
                                                                                //@param string $cle_chiffrement Clé de chiffrement chargée depuis le `.env`.
        $donnee_chiffree = openssl_encrypt($donnee, 'aes-256-cbc', $cle_chiffrement);
        return $donnee_chiffree;                                                //@return string Donnée chiffrée, prête à être enregistrée en base.
}

    function dechiffrer_donnee (string $donnee_chiffree, string $cle_dechiffrement): string { //@param string $donnee_chiffree  Donnée chiffrée lue en base de données.
                                                                                                //@param string $cle_dechiffrement Clé de déchiffrement chargée depuis le `.env`.
        $donnee_claire = openssl_decrypt($donnee_chiffree, 'aes-256-cbc', $cle_dechiffrement);
        return $donnee_claire;                                                                 //@return string Donnée personnelle en clair.
}

?>

/**
 * Gestion des secrets du site marchand.
 *
 * Regroupe les fonctions de protection des données sensibles (partie IV
 * « Gestion des secrets » du projet de sécurisation, BTS SIO SLAM - B3) :
 *  - partie A : hachage et vérification des mots de passe ;
 *  - partie B : chiffrement et déchiffrement des données à caractère
 *    personnel (nom, prénom, e-mail, téléphone) au sens du RGPD.
 *
 * @package    SiteMarchand
 * @subpackage Securite
 * @author     Emy Philippe-Durand
 * @version    1.0
 * @see        https://github.com/vlucas/phpdotenv
 */