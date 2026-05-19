# Sistema de Rutas, CDN y Base de Datos - Tutorium

Un sistema simple y útil para gestionar rutas, assets/CDN y conexiones a base de datos en PHP.

## 🚀 Características

✅ **Router Simple**: Registra y despacha rutas GET/POST/PUT/DELETE  
✅ **Helpers de Assets**: Funciones `asset()` y `cdn()` para generar URLs  
✅ **Conexión PDO**: Gestión simple de conexiones a MySQL con singleton pattern  
✅ **Pretty URLs**: Soporte para URLs limpias (sin index.php)  
✅ **Configuración .env**: Variables de entorno centralizadas  
✅ **Composer Autoload**: PSR-4 autoloading automático  

---

## 📋 Estructura del Proyecto

```
Tutorium/
├── public/
│   ├── index.php          # Front controller - punto de entrada
│   └── .htaccess          # Reescritura de URLs
├── src/
│   ├── Router.php         # Clase Router para gestionar rutas
│   └── Database.php       # Clase Database para conexiones PDO
├── routes/
│   └── routes.php         # Definición de rutas de la aplicación
├── helpers/
│   └── url.php            # Helpers para URLs y assets
├── config/
│   └── database.php       # Configuración de BD (legacy, deprecado)
├── resources/
│   ├── css/
│   ├── javascript/
│   ├── img/
│   └── view/              # Vistas/templates
├── bootstrap.php          # Carga .env y configuración inicial
├── .env                   # Variables de entorno
└── composer.json          # Definición de dependencias y autoload
```

---

## 🎯 Inicio Rápido

### 1. Configurar el servidor

```bash
# Desde la raíz del proyecto
php -S localhost:8000 -t public
```

Luego accede a: **http://localhost:8000**

### 2. Definir una ruta

Abre `routes/routes.php` y agrega:

```php
use App\Router;
use App\Database;

// Ruta simple
Router::get('/saludo', function() {
    return json_encode(['mensaje' => '¡Hola!']);
});

// Ruta con parámetro
Router::get('/usuarios/{id}', function($id) {
    return json_encode(['usuario_id' => $id]);
});

// Ruta con base de datos
Router::get('/productos', function() {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM productos LIMIT 10");
    $stmt->execute();
    return json_encode($stmt->fetchAll());
});

// Ruta POST
Router::post('/usuarios', 'UserController@store');
```

### 3. Usar helpers en vistas

En tus archivos de vista (`resources/view/*.php`):

```php
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?= asset('resources/css/style.css') ?>">
    <link rel="stylesheet" href="<?= cdn('bootstrap/5/css/bootstrap.min.css') ?>">
</head>
<body>
    <a href="<?= url('/productos') ?>">Ver productos</a>
    
    <script src="<?= cdn('jquery/3/jquery.min.js') ?>"></script>
    <script src="<?= asset('resources/javascript/app.js') ?>"></script>
</body>
</html>
```

---

## 📚 Documentación Detallada

### Router - Registrar Rutas

```php
use App\Router;

// GET - Obtener datos
Router::get('/productos', function() {
    return json_encode(['productos' => []]);
});

// POST - Crear datos
Router::post('/productos', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    // Procesar datos...
    return json_encode(['success' => true]);
});

// PUT - Actualizar
Router::put('/productos/{id}', function($id) {
    // Actualizar producto con ID $id
});

// DELETE - Eliminar
Router::delete('/productos/{id}', function($id) {
    // Eliminar producto con ID $id
});
```

### Parámetros en Rutas

Los parámetros entre llaves `{nombre}` se extraen automáticamente:

```php
Router::get('/usuarios/{id}/posts/{post_id}', function($id, $post_id) {
    return json_encode([
        'usuario_id' => $id,
        'post_id' => $post_id
    ]);
});
```

**Solo captura números por defecto** (patrón: `\d+`). Para más flexibilidad, usa funciones anónimas complejas.

### Database - Conexiones PDO

```php
use App\Database;

// Obtener conexión (singleton)
$db = Database::getConnection();

// Consulta preparada (recomendado)
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch(); // Retorna asociativo

// Múltiples resultados
$usuarios = $stmt->fetchAll();

// Insertar
$stmt = $db->prepare("INSERT INTO usuarios (nombre, email) VALUES (?, ?)");
$stmt->execute([$nombre, $email]);
$lastId = $db->lastInsertId();

// Actualizar
$stmt = $db->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
$stmt->execute([$nombre, $id]);

// Eliminar
$stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
```

**La conexión usa UTF-8 y modo de excepción por defecto.**

### Helpers - URLs y Assets

```php
// Generar URL de asset local
asset('resources/css/style.css')
// → http://localhost:8000/resources/css/style.css

// Generar URL de CDN
cdn('bootstrap/5/css/bootstrap.min.css')
// → http://localhost:8000/bootstrap/5/css/bootstrap.min.css (si CDN_URL no existe)
// → https://your-cdn.com/bootstrap/5/css/bootstrap.min.css (si CDN_URL está configurado)

// URL relativa (útil para href en HTML)
url('/productos')
// → /productos

// URL completa de ruta
route_url('/productos/123')
// → http://localhost:8000/productos/123
```

---

## ⚙️ Configuración

### Variables de Entorno (.env)

Edita el archivo `.env` en la raíz del proyecto:

```dotenv
# Base de datos
DB_HOST=localhost
DB_PORT=3306
DB_NAME=tutorium_db
DB_USER=root
DB_PASSWORD=tu_password

# Aplicación
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

# CDN (opcional)
CDN_URL=https://cdn.example.com
```

**Notas:**
- `APP_URL` se usa en los helpers `asset()` y como fallback para `cdn()`
- `CDN_URL` es opcional; si no existe, se usa `APP_URL`
- Las variables se cargan automáticamente en `bootstrap.php`

### Configuración de BD para Docker

Si usas Docker Compose con MySQL:

```dotenv
DB_HOST=mysql
DB_PORT=3306
DB_NAME=tutorium_db
DB_USER=root
DB_PASSWORD=root_password
```

---

## 📝 Ejemplos Reales

### Ejemplo 1: API REST de Productos

```php
// routes/routes.php
use App\Router;
use App\Database;

// Listar productos
Router::get('/api/productos', function() {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT id, nombre, precio FROM productos");
    return json_encode($stmt->fetchAll());
});

// Obtener un producto
Router::get('/api/productos/{id}', function($id) {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
    
    if (!$producto) {
        http_response_code(404);
        return json_encode(['error' => 'Producto no encontrado']);
    }
    
    return json_encode($producto);
});

// Crear producto
Router::post('/api/productos', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    $db = Database::getConnection();
    
    $stmt = $db->prepare("INSERT INTO productos (nombre, precio) VALUES (?, ?)");
    $stmt->execute([$data['nombre'], $data['precio']]);
    
    return json_encode([
        'success' => true,
        'id' => $db->lastInsertId()
    ]);
});
```

### Ejemplo 2: Renderizar Vistas

```php
// helpers/url.php - ya incluye view()
function view($name, $data = [])
{
    extract($data);
    $viewPath = __DIR__ . '/../resources/view/' . $name . '.php';
    
    if (!file_exists($viewPath)) {
        http_response_code(404);
        return json_encode(['error' => "Vista $name no encontrada"]);
    }
    
    ob_start();
    include $viewPath;
    return ob_get_clean();
}

// routes/routes.php
Router::get('/', function() {
    return view('home', ['title' => 'Bienvenido']);
});
```

### Ejemplo 3: Con Controlador

```php
// app/controller/ProductController.php
namespace App\Controller;

use App\Database;

class ProductController
{
    public function index()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM products");
        return json_encode($stmt->fetchAll());
    }
    
    public function store()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $db = Database::getConnection();
        
        $stmt = $db->prepare("INSERT INTO products (title, description) VALUES (?, ?)");
        $stmt->execute([$data['title'], $data['description']]);
        
        return json_encode(['id' => $db->lastInsertId()]);
    }
}

// routes/routes.php
Router::get('/products', 'ProductController@index');
Router::post('/products', 'ProductController@store');
```

---

## 🔧 Troubleshooting

### "Ruta no encontrada" (404)

1. Verifica que la ruta esté registrada en `routes/routes.php`
2. Asegúrate de que la URL coincida exactamente (sin trailing slash)
3. Si usas parámetros, verifica que sean números: `/usuarios/123` ✓ vs `/usuarios/fernando` ✗

### "Class not found" en controladores

1. Verifica que el controlador exista en `app/controller/`
2. Asegúrate de que el namespace sea `App\Controller\`
3. Verifica el nombre de la clase: `ProductController` vs `producController` (case-sensitive)

### La BD no conecta

1. Verifica que los datos en `.env` sean correctos
2. Si usas Docker, asegúrate de que el alias `mysql` está disponible
3. Si está en local, usa `localhost` en lugar de `mysql`
4. Verifica que la base de datos exista

### Pretty URLs no funcionan

1. Asegúrate de que Apache tiene `mod_rewrite` habilitado
2. Verifica que el archivo `public/.htaccess` existe y tiene contenido
3. Si usas Nginx, hay que configurar manualmente (fuera de este scope)

---

## 📖 API Referencia Rápida

### Router

```php
Router::get($path, $callback)      // Registrar GET
Router::post($path, $callback)     // Registrar POST
Router::put($path, $callback)      // Registrar PUT
Router::delete($path, $callback)   // Registrar DELETE
Router::dispatch()                 // Ejecutar ruta actual
Router::getRoutes()                // Obtener todas las rutas
```

### Database

```php
Database::getConnection()          // Obtener conexión PDO
Database::closeConnection()        // Cerrar conexión
```

### Helpers

```php
asset($path)                       // URL de asset local
cdn($path)                         // URL de CDN
url($path)                         // URL relativa
route_url($path)                   // URL completa
view($name, $data)                 // Renderizar vista
```

---

## 🗂️ TIPOS DE RUTAS

### 1. Ruta Simple
```php
Router::get('/simple', function() {
    return json_encode(['resultado' => 'OK']);
});
```

### 2. Ruta con Parámetro
```php
Router::get('/usuarios/{id}', function($id) {
    return json_encode(['usuario_id' => $id]);
});
```
Acceder: `http://localhost:8000/usuarios/123`

### 3. Ruta POST
```php
Router::post('/datos', function() {
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    return json_encode(['recibido' => $data]);
});
```

### 4. Con Base de Datos
```php
use App\Database;

Router::get('/productos', function() {
    $db = Database::getConnection();
    $stmt = $db->query("SELECT * FROM productos");
    return json_encode($stmt->fetchAll());
});
```

### 5. Con Controlador
```php
Router::get('/users', 'ExampleUserController@index');
```

---

## 🎨 USA LOS HELPERS EN VISTAS

En tus archivos PHP dentro de `resources/view/`:

```php
<?php
// URLs de assets locales
<link href="<?= asset('resources/css/style.css') ?>" rel="stylesheet">

// URLs de CDN (o asset si no hay CDN_URL)
<script src="<?= cdn('jquery/3/jquery.min.js') ?>"></script>

// Links internos
<a href="<?= url('/productos') ?>">Ver productos</a>

// URL completa
<?= route_url('/usuarios/123') ?>
?>
```


