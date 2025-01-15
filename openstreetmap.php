<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Liste des régions françaises avec leurs coordonnées géographiques approximatives
$regions = [
    ["name" => "Auvergne-Rhône-Alpes", "latitude" => 45.748, "longitude" => 4.846],
    ["name" => "Bourgogne-Franche-Comté", "latitude" => 47.040, "longitude" => 5.105],
    ["name" => "Bretagne", "latitude" => 48.202, "longitude" => -2.918],
    ["name" => "Centre-Val de Loire", "latitude" => 47.344, "longitude" => 1.800],
    ["name" => "Corse", "latitude" => 42.0, "longitude" => 9.0],
    ["name" => "Grand Est", "latitude" => 48.708, "longitude" => 6.674],
    ["name" => "Hauts-de-France", "latitude" => 50.0, "longitude" => 2.5],
    ["name" => "Île-de-France", "latitude" => 48.856, "longitude" => 2.352],
    ["name" => "Normandie", "latitude" => 49.414, "longitude" => 0.683],
    ["name" => "Nouvelle-Aquitaine", "latitude" => 44.837, "longitude" => -0.579],
    ["name" => "Occitanie", "latitude" => 43.611, "longitude" => 3.877],
    ["name" => "Pays de la Loire", "latitude" => 47.348, "longitude" => -0.775],
    ["name" => "Provence-Alpes-Côte d'Azur", "latitude" => 43.934, "longitude" => 6.042]
];

// Générer le GeoJSON
$geojson = [
    "type" => "FeatureCollection",
    "features" => []
];

// Ajouter chaque région en tant que point dans le GeoJSON
foreach ($regions as $region) {
    // Générer un nombre aléatoire entre 100 et 10000 pour "signalements"
    $signalements = rand(100, 10000);

    // Créer un point GeoJSON pour la région
    $feature = [
        "type" => "Feature",
        "geometry" => [
            "type" => "Point",
            "coordinates" => [
                $region['longitude'],
                $region['latitude']
            ]
        ],
        "properties" => [
            "name" => $region['name'],
            "description" => "signalements " . $signalements
        ]
    ];

    // Ajouter le point à la collection de features
    $geojson['features'][] = $feature;
}

// Configurer l'en-tête pour indiquer que c'est du JSON
header('Content-Type: application/json');

// Retourner le GeoJSON
echo json_encode($geojson, JSON_PRETTY_PRINT);
?>
