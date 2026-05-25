<?php

namespace App\Controller\Auth;

use App\Database;
use estado_general;
use model\users;
use rol_usuario;

require_once __DIR__ . '/../../model/users.php';
require_once __DIR__ . '/../../../resources/enum/rol_usuario.php';
require_once __DIR__ . '/../../../resources/enum/estado_general.php';

class LoginController
{
    private users $model;

    public function __construct()
    {
        $db = Database::getConnection();
        $this->model = new users($db);
    }

    public function handle(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $this->BackWithError('Completa todos los campos.');
        }

        $user = $this->model->FindById($email);

        if (!$user) {
            $this->BackWithError('correo o contraseña incorrectos.');
        }

        $storedPassword = $user['password'] ?? '';

        if (!password_verify($password, $storedPassword)) {
            // Si la contraseña estaba en texto plano, migrarla a hash
            if (hash_equals($storedPassword, $password)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->model->UpdatePassword((int) $user['id'], $newHash);
                $storedPassword = $newHash;
            } else {
                $this->BackWithError('correo o contraseña incorrectos.');
            }
        }

        if ($user['estado'] !== estado_general::ACTIVO->value) {
            $this->BackWithError('Este usuario esta inactivo.');
        }

        // iniciar sesion
        session_regenerate_id(true);
        $_SESSION['id'] = $user['id'];
        $_SESSION['nombres'] = $user['nombres'];
        $_SESSION['apellidos'] = $user['apellidos'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['email'] = $user['email'];

        if ($remember) {
            setcookie('remember_email', $user['email'], time() + (86400 * 7), "/", "", true, true);
        }

        $this->RedirectByRol($user['rol']);
    }

    private function RedirectByRol(string $rol): void
    {
        $rutas = [
            rol_usuario::ADMIN->value => '/admin/dashboard',
            rol_usuario::TUTOR->value => '/usuario/inicio',
            rol_usuario::ALUMNO->value => '/usuario/inicio',
        ];

        header('Location: ' . ($rutas[$rol] ?? '/'));
        exit;
    }

    private function BackWithError(string $message): void
    {
        $_SESSION['error'] = $message;
        header('Location: /login');
        exit;
    }
}