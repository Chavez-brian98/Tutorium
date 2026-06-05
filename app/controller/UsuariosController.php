<?php

namespace App\Controller;

use App\Model\Usuario;

class UsuariosController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function index() {
        $usuarios = $this->usuarioModel->obtenerTodos();

        return view('admin/crud_usuarios', [
            'usuarios' => $usuarios,
            'title' => 'Usuarios',
        ]);
    }

    public function guardar() {
        $nombres  = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol      = in_array($_POST['rol'] ?? 'alumno', ['admin', 'tutor', 'alumno']) ? $_POST['rol'] : 'alumno';
        $estado   = in_array($_POST['estado'] ?? 'ACTIVO', ['ACTIVO', 'INACTIVO']) ? $_POST['estado'] : 'ACTIVO';

        if ($nombres === '' || $apellidos === '' || $email === '' || $password === '') {
            header('Location: /usuarios');
            exit;
        }

        $this->usuarioModel->crear($nombres, $apellidos, $email, $password, $rol, $estado, $telefono !== '' ? $telefono : null);

        header('Location: /usuarios');
        exit;
    }

    public function actualizar() {
        $id       = (int) ($_POST['id'] ?? 0);
        $nombres  = trim($_POST['nombres'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $rol      = in_array($_POST['rol'] ?? 'alumno', ['admin', 'tutor', 'alumno']) ? $_POST['rol'] : 'alumno';
        $estado   = in_array($_POST['estado'] ?? 'ACTIVO', ['ACTIVO', 'INACTIVO']) ? $_POST['estado'] : 'ACTIVO';

        if ($id <= 0 || $nombres === '' || $apellidos === '' || $email === '') {
            header('Location: /usuarios');
            exit;
        }

        $this->usuarioModel->actualizar(
            $id,
            $nombres,
            $apellidos,
            $email,
            $rol,
            $estado,
            $password !== '' ? $password : null,
            $telefono !== '' ? $telefono : null
        );

        header('Location: /usuarios');
        exit;
    }

    public function eliminar() {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->usuarioModel->eliminar($id);
        }

        header('Location: /usuarios');
        exit;
    }
}
