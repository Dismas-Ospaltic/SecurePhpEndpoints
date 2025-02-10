<?php
// require_once __DIR__ . '/../../config/database.php';

// class Token {
//     private $mysqli;

//     public function __construct($mysqli) {
//         $this->mysqli = $mysqli;
//     }

//     public function storeRefreshToken($user_id, $token, $expires_at) {
//         $stmt = $this->mysqli->prepare("INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
//         $stmt->bind_param("iss", $user_id, $token, $expires_at);
//         return $stmt->execute();
//     }

//     public function getRefreshToken($token) {
//         $stmt = $this->mysqli->prepare("SELECT user_id FROM refresh_tokens WHERE token = ? AND revoked_at IS NULL");
//         $stmt->bind_param("s", $token);
//         $stmt->execute();
//         return $stmt->get_result()->fetch_assoc();
//     }

//     public function revokeToken($token) {
//         $stmt = $this->mysqli->prepare("UPDATE refresh_tokens SET revoked_at = NOW() WHERE token = ?");
//         $stmt->bind_param("s", $token);
//         return $stmt->execute();
//     }
// }





require_once __DIR__ . '/../../config/database.php';

class Token {
    private $mysqli;

    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    // Store refresh token
    public function storeRefreshToken($user_id, $token, $expires_at) {
        $stmt = $this->mysqli->prepare("INSERT INTO refresh_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user_id, $token, $expires_at);
        return $stmt->execute();
    }

    // Get refresh token and check expiration
    public function getRefreshToken($token) {
        $stmt = $this->mysqli->prepare("SELECT user_id, expires_at FROM refresh_tokens WHERE token = ? AND revoked_at IS NULL");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        // If no token found or token has expired
        if (!$result || strtotime($result['expires_at']) < time()) {
            return null; // Token not found or expired
        }

        return $result; // Return token data
    }

    public function revokeToken($token) {
        // Check if token is valid (exists, not revoked, and not expired)
        $stmt = $this->mysqli->prepare("SELECT id, expires_at, revoked_at FROM refresh_tokens WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
    
        if (!$result) {
            error_log("Token not found in database.");
            return false; // Token is invalid (does not exist)
        }
    
        // Check if the token is already revoked
        if (!is_null($result['revoked_at'])) {
            error_log("Token already revoked at: " . $result['revoked_at']);
            return false; 
        }
    
        // Check if the token is expired
        if (strtotime($result['expires_at']) < time()) {
            error_log("Token expired at: " . $result['expires_at']);
            return false; 
        }
    
        // Revoke the token
        $stmt = $this->mysqli->prepare("UPDATE refresh_tokens SET revoked_at = NOW() WHERE token = ?");
        $stmt->bind_param("s", $token);
        
        if ($stmt->execute()) {
            error_log("Token successfully revoked.");
            return true;
        } else {
            error_log("Failed to revoke token.");
            return false;
        }
    }
    
    


    // Optional method to check if the token is expired
    public function isTokenExpired($token) {
        $tokenData = $this->getRefreshToken($token);
        return $tokenData === null; // Return true if token is expired or invalid
    }
}

?>
