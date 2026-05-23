<?php

require_once __DIR__ . '/../model/Session.php';

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

        return view('tutor/sesion', [
            'sesion'     => $sesion,
            'sesiones'   => $sesiones,
            'tutoria_id' => $tutoria_id,
            'numero'     => $numero,
        ]);
    }
}