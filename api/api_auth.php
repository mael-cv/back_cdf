<?php
    function api_check($key, $key_hash) {
        if (password_verify($key, $key_hash)) {
            return true;
        } else {
            return false;
        }
    }

    function api_auth($bdd, $token, $key){
        $request = $bdd->prepare('SELECT * FROM `api_auth` WHERE `token` = :token');
        $request->execute(array('token' => $token));
        $res = $request->fetch();

        if ($res){
            if (password_verify($key, $res['key'])) {
                if (is_null($res['expire']) || new DateTime($res['expire']) > new DateTime()) {
                    return true;
                } else {
                    echo "Token expired";
                    return false;
                }
            }else{
                echo "Bad API login";
                return false;
            }   
        } else {
            return false;
        }
    }