<?php

function getStatisticsByCategory($conn) {
    $sql = "SELECT categorie, COUNT(*) as count FROM signalements GROUP BY categorie ORDER BY count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories = json_decode($row['categorie'], true);
            foreach ($categories as $category) {
                if (!isset($data[$category])) {
                    $data[$category] = 0;
                }
                $data[$category]++;
            }
        }
    }

    // Convertir le tableau associatif en tableau de résultats
    $result = [];
    foreach ($data as $category => $count) {
        $result[] = ['categorie' => $category, 'count' => $count];
    }

    return $result;
}

function extractCityAndPostalCode($city_zip) {
    // Utilisation d'une expression régulière pour extraire la ville et le code postal
    if (preg_match('/^(.*) \((\d{5})\)$/', $city_zip, $matches)) {
        return [
            'city' => $matches[1],   // Ville (avant l'espace)
            'postal_code' => $matches[2]  // Code postal (5 chiffres après la parenthèse)
        ];
    }

    return null;  // Si le format est incorrect
}

// Fonction pour récupérer les signalements par ville
function getStatisticsByCity($conn) {
    $sql = "SELECT city_zip, COUNT(*) as count FROM signalements GROUP BY city_zip ORDER BY count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cityInfo = extractCityAndPostalCode($row['city_zip']);
            if ($cityInfo) {
                $city = $cityInfo['city'];
                $postalCode = $cityInfo['postal_code'];
                $data[] = ['city' => $city, 'postal_code' => $postalCode, 'count' => $row['count']];
            }
        }
    }

    return $data;
}

// Fonction pour récupérer les signalements par département
// Fonction pour récupérer les signalements par département
function getStatisticsByDepartment($conn) {
    $sql = "SELECT SUBSTRING(city_zip, -6, 2) as department, COUNT(*) as count FROM signalements GROUP BY department ORDER BY count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
    }

    return $data;
}

// Fonction pour récupérer les signalements par région
function getStatisticsByRegion($conn) {
    // Vous devrez peut-être ajouter un tableau ou une logique pour associer le code postal à une région
    // Pour cet exemple, nous allons simplement utiliser une approche similaire à celle des départements.
    
    // Utilisation d'un tableau de correspondance entre les départements et les régions
    // Exemple : tableau associant chaque département à une région
    $departmentToRegion = [
        '01' => 'Auvergne-Rhône-Alpes',
        '02' => 'Hauts-de-France',
        '03' => 'Auvergne-Rhône-Alpes',
        '04' => 'Provence-Alpes-Côte d\'Azur',
        '05' => 'Provence-Alpes-Côte d\'Azur',
        '06' => 'Provence-Alpes-Côte d\'Azur',
        '07' => 'Auvergne-Rhône-Alpes',
        '08' => 'Grand Est',
        '09' => 'Occitanie',
        '10' => 'Grand Est',
        '11' => 'Occitanie',
        '12' => 'Occitanie',
        '13' => 'Provence-Alpes-Côte d\'Azur',
        '14' => 'Normandie',
        '15' => 'Auvergne-Rhône-Alpes',
        '16' => 'Nouvelle-Aquitaine',
        '17' => 'Nouvelle-Aquitaine',
        '18' => 'Centre-Val de Loire',
        '19' => 'Nouvelle-Aquitaine',
        '2A' => 'Corse',
        '2B' => 'Corse',
        '21' => 'Bourgogne-Franche-Comté',
        '22' => 'Bretagne',
        '23' => 'Nouvelle-Aquitaine',
        '24' => 'Nouvelle-Aquitaine',
        '25' => 'Bourgogne-Franche-Comté',
        '26' => 'Auvergne-Rhône-Alpes',
        '27' => 'Normandie',
        '28' => 'Centre-Val de Loire',
        '29' => 'Bretagne',
        '30' => 'Occitanie',
        '31' => 'Occitanie',
        '32' => 'Occitanie',
        '33' => 'Nouvelle-Aquitaine',
        '34' => 'Occitanie',
        '35' => 'Bretagne',
        '36' => 'Centre-Val de Loire',
        '37' => 'Centre-Val de Loire',
        '38' => 'Auvergne-Rhône-Alpes',
        '39' => 'Bourgogne-Franche-Comté',
        '40' => 'Nouvelle-Aquitaine',
        '41' => 'Centre-Val de Loire',
        '42' => 'Auvergne-Rhône-Alpes',
        '43' => 'Auvergne-Rhône-Alpes',
        '44' => 'Pays de la Loire',
        '45' => 'Centre-Val de Loire',
        '46' => 'Occitanie',
        '47' => 'Nouvelle-Aquitaine',
        '48' => 'Occitanie',
        '49' => 'Pays de la Loire',
        '50' => 'Normandie',
        '51' => 'Grand Est',
        '52' => 'Grand Est',
        '53' => 'Pays de la Loire',
        '54' => 'Grand Est',
        '55' => 'Grand Est',
        '56' => 'Bretagne',
        '57' => 'Grand Est',
        '58' => 'Bourgogne-Franche-Comté',
        '59' => 'Hauts-de-France',
        '60' => 'Hauts-de-France',
        '61' => 'Normandie',
        '62' => 'Hauts-de-France',
        '63' => 'Auvergne-Rhône-Alpes',
        '64' => 'Nouvelle-Aquitaine',
        '65' => 'Occitanie',
        '66' => 'Occitanie',
        '67' => 'Grand Est',
        '68' => 'Grand Est',
        '69' => 'Auvergne-Rhône-Alpes',
        '70' => 'Bourgogne-Franche-Comté',
        '71' => 'Bourgogne-Franche-Comté',
        '72' => 'Pays de la Loire',
        '73' => 'Auvergne-Rhône-Alpes',
        '74' => 'Auvergne-Rhône-Alpes',
        '75' => 'Île-de-France',
        '76' => 'Normandie',
        '77' => 'Île-de-France',
        '78' => 'Île-de-France',
        '79' => 'Nouvelle-Aquitaine',
        '80' => 'Hauts-de-France',
        '81' => 'Occitanie',
        '82' => 'Occitanie',
        '83' => 'Provence-Alpes-Côte d\'Azur',
        '84' => 'Provence-Alpes-Côte d\'Azur',
        '85' => 'Pays de la Loire',
        '86' => 'Nouvelle-Aquitaine',
        '87' => 'Nouvelle-Aquitaine',
        '88' => 'Grand Est',
        '89' => 'Bourgogne-Franche-Comté',
        '90' => 'Bourgogne-Franche-Comté',
        '91' => 'Île-de-France',
        '92' => 'Île-de-France',
        '93' => 'Île-de-France',
        '94' => 'Île-de-France',
        '95' => 'Île-de-France',
        '971' => 'Guadeloupe',
        '972' => 'Martinique',
        '973' => 'Guyane',
        '974' => 'La Réunion',
        '976' => 'Mayotte'
    ];
    
    $sql = "SELECT SUBSTRING(city_zip, -6, 2) as department, COUNT(*) as count FROM signalements GROUP BY department ORDER BY count DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $data = [];
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $department = $row['department'];
            $region = isset($departmentToRegion[$department]) ? $departmentToRegion[$department] : 'Unknown';
            if (!isset($data[$region])) {
                $data[$region] = 0;
            }
            $data[$region] += $row['count'];
        }
    }

    // Convertir le tableau associatif en tableau de résultats
    $result = [];
    foreach ($data as $region => $count) {
        $result[] = ['region' => $region, 'count' => $count];
    }

    return $result;
}


?>