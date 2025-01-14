<?php
    if(!empty($_GET['action'])){
        if ($_GET['action'] == 'categorie') {
            $request = $pdo->prepare('SELECT * FROM `categorie`');
            $request->execute();
    
            $res = array(
                'out' => true,
                'infos' => 'categories : ',
                'data' => $request->fetchAll()
            );
        }elseif ($_GET['action'] == 'post') {
            if (!empty($_POST['title']) & !empty($_POST['date']) & !empty($_POST['description']) & !empty($_POST['categories']) & !empty($_POST['location']) & !empty($_POST('city_zip'))) {
                $title = htmlspecialchars($_POST['title']);
                $date = $_POST['date'];
                $description = htmlspecialchars($_POST['description']);
                $categories = explode(",", htmlspecialchars($_POST['categories']));
                $adresseComplete = $_POST['location'];  // L'adresse complète
                $villeCodePostale = $_POST['city_zip'];

                // Séparer la ville et le code postal de la chaîne "VILLE (00000)"
                preg_match('/^(.*)\s\((\d{5})\)$/', $villeCodePostale, $matches);
                if (count($matches) == 3) {
                    $ville = trim($matches[1]); // La ville
                    $codePostal = $matches[2];  // Le code postal
                } else {
                    $res = array(
                        'out' => false,
                        'infos' => 'Ville ou Code Postal invalide...'
                    );
                    exit;
                }

                // Curl to verify adress : 
                $apiUrl = "https://api-adresse.data.gouv.fr/search/";   
                $params = [
                    'q' => $adresseComplete,  // L'adresse complète
                    'postcode' => $codePostal,  // Le code postal
                    'city' => $ville,           // La ville
                    'limit' => 1,               // Limiter à 1 résultat
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                $response = curl_exec($ch);                

                if (curl_errno($ch)) {
                    $res = array(
                        'out' => false,
                        'infos' => 'ERREUR 500 - INTERNAL SERVER ERROR - ('.curl_error($ch).')'
                    );
                    exit;
                }

                curl_close($ch);

                // Décoder la réponse JSON
                $data = json_decode($response, true);

                // Vérifier si l'API a retourné des résultats
                if (isset($data['features']) && count($data['features']) > 0) {
                    $address = $data['features'][0];
                    // echo "Adresse validée : " . $address['properties']['label'];
                    // Si l'adresse est valide
                    $req = $pdo->prepare("INSERT INTO `signalements` (`id`, `titre`, `categorie`, `description`, `files`, `date`, `adresse`, `city_zip`, `utils`, `post`, `user`) VALUES (NULL, ?, ?, ?, '', ?, ?, ?, '0', ?, NULL);");
                    $req->execute(array($title, $categories, $description, $date, $adresseComplete, $villeCodePostale, date("Y-m-d H:i:s")));

                    $req = array(
                        'out' => true,
                        'infos' => 'Signalement enregistré'
                    );
                } else {
                    $res = array(
                        'out' => false,
                        'infos' => 'Adresse invalide...'
                    );
                }
            }else{
                $res = array(
                    'out' => false,
                    'infos' => 'Veuillez remplir tous les champs...'
                );
            }
        }else{
            $res = array(
                'out' => false,
                'infos' => 'Bad Request !'
            );
        }
    }else{
        $res = array(
            'out' => false,
            'infos' => 'Bad Request !'
        );
    }