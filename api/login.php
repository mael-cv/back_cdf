<?php
    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        //var 
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        try {
            // Vérifier si l'email existe
            $stmt = $pdo->prepare("SELECT id, password FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
                // Vérification du mot de passe avec password_verify
                if (password_verify($password, $user['password'])) {
                    // Génération d'un token de session aléatoire
                    $session_token = // bin2hex(random_bytes(32));
                    $expire = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
                    // Insertion de la session dans la table session
                    $insertStmt = $pdo->prepare("INSERT INTO session (userid, session_token, expire, status) VALUES (:user_id, :session_token, :expire, :status)");
                    // $insertStmt->bindParam(':userid', $user['id']);
                    // $insertStmt->bindParam(':session_token', $session_token);
                    // $insertStmt->bindParam(':expire', $expire);
                    // $insertStmt->bindValue(':status', 1, PDO::PARAM_INT);
                    // $insertStmt->execute();
    
                    // Retourner une réponse JSON avec le token de session
                    echo json_encode([
                        'out' => true,
                        'infos' => 'Connexion réussie.',
                        'data' => $session_token
                    ]);
                } else {
                    // Mot de passe incorrect
                    echo json_encode([
                        'out' => false,
                        'infos' => 'Mot de passe incorrect.'
                    ]);
                }
            } else {
                // Email non trouvé
                echo json_encode([
                    'out' => false,
                    'infos' => 'Email non trouvé.'
                ]);
            }
        }
    }