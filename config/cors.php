<?php

return [
    // Toutes les routes API + Sanctum doivent être couvertes
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // OPTIONS est obligatoire pour les preflights CORS de Chrome
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // En dev local : tout autoriser. En prod, remplacer par l'URL de l'app.
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    // Authorization + Content-Type sont les headers envoyés par Flutter
    'allowed_headers' => ['Accept', 'Authorization', 'Content-Type', 'X-Requested-With'],

    'exposed_headers' => [],
    'max_age' => 3600,

    // false = on utilise des Bearer tokens (pas de cookies session)
    'supports_credentials' => false,
];
