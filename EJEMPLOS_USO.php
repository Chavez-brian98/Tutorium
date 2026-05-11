<?php
/**
 * EJEMPLOS DE USO - Estructura y Patrones del Proyecto Tutorium
 *
 * Este archivo muestra cómo usar cada parte de la estructura del proyecto
 */

// ============================================================================
// 1. CONEXIÓN A BASE DE DATOS (config/database.php)
// ============================================================================

// En cualquier archivo PHP, para acceder a la base de datos:
// require_once 'config/database.php';
// Ahora $pdo está disponible globalmente

// Ejemplo: Obtener todos los registros
// $stmt = $pdo->query("SELECT * FROM usuarios");
// $usuarios = $stmt->fetchAll();
// foreach ($usuarios as $usuario) {
//     echo $usuario['nombre'];
// }

// ============================================================================
// 2. MODELO (app/model/User.php)
// ============================================================================

/*
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create($name, $email) {
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)");
        return $stmt->execute([':nombre' => $name, ':email' => $email]);
    }
}
*/

// ============================================================================
// 3. CONTROLADOR (app/controller/UserController.php)
// ============================================================================

/*
require_once 'config/database.php';
require_once 'app/model/User.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function listUsers() {
        $usuarios = $this->userModel->getAll();
        return $usuarios;
    }

    public function getUserById($id) {
        return $this->userModel->getById($id);
    }
}
*/

// ============================================================================
// 4. RUTAS (routes/routes.php)
// ============================================================================

/*
require_once 'config/database.php';
require_once 'app/controller/UserController.php';

$userController = new UserController($pdo);

// Definir rutas
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'users') {
        $users = $userController->listUsers();
        header('Content-Type: application/json');
        echo json_encode($users);
    }

    if ($_GET['action'] === 'user' && isset($_GET['id'])) {
        $user = $userController->getUserById($_GET['id']);
        header('Content-Type: application/json');
        echo json_encode($user);
    }
}
*/

// ============================================================================
// 5. ACCESO A VARIABLES DE ENTORNO
// ============================================================================

// Variables de entorno se cargan automáticamente del archivo .env
// Acceso directo:
$db_host = getenv('DB_HOST');           // mysql
$db_name = getenv('DB_NAME');           // tutorium_db
$app_env = getenv('APP_ENV');           // development
$app_debug = getenv('APP_DEBUG');       // true

// Con valores por defecto:
$timeout = getenv('TIMEOUT') ?: 30;     // Si no existe, usa 30

// ============================================================================
// 6. SERVICIOS (service/NotificationService.php)
// ============================================================================

/*
class NotificationService {
    public static function sendEmail($email, $subject, $body) {
        // Lógica para enviar email
        mail($email, $subject, $body);
    }

    public static function sendSMS($phone, $message) {
        // Lógica para enviar SMS
    }

    public static function logActivity($userId, $activity) {
        // Lógica para registrar actividad
    }
}

// Uso en controlador:
// NotificationService::sendEmail('user@example.com', 'Bienvenido', 'Contenido');
*/

// ============================================================================
// 7. MIDDLEWARE (middleware/AuthMiddleware.php)
// ============================================================================

/*
class AuthMiddleware {
    public static function authenticate() {
        if (!isset($_COOKIE['user_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public static function authorize($role) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
            http_response_code(403);
            die('Acceso denegado');
        }
    }
}

// Uso antes de ejecutar controladores:
// AuthMiddleware::authenticate();
*/

// ============================================================================
// 8. VISTAS (resources/view/user.php)
// ============================================================================

/*
// user.php
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/style.css">
    <title>Usuarios</title>
</head>
<body>
    <nav>
        <?php include 'resources/view/layout/navbar.php'; ?>
    </nav>

    <div class="container">
        <h1>Lista de Usuarios</h1>
        <table>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script src="/javascript/Alerts.js"></script>
</body>
</html>
*/

// ============================================================================
// FLUJO COMPLETO DE UNA PETICIÓN
// ============================================================================

/*
FLUJO:

1. Usuario hace request a: /users?action=list

2. index.php (public/index.php) recibe la petición

3. routes/routes.php procesa la ruta

4. Middleware (middleware/AuthMiddleware.php) valida permisos

5. UserController llamada:
   - Crea instancia de User model
   - Llama a userModel->getAll()

6. User model consulta a la BD:
   - require 'config/database.php'
   - $pdo->query("SELECT * FROM usuarios")

7. UserController retorna datos

8. Vistas renderiza HTML (resources/view/user.php)

9. JavaScript interactúa (resources/javascript/Alerts.js)

10. Respuesta se envía al navegador
*/

// ============================================================================
// ESTRUCTURA RECOMENDADA PARA UN PROYECTO MÁS GRANDE
// ============================================================================

/*
Tutorium/
├── app
│   ├── controller
│   │   ├── UserController.php
│   │   ├── ProductController.php
│   │   └── ...
│   └── model
│       ├── User.php
│       ├── Product.php
│       └── ...
├── config
│   ├── database.php
│   ├── php.ini
│   └── mysql.cnf
├── middleware
│   ├── AuthMiddleware.php
│   ├── RateLimitMiddleware.php
│   └── ...
├── public
│   ├── index.php (router principal)
│   ├── css
│   ├── js
│   └── uploads
├── resources
│   ├── css
│   ├── javascript
│   │   └── Alerts.js
│   └── view
│       ├── layout
│       │   ├── navbar.php
│       │   ├── footer.php
│       │   └── layout.php
│       ├── user
│       │   ├── list.php
│       │   └── detail.php
│       └── ...
├── routes
│   └── routes.php
├── service
│   ├── NotificationService.php
│   ├── ValidationService.php
│   └── ...
└── storage
    ├── logs
    └── uploads
*/

// ============================================================================
// EJEMPLO: Crear una tabla en MySQL
// ============================================================================

/*
-- Acceder a MySQL:
docker-compose exec mysql mysql -u tutorium_user -ptutorium_password tutorium_db

-- SQL para crear tabla de usuarios:
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar datos de prueba:
INSERT INTO usuarios (nombre, email) VALUES
('Juan Pérez', 'juan@example.com'),
('María García', 'maria@example.com');
*/

// ============================================================================
// VARIABLES DE ENTORNO DISPONIBLES
// ============================================================================

echo "=== VARIABLES DE ENTORNO ===\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_PORT: " . getenv('DB_PORT') . "\n";
echo "DB_NAME: " . getenv('DB_NAME') . "\n";
echo "DB_USER: " . getenv('DB_USER') . "\n";
echo "APP_ENV: " . getenv('APP_ENV') . "\n";
echo "APP_URL: " . getenv('APP_URL') . "\n";

?>

