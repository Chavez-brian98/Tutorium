<?php

namespace App\Controller;

use App\Model\Materia;

class MateriasController {
    private $materiaModel;

    public function __construct() {
        $this->materiaModel = new \App\Model\Materia();
    }

    public function index() {
        $materias = $this->materiaModel->obtenerTodos();

        return view('admin/crud_materias', [
            'materias' => $materias,
            'title' => 'Materias',
        ]);
    }

    public function guardar() {
        $nombre      = trim($_POST['nombre'] ?? '');
        $codigo      = trim($_POST['codigo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado      = in_array($_POST['estado'] ?? 'ACTIVO', ['ACTIVO', 'INACTIVO']) ? $_POST['estado'] : 'ACTIVO';

        if ($nombre === '' || $codigo === '') {
            header('Location: /materias');
            exit;
        }

        $this->materiaModel->crear($nombre, $codigo, $descripcion, $estado);

        header('Location: /materias');
        exit;
    }

    public function actualizar() {
        $id          = (int) ($_POST['id'] ?? 0);
        $nombre      = trim($_POST['nombre'] ?? '');
        $codigo      = trim($_POST['codigo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado      = in_array($_POST['estado'] ?? 'ACTIVO', ['ACTIVO', 'INACTIVO']) ? $_POST['estado'] : 'ACTIVO';

        if ($id <= 0 || $nombre === '' || $codigo === '') {
            header('Location: /materias');
            exit;
        }

        $this->materiaModel->actualizar($id, $nombre, $codigo, $descripcion, $estado);

        header('Location: /materias');
        exit;
    }

    public function eliminar() {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->materiaModel->eliminar($id);
        }

        header('Location: /materias');
        exit;
    }
}
