<?php

namespace App\Middleware;

class Auth {
    public static function requireLogin() {
        if (!isset($_SESSION['id'])) {
            self::redirect('/login');
        }
    }

    public static function requireRole(...$roles) {
        self::requireLogin();
        $userRole = $_SESSION['rol'] ?? '';
        if (!in_array($userRole, $roles)) {
            http_response_code(403);
            echo 'No tienes permiso para acceder a esta página.';
            exit;
        }
    }

    public static function logout() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        self::redirect('/login');
    }

    public static function preventCache() {
        header('Cache-Control: no-cache, no-store, must-revalidate, private');
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    private static function redirect($url) {
        self::preventCache();
        header('Location: ' . $url);
        exit;
    }
}
