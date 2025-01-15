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
            if (!empty($_POST['title']) && !empty($_POST['date']) && !empty($_POST['description']) && !empty($_POST['categories']) && !empty($_POST['location']) && !empty($_POST['city_zip'])) {
                $title = htmlspecialchars($_POST['title']);
                $date = $_POST['date'];
                $description = htmlspecialchars($_POST['description']);
                $categories = explode(",", htmlspecialchars($_POST['categories']));
                $adresseComplete = $_POST['location'];  // L'adresse complète
                $villeCodePostale = $_POST['city_zip'];

                $req = $pdo->prepare("INSERT INTO `signalements` (`id`, `titre`, `categorie`, `description`, `files`, `date`, `adresse`, `city_zip`, `utils`, `post`, `user`) VALUES (NULL, ?, ?, ?, '', ?, ?, ?, '0', ?, NULL);");
                $req->execute(array($title, json_encode($categories), $description, $date, $adresseComplete, $villeCodePostale, date("Y-m-d H:i:s")));
                $res = array(
                    'out' => true,
                    'infos' => 'Signalement enregistré'
                );
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