<?php
// Générer un token unique
function generateToken() {
    return bin2hex(random_bytes(32)); // Génère un token de 64 caractères
}

// Générer une clé API aléatoire
function generateApiKey() {
    return bin2hex(random_bytes(32)); // Génère une clé API de 64 caractères
}

// Générer un token et une clé API
$token = generateToken();
$apiKey = generateApiKey();

// Chiffrer la clé API avec password_hash (utilisé pour vérifier la clé lors de l'authentification)
$hashedApiKey = password_hash($apiKey, PASSWORD_DEFAULT);

// Afficher le token clair, la clé API claire, et la clé API chiffrée
echo 'token '. $token.
    '<br>api_key '.$apiKey.
    '<br>hashed_api_key '.$hashedApiKey;
?>
