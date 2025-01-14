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