<?php

namespace App\Controller;

require_once __DIR__ . '/../model/Session.php';
require_once __DIR__ . '/../model/Material.php';

// CORREGIDO: Cambiados a singular para que coincidan con tu estructura de carpetas
use App\Model\Material;
use App\Model\Session;
use App\Model\Attendance;

class SessionController {

    private $sessionModel;

    public function __construct() {
        $this->sessionModel = new Session();
    }

    // GET /tutorias/{tutoria_id}/sesiones?numero=1
    public function mostrar($tutoria_id) {
        $numero = isset($_GET['numero']) ? (int) $_GET['numero'] : 1;

        $sesion   = $this->sessionModel->obtenerPorTutoria($tutoria_id, $numero);
        $sesiones = $this->sessionModel->obtenerTodasPorTutoria($tutoria_id);

        if (!$sesion) {
            http_response_code(404);
            echo "Sesión no encontrada.";
            return;
        }

        // Asistencia
        $attendanceModel = new Attendance();
        $asistencia      = $attendanceModel->obtenerPorSesion($sesion['id'], $sesion['alumno_id']);
        $yaAsistencia    = $asistencia !== false && $asistencia !== null;
        $asisPresente    = $yaAsistencia ? (bool) $asistencia['presente'] : null;

        // Datos de la tutoría (materia, alumno, horario)
        $datosTutoria  = $this->sessionModel->obtenerDatosTutoria($tutoria_id);

        $dias = [
            'Monday'    => 'Lunes',
            'Tuesday'   => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday'  => 'Jueves',
            'Friday'    => 'Viernes',
            'Saturday'  => 'Sábado',
            'Sunday'    => 'Domingo',
        ];
        $dia        = $dias[date('l', strtotime($datosTutoria['fecha']))];
        $horaInicio = date('g:ia', strtotime($datosTutoria['hora_inicio']));
        $horaFin    = date('g:ia', strtotime($datosTutoria['hora_fin']));

        // Material
        $materialModel = new Material();
        $material      = $materialModel->obtenerPorSesion($sesion['id']);

        return view('Session/Session', [
            'sesion'        => $sesion,
            'sesiones'      => $sesiones,
            'tutoria_id'    => $tutoria_id,
            'numero'        => $numero,
            'sesion_id'     => $sesion['id'],
            'alumno_id'     => $sesion['alumno_id'],
            'yaAsistencia'  => $yaAsistencia,
            'asisPresente'  => $asisPresente,
            'material'      => $material,
            'materia'       => $datosTutoria['materia'],
            'horario'       => "$dia / $horaInicio - $horaFin",
            'nombre_alumno' => $datosTutoria['nombre_alumno'],
        ]);
    }

    public function guardarLink() {
        $sesion_id = (int)  $_POST['sesion_id'];
        $link      = trim($_POST['link'] ?? '');

        $this->sessionModel->guardarLink($sesion_id, $link);

        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
    }
}