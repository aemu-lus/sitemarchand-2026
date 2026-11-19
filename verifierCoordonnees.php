<?php
/**
 * Validation des coordonnées (mail et téléphone) lors de l'inscription.
 *
 * @package SiteMarchand
 */

/**
 * Valide l'adresse mail et le numéro de téléphone saisis.
 *
 * Le mail doit respecter un format valide (254 caractères maximum).
 * Le téléphone doit être un numéro français ; les séparateurs (espaces,
 * points, tirets) sont retirés et le préfixe +33 / 0033 est converti en 0.
 *
 * @param string $mail Adresse mail saisie (déjà passée dans trim()).
 * @param string $tel  Numéro de téléphone saisi (déjà passé dans trim()).
 *
 * @return array{erreurs: string[], tel: string} Tableau contenant :
 *               - 'erreurs' : messages d'erreur (vide si tout est valide) ;
 *               - 'tel'     : téléphone normalisé au format 0XXXXXXXXX
 *                             (valeur nettoyée mais non normalisée si invalide).
 */
function verifierCoordonnees(string $mail, string $tel): array
{
    $erreurs = [];

    // --- Mail ---
    if ($mail === '') {
        $erreurs[] = "L'adresse mail est obligatoire";
    } elseif (strlen($mail) > 254 || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Le format de l'adresse mail est invalide";
    }

    // --- Téléphone ---
    // On retire les séparateurs courants (espaces, points, tirets) avant de tester
    $telNormalise = preg_replace('/[ .\-]/', '', $tel);

    if ($telNormalise === '') {
        $erreurs[] = 'Le numéro de téléphone est obligatoire';
    } elseif (!preg_match('/^(?:0|\+33|0033)[1-9]\d{8}$/', $telNormalise)) {
        $erreurs[] = 'Le numéro de téléphone doit être un numéro français valide (ex : 06 12 34 56 78)';
    } else {
        // Forme unique 0XXXXXXXXX pour le stockage
        $telNormalise = preg_replace('/^(?:\+33|0033)/', '0', $telNormalise);
    }

    return ['erreurs' => $erreurs, 'tel' => $telNormalise];
}
