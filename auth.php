<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    public function checkAccess() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: index.php");
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
?>
