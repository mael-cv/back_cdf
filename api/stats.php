<?php
    include 'functions/stats.php';
    if(!empty($_GET['action'])){
        if ($_GET['action'] == "categories") {
            $res = array(
                'out' => true,
                'infos' => 'By Categorie : ',
                'data' => getStatisticsByCategory($pdo)
            );
        }elseif ($_GET['action'] == "cities") {
            $res = array(
                'out' => true,
                'infos' => 'By Cities : ',
                'data' => getStatisticsByCity($pdo)
            );
            getStatisticsByCity($pdo);
        }elseif ($_GET['action'] == "departments") {
            $res = array(
                'out' => true,
                'infos' => 'By Departments : ',
                'data' => getStatisticsByDepartment($pdo)
            );
        }elseif ($_GET['action'] == "regions") {
            $res = array(
                'out' => true,
                'infos' => 'By Regions : ',
                'data' => getStatisticsByRegion($pdo)
            );
            getStatisticsByRegion($pdo);
        }elseif ($_GET['action'] == "all") {
            $res = array(
                'out' => true,
                'infos' => 'By All : ',
                'data' => "en cours..."
            );
        }
        else{
            $res = array(
                'out' => false,
                'infos' => 'Not found Action !'
            );
        }
    }else {
        $res = array(
            'out' => false,
            'infos' => 'Bad Request !'
        );
    }