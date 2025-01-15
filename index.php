<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Content-Type: application/json; charset=UTF-8");
    
    include 'config/db.php';
    include 'api/api_auth.php';

    if (!empty($_GET['token']) & !empty($_GET['key'])) {
        if (api_auth($pdo, $_GET['token'], $_GET['key'])) {
            if (!empty($_GET['api'])) {
                if ($_GET['api'] == 'stats') {
                    include('api/stats.php');
                }else if ($_GET['api'] == 'map'){
                    include('api/map.php'); 
                }else if ($_GET['api'] == 'auth'){
                    include('api/auth.php');
                }else if ($_GET['api'] == 'add'){
                    include('api/add.php');
                }else if ($_GET['api'] == 'login'){
                    include('api/login.php');
                }else {
                    $res = array(
                        'out' => false,
                        'infos' => 'Bad Request !'
                    );
                }
            }else{
                $res = array(
                    'out' => false,
                    'infos' => 'Bad API !'
                );
            }
        }else{
            $res = array(
                'out' => false,
                'infos' => 'Bad API !'
            );
        }
    }else{
        $res = array(
            'out' => false,
            'infos' => 'Bad Request !'
        );
    }

    echo json_encode($res);
?>