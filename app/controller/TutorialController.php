<?php
namespace App\Controller;

use App\Model\Tutorial;

class TutorialController {
    private $tutorialModel;

    public function __construct() {
        // Aseguramos que el usuario esté logueado
        if (!isset($_SESSION['id'])) {
            header('Location: /login');
            exit;
        }
        $this->tutorialModel = new Tutorial();
    }

    /**
     * Renderiza la pantalla principal de tutorías asignadas
     */
    public function index() {
        // 1. Obtener las tutorías del usuario actual
        $usuario_id = $_SESSION['id'];
        $tutorias = $this->tutorialModel->obtenerPorUsuario($usuario_id);

        // 2. Renderizar la vista dentro de la carpeta 'users/TutorialsUser'
        return view('users/TutorialsUser/Tutorial', [
            'tutorias' => $tutorias
        ]);
    }
}