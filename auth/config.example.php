<?php
/*
 * Configuration de la passerelle d'authentification (auth.php).
 * Ce fichier est du PHP : son contenu n'est jamais affiché dans le navigateur.
 * Ne le mets PAS sur GitHub (il contient des secrets).
 */
return [
    // Origine autorisée à appeler la passerelle (ta page GitHub Pages)
    'allowed_origin' => 'https://danilblm.github.io',

    // Google : « ID client OAuth » (type Application Web) créé dans Google Cloud Console
    'google_client_id' => '',

    // GitHub : OAuth App créée dans GitHub → Settings → Developer settings → OAuth Apps
    'github_client_id'     => '',
    'github_client_secret' => '',

    // CAS : adresse du serveur CAS (sans /login à la fin)
    'cas_server' => 'https://cas.univ-paris8.fr/cas',

    /*
     * Association « compte externe » → clé API Omeka.
     * Format de l'identifiant : google:<email>, github:<login>, cas:<login>
     * Un compte absent de la liste peut se connecter, mais en lecture seule.
     */
    'users' => [
        // 'google:ton.email@gmail.com' => ['key_identity' => '...', 'key_credential' => '...'],
        // 'github:danilblm'            => ['key_identity' => '...', 'key_credential' => '...'],
        // 'cas:ton_login_paris8'       => ['key_identity' => '...', 'key_credential' => '...'],
    ],
];
