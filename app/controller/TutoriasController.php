<?php

namespace App\Controller;

use App\Model\Tutoria;
use App\Model\Usuario;
use App\Model\Materia;

class TutoriasController {
    private $tutoriaModel;
    private $usuarioModel;
    private $materiaModel;

    public function __construct() {
        $this->tutoriaModel = new Tutoria();
        $this->usuarioModel = new Usuario();
        $this->materiaModel = new Materia();
    }

    public function index() {
        $tutorias = $this->tutoriaModel->obtenerTodos();
        $usuarios = $this->usuarioModel->obtenerTodos();
        $alumnos  = array_values(array_filter($usuarios, fn($usuario) => ($usuario['rol'] ?? '') === 'alumno' && strtoupper($usuario['estado'] ?? '') === 'ACTIVO'));
        $tutores  = array_values(array_filter($usuarios, fn($usuario) => ($usuario['rol'] ?? '') === 'tutor' && strtoupper($usuario['estado'] ?? '') === 'ACTIVO'));
        $materias = $this->materiaModel->obtenerTodos();

        return view('admin/crud_tutorias', [
            'tutorias' => $tutorias,
            'alumnos' => $alumnos,
            'tutores' => $tutores,
            'materias' => $materias,
            'title' => 'Tutorías',
        ]);
    }

    public function guardar() {
        $alumno_id   = (int) ($_POST['alumno_id'] ?? 0);
        $tutor_id    = (int) ($_POST['tutor_id'] ?? 0);
        $materia_id  = (int) ($_POST['materia_id'] ?? 0);
        $fecha       = trim($_POST['fecha'] ?? '');
        $hora_inicio = trim($_POST['hora_inicio'] ?? '');
        $hora_fin    = trim($_POST['hora_fin'] ?? '');
        $sesiones    = max(1, (int) ($_POST['num_sesiones'] ?? 1));
        $estado      = in_array($_POST['estado'] ?? 'PENDIENTE', ['PENDIENTE', 'COMPLETADA', 'CANCELADA']) ? $_POST['estado'] : 'PENDIENTE';

        if ($alumno_id <= 0 || $tutor_id <= 0 || $materia_id <= 0 || $fecha === '' || $hora_inicio === '' || $hora_fin === '') {
            header('Location: /tutorias/admin');
            exit;
        }

        $this->tutoriaModel->crear($alumno_id, $tutor_id, $materia_id, $fecha, $hora_inicio, $hora_fin, $sesiones, $estado);

        header('Location: /tutorias/admin');
        exit;
    }

    public function actualizar() {
        $id          = (int) ($_POST['id'] ?? 0);
        $alumno_id   = (int) ($_POST['alumno_id'] ?? 0);
        $tutor_id    = (int) ($_POST['tutor_id'] ?? 0);
        $materia_id  = (int) ($_POST['materia_id'] ?? 0);
        $fecha       = trim($_POST['fecha'] ?? '');
        $hora_inicio = trim($_POST['hora_inicio'] ?? '');
        $hora_fin    = trim($_POST['hora_fin'] ?? '');
        $sesiones    = max(1, (int) ($_POST['num_sesiones'] ?? 1));
        $estado      = in_array($_POST['estado'] ?? 'PENDIENTE', ['PENDIENTE', 'COMPLETADA', 'CANCELADA']) ? $_POST['estado'] : 'PENDIENTE';

        if ($id <= 0 || $alumno_id <= 0 || $tutor_id <= 0 || $materia_id <= 0 || $fecha === '' || $hora_inicio === '' || $hora_fin === '') {
            header('Location: /tutorias/admin');
            exit;
        }

        $this->tutoriaModel->actualizar($id, $alumno_id, $tutor_id, $materia_id, $fecha, $hora_inicio, $hora_fin, $sesiones, $estado);

        header('Location: /tutorias/admin');
        exit;
    }

    public function eliminar() {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->tutoriaModel->eliminar($id);
        }

        header('Location: /tutorias/admin');
        exit;
    }
}
