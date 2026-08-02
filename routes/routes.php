<?php

/**
 * Definición de Rutas de la Aplicación
 */

use App\Router;
use App\Controller\AttendanceController;
use App\Controller\EvaluationController;
use App\Controller\MaterialController;
use App\Controller\SessionController;
use App\Controller\TutoriasController;
use App\Controller\UsuariosController;

function dashboardData() {
    $db = \App\Database::getConnection();

    $stats = [];

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias");
    $stats['total_tutorias'] = (int) $stmt->fetchColumn();

    $stmt = $db->prepare("SELECT COUNT(*) FROM sesiones_tutoria WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())");
    $stmt->execute();
    $stats['sesiones_este_mes'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'PENDIENTE'");
    $stats['tutorias_activas'] = (int) $stmt->fetchColumn();
    $stats['tutorias_pendientes'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM materias WHERE estado = 'ACTIVO'");
    $stats['materias_activas'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM materias");
    $stats['total_materias'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM usuarios");
    $stats['total_usuarios'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'tutor'");
    $stats['total_tutores'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'alumno' AND estado = 'ACTIVO'");
    $stats['total_alumnos'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'COMPLETADA'");
    $stats['tutorias_completadas'] = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'CANCELADA'");
    $stats['tutorias_canceladas'] = (int) $stmt->fetchColumn();

    $usuario = [
        'nombre' => trim(($_SESSION['nombres'] ?? '') . ' ' . ($_SESSION['apellidos'] ?? '')) ?: 'Administrador',
    ];

    $stmt = $db->prepare("
        SELECT s.numero, s.fecha,
               t.hora_incio AS hora_inicio,
               CONCAT(u.nombres, ' ', u.apellidos) AS alumno_nombre,
               m.nombre AS materia_nombre
        FROM sesiones_tutoria s
        JOIN tutorias t ON t.id = s.tutoria_id
        JOIN usuarios u ON u.id = t.alumno_id
        JOIN materias m ON m.id = t.materia_id
        WHERE s.fecha >= CURDATE() AND t.estado = 'PENDIENTE'
        ORDER BY s.fecha ASC, t.hora_incio ASC
        LIMIT 5
    ");
    $stmt->execute();
    $proximas_sesiones = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $stmt = $db->query("
        SELECT CONCAT(u.nombres, ' ', u.apellidos) AS tutor_nombre,
               u.id,
               COUNT(t.id) AS total_completadas
        FROM usuarios u
        JOIN tutorias t ON t.tutor_id = u.id AND t.estado = 'COMPLETADA'
        WHERE u.rol = 'tutor'
        GROUP BY u.id
        ORDER BY total_completadas DESC
        LIMIT 5
    ");
    $top_tutores = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $stmt = $db->query("
        SELECT m.nombre, m.codigo, COUNT(t.id) AS total_tutorias
        FROM materias m
        JOIN tutorias t ON t.materia_id = m.id
        GROUP BY m.id
        ORDER BY total_tutorias DESC
        LIMIT 5
    ");
    $top_materias = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $stmt = $db->prepare("
        SELECT MONTH(fecha) AS mes, COUNT(*) AS total
        FROM tutorias
        WHERE YEAR(fecha) = YEAR(CURDATE())
        GROUP BY MONTH(fecha)
        ORDER BY mes
    ");
    $stmt->execute();
    $chart_data_rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    $chart_values = array_fill(0, 12, 0);
    foreach ($chart_data_rows as $row) {
        $chart_values[(int)$row['mes'] - 1] = (int)$row['total'];
    }

    return view('admin/dashboard', [
        'title'           => 'Dashboard',
        'stats'           => $stats,
        'usuario'         => $usuario,
        'proximas_sesiones' => $proximas_sesiones,
        'top_tutores'     => $top_tutores,
        'top_materias'    => $top_materias,
        'chart_values'    => $chart_values,
    ]);
}

// ==========================================
// RUTAS DE AUTENTICACIÓN (LOGIN)
// ==========================================

Router::get('/', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::get('/login', function () {
    return view('auth/login', ['title' => 'Login Tutorium']);
});

Router::post('/login', 'Auth\\LoginController@handle');

Router::get('/logout', function () {
    \App\Middleware\Auth::logout();
});


// ==========================================
// VISTAS DEL DASHBOARD / SECCIONES
// ==========================================

// USUARIOS (Corregido: Ya no llama a layout/base manualmente)
Router::get('/users/inicio', function () {
    return view('users/inicio', ['title' => 'Mis tutorias']);
});

// ADMINISTRADOR (Corregido: Ya no llama a layout/base manualmente)
Router::get('/admin/dashboard', function () {
    return dashboardData();
});

Router::get('/dashboard', function () {
    return dashboardData();
});

Router::get('/tutorias', function () {
    $controller = new \App\Controller\TutorialController();
    return $controller->index();
});

// NUEVA RUTA DINÁMICA: Capta el ID de la tutoría de forma limpia
Router::get('/tutorias/{tutoria_id}/sesiones', function ($tutoria_id) {
    $controller = new SessionController();
    return $controller->mostrar($tutoria_id);
});

Router::get('/tutorias/admin', function () {
    $controller = new TutoriasController();
    return $controller->index();
});

Router::get('/materias', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->index();
});

Router::get('/usuarios', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->index();
});

Router::get('/evaluaciones', function () {
    $model = new \App\Model\Evaluation();
    $rol = $_SESSION['rol'] ?? 'alumno';
    $evaluaciones = $model->obtenerHistorial($_SESSION['id'], $rol);

    $db = \App\Database::getConnection();
    $stmt = $db->query("SELECT DISTINCT m.nombre FROM materias m JOIN tutorias t ON t.materia_id = m.id JOIN evaluaciones e ON e.tutoria_id = t.id ORDER BY m.nombre");
    $materias = $stmt->fetchAll(PDO::FETCH_COLUMN);

    return view('users/EvaluationHistory/EvaluationHistory', [
        'title' => 'Evaluaciones',
        'evaluaciones' => $evaluaciones,
        'rol' => $rol,
        'materias' => $materias,
    ]);
});

Router::get('/evaluacion/responder/{id}', function ($evaluacion_id) {
    $model = new \App\Model\Evaluation();
    $evaluacion = $model->obtenerConPreguntas($evaluacion_id);
    if (!$evaluacion) {
        http_response_code(404);
        echo 'Evaluación no encontrada.';
        exit;
    }

    $usuario_id = (int) ($_SESSION['id'] ?? 0);
    $rol = $_SESSION['rol'] ?? 'alumno';

    $preguntasEvaluacion = $evaluacion['preguntas'];

    $db = \App\Database::getConnection();
    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM respuestas_alumno WHERE evaluacion_id = ? AND alumno_id = ?");
    $stmtCheck->execute([$evaluacion_id, $usuario_id]);
    $yaRespondida = (int) $stmtCheck->fetchColumn() > 0;

    return view('users/Evaluation/ResponderEvaluacion', [
        'title' => $evaluacion['titulo'],
        'evaluacion' => $evaluacion,
        'preguntasEvaluacion' => $preguntasEvaluacion,
        'yaRespondida' => $yaRespondida,
        'rol' => $rol,
    ]);
});

Router::get('/evaluacion/pdf/{id}', function ($evaluacion_id) {
    $controller = new EvaluationController();
    return $controller->generarPDF($evaluacion_id);
});

Router::get('/certificado/pdf/{tutoria_id}', function ($tutoria_id) {
    $controller = new EvaluationController();
    return $controller->generarCertificado($tutoria_id);
});

Router::get('/perfil', function () {
    $db = \App\Database::getConnection();
    $stmt = $db->prepare("SELECT id, nombres, apellidos, email, telefono, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    return view('layout/Profile', ['title' => 'Perfil', 'usuario' => $usuario]);
});


// ==========================================
// CONTROLADORES Y PROCESOS (POST/GET)
// ==========================================

Router::get('/evaluation/crear', function () {
    $controller = new EvaluationController();
    return $controller->mostrarFormulario();
});

Router::post('/evaluation/guardar', function () {
    $controller = new EvaluationController();
    return $controller->guardar();
});

Router::get('/evaluation/editar/{id}', function ($evaluacion_id) {
    $controller = new EvaluationController();
    return $controller->mostrarFormularioEditar($evaluacion_id);
});

Router::post('/evaluation/actualizar', function () {
    $controller = new EvaluationController();
    return $controller->actualizar();
});

Router::post('/evaluation/importar-xml', function () {
    $controller = new EvaluationController();
    return $controller->importarXML();
});

Router::post('/evaluation/eliminar/{id}', function ($evaluacion_id) {
    $rol = $_SESSION['rol'] ?? 'alumno';
    if ($rol !== 'tutor' && $rol !== 'admin') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'No autorizado.']);
        exit;
    }

    $model = new \App\Model\Evaluation();
    $model->eliminar($evaluacion_id);

    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
});

Router::post('/attendance/marcar', function () {
    $controller = new AttendanceController();
    return $controller->marcar();
});

Router::post('/material/guardar', function () {
    $controller = new MaterialController();
    return $controller->guardar();
});

Router::get('/material/pdf/{sesion_id}', function ($sesion_id) {
    $controller = new MaterialController();
    return $controller->descargarPDF($sesion_id);
});

Router::post('/material/subir-pdf', function () {
    $controller = new MaterialController();
    return $controller->subirPDF();
});

Router::post('/material/eliminar-pdf', function () {
    $controller = new MaterialController();
    return $controller->eliminarPDF();
});

Router::post('/materias/guardar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->guardar();
});

Router::post('/materias/actualizar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->actualizar();
});

Router::post('/materias/eliminar', function () {
    $controller = new \App\Controller\MateriasController();
    return $controller->eliminar();
});

Router::post('/tutorias/guardar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->guardar();
});

Router::post('/tutorias/actualizar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->actualizar();
});

Router::post('/tutorias/eliminar', function () {
    $controller = new \App\Controller\TutoriasController();
    return $controller->eliminar();
});

Router::post('/usuarios/guardar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->guardar();
});

Router::post('/usuarios/actualizar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->actualizar();
});

Router::post('/usuarios/eliminar', function () {
    $controller = new \App\Controller\UsuariosController();
    return $controller->eliminar();
});

Router::post('/session/guardarLink', function () {
    $controller = new SessionController();
    return $controller->guardarLink();
});

Router::post('/api/calificar-evaluacion', function () {
    $rol = $_SESSION['rol'] ?? 'alumno';
    if ($rol !== 'tutor' && $rol !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No autorizado.']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $evaluacion_id = (int) ($input['id'] ?? 0);
    $nota          = (float) ($input['nota'] ?? 0);
    $comentarios   = trim($input['comentarios'] ?? '');

    if (!$evaluacion_id || $nota < 0 || $nota > 10) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
        exit;
    }

    $model = new \App\Model\Evaluation();
    $result = $model->guardarCalificacion($evaluacion_id, $nota, $comentarios);

    if (!$result) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'La base de datos no tiene las columnas de calificación. Ejecute la migración.']);
        exit;
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'nota' => $nota]);
    exit;
});

Router::post('/api/evaluacion/responder', function () {
    $rol = $_SESSION['rol'] ?? 'alumno';
    if ($rol !== 'alumno' && $rol !== 'tutor' && $rol !== 'admin') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'No autorizado.']);
        exit;
    }

    $evaluacion_id = (int) ($_POST['evaluacion_id'] ?? 0);
    $alumno_id     = (int) ($_SESSION['id'] ?? 0);
    $respuestas    = json_decode($_POST['respuestas'] ?? '[]', true) ?? [];

    if (!$evaluacion_id || !$alumno_id || empty($respuestas)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Datos incompletos.']);
        exit;
    }

    $model = new \App\Model\Evaluation();
    $model->guardarRespuestas($evaluacion_id, $alumno_id, $respuestas);

    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
});

Router::get('/api/evaluacion/respuestas/{evaluacion_id}/{alumno_id}', function ($evaluacion_id, $alumno_id) {
    $rol = $_SESSION['rol'] ?? 'alumno';
    if ($rol !== 'tutor' && $rol !== 'admin') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'No autorizado.']);
        exit;
    }

    $model = new \App\Model\Evaluation();
    $respuestas = $model->obtenerRespuestasAlumno($evaluacion_id, $alumno_id);

    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'respuestas' => $respuestas]);
    exit;
});

Router::get('/api/admin/stats', function () {
    $db = \App\Database::getConnection();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias");
    $total_tutorias = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'PENDIENTE'");
    $pendientes = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'COMPLETADA'");
    $completadas = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM tutorias WHERE estado = 'CANCELADA'");
    $canceladas = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM usuarios");
    $total_usuarios = (int) $stmt->fetchColumn();

    $stmt = $db->query("SELECT COUNT(*) FROM materias WHERE estado = 'ACTIVO'");
    $materias_activas = (int) $stmt->fetchColumn();

    header('Content-Type: application/json');
    echo json_encode([
        'total_tutorias'     => $total_tutorias,
        'pendientes'         => $pendientes,
        'completadas'        => $completadas,
        'canceladas'         => $canceladas,
        'total_usuarios'     => $total_usuarios,
        'materias_activas'   => $materias_activas,
    ]);
    exit;
});

Router::post('/perfil/actualizar', function () {
    $db = \App\Database::getConnection();
    $usuario_id = $_SESSION['id'];

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['password'] ?? '';
    $confirm_password = $_POST['password_confirm'] ?? '';

    // Verificar contraseña actual
    $stmt = $db->prepare("SELECT password FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($current_password, $user['password'])) {
        $_SESSION['perfil_error'] = 'La contraseña actual no es correcta.';
        header('Location: /perfil');
        exit;
    }

    // Validar y actualizar contraseña
    if ($new_password !== '') {
        if (strlen($new_password) < 6) {
            $_SESSION['perfil_error'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
            header('Location: /perfil');
            exit;
        }
        if ($new_password !== $confirm_password) {
            $_SESSION['perfil_error'] = 'Las contraseñas nuevas no coinciden.';
            header('Location: /perfil');
            exit;
        }
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $usuario_id]);
    }

    $_SESSION['perfil_ok'] = 'Credenciales actualizadas correctamente.';
    header('Location: /perfil');
    exit;
});


// ==========================================
// PROTECCIÓN DE RUTAS (MIDDLEWARE)
// ==========================================

// Públicas (no requieren autenticación)
Router::setPublic('/');
Router::setPublic('/login');
Router::setPublic('/logout');

// Admin (dashboard, materias, usuarios)
Router::protect('/admin/*', ['admin']);
Router::protect('/dashboard', ['admin']);
Router::protect('/tutorias/admin', ['admin']);
Router::protect('/materias*', ['admin']);
Router::protect('/usuarios*', ['admin']);
Router::protect('/api/admin/*', ['admin']);

// Tutor/Admin (evaluaciones, materiales, asistencias)
Router::protect('/attendance/*', ['tutor', 'admin']);
Router::protect('/evaluation/crear', ['tutor', 'admin']);
Router::protect('/evaluation/guardar', ['tutor', 'admin']);
Router::protect('/evaluation/editar/*', ['tutor', 'admin']);
Router::protect('/evaluation/actualizar', ['tutor', 'admin']);
Router::protect('/evaluation/importar-xml', ['tutor', 'admin']);
Router::protect('/evaluation/eliminar/*', ['tutor', 'admin']);
Router::protect('/material/guardar', ['tutor', 'admin']);
Router::protect('/material/subir-pdf', ['tutor', 'admin']);
Router::protect('/material/eliminar-pdf', ['tutor', 'admin']);
Router::protect('/session/guardarLink', ['tutor', 'admin']);
Router::protect('/api/calificar-evaluacion', ['tutor', 'admin']);
Router::protect('/api/evaluacion/respuestas/*', ['tutor', 'admin']);

// El resto de rutas requieren autenticación (cualquier rol)


// ==========================================
// HELPER PARA RENDERIZAR VISTAS
// ==========================================

function view($name, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../resources/view/' . $name . '.php';

    if (!file_exists($viewPath)) {
        http_response_code(404);
        echo "Vista $name no encontrada";
        return;
    }

    // Evita bucles: Si se pide directamente el layout base, solo lo incluye
    $sinLayout = ['layout/base'];

    if (in_array($name, $sinLayout)) {
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }

    ob_start();
    include $viewPath;
    $content = ob_get_clean();

    $layoutPath = __DIR__ . '/../resources/view/layout/base.php';
    ob_start();
    include $layoutPath;
    return ob_get_clean();
}