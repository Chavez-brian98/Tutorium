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
            $_SESSION['flash_error'] = 'Todos los campos obligatorios deben ser llenados.';
            header('Location: /usuarios');
            exit;
        }

        $telefono = strlen($telefono) > 8 ? null : ($telefono !== '' ? $telefono : null);

        try {
            $resultado = $this->usuarioModel->crear($nombres, $apellidos, $email, $password, $rol, $estado, $telefono);
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Error al crear usuario: ' . $e->getMessage();
            header('Location: /usuarios');
            exit;
        }

        if ($resultado) {
            $_SESSION['flash_success'] = 'Usuario creado exitosamente.';
        } else {
            $_SESSION['flash_error'] = 'Error al crear el usuario. Revisa los logs del sistema.';
        }

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
            $_SESSION['flash_error'] = 'Datos inválidos para actualizar el usuario.';
            header('Location: /usuarios');
            exit;
        }

        $telefono = strlen($telefono) > 8 ? null : ($telefono !== '' ? $telefono : null);

        $resultado = $this->usuarioModel->actualizar(
            $id,
            $nombres,
            $apellidos,
            $email,
            $rol,
            $estado,
            $password !== '' ? $password : null,
            $telefono
        );

        if ($resultado) {
            $_SESSION['flash_success'] = 'Usuario actualizado correctamente.';
        } else {
            $_SESSION['flash_error'] = 'Error al actualizar el usuario.';
        }

        header('Location: /usuarios');
        exit;
    }

    public function eliminar() {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $resultado = $this->usuarioModel->eliminar($id);
            if ($resultado) {
                $_SESSION['flash_success'] = 'Usuario eliminado correctamente.';
            } else {
                $_SESSION['flash_error'] = 'Error al eliminar el usuario.';
            }
        }

        header('Location: /usuarios');
        exit;
    }
}
