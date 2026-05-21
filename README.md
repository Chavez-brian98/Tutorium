# 🚀 Tutorium - Guía de Inicio con Docker

Bienvenido a **Tutorium**, un proyecto PHP moderno configurado para ejecutarse con Docker Compose. Este README te guiará paso a paso para poner en marcha el proyecto.

---

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Inicio Rápido](#inicio-rápido)
3. [Estructura del Proyecto](#estructura-del-proyecto)
4. [Configuración](#configuración)
5. [Acceso a Servicios](#acceso-a-servicios)
6. [Comandos Útiles](#comandos-útiles)
7. [Variables de Entorno](#variables-de-entorno)
8. [Troubleshooting](#troubleshooting)

---

## 📦 Requisitos Previos

Antes de empezar, asegúrate de tener instalado:

- **Docker Desktop** (incluye Docker y Docker Compose)
  - Windows: [Descargar Docker Desktop para Windows](https://www.docker.com/products/docker-desktop)
  - macOS: [Descargar Docker Desktop para Mac](https://www.docker.com/products/docker-desktop)
  - Linux: [Instalar Docker Engine](https://docs.docker.com/engine/install/)

**Versiones Mínimas:**
- Docker 20.10+
- Docker Compose 2.0+
- (En Windows: WSL 2 recomendado para mejor rendimiento)

**Verificar instalación:**
```powershell
docker --version
docker-compose --version
```

---

## 📁 Estructura del Proyecto

```
Tutorium/
│
├── 📂 app/                         # Lógica principal de la aplicación
│   ├── controller/                 # Controladores (manejan peticiones)
│   └── model/                      # Modelos (interactúan con la BD)
│
├── 📂 config/                      # Archivos de configuración
│   ├── database.php                # Configuración y conexión a MySQL
│   ├── php.ini                     # Configuración personalizada de PHP
│   └── mysql.cnf                   # Configuración personalizada de MySQL
│
├── 📂 middleware/                  # Middleware (filtros de peticiones)
│   └── (Archivos de middleware aquí)
│
├── 📂 public/                      # Carpeta pública (punto de entrada)
│   ├── index.php                   # Página principal (punto de entrada)
│   └── favicon.ico                 # Icono de la pestaña del navegador
│
├── 📂 resources/                   # Recursos estáticos y vistas
│   ├── css/                        # Estilos CSS
│   ├── enum/                       # Enumeraciones
│   ├── img/                        # Imágenes
│   ├── javascript/                 # Archivos JavaScript
│   │   └── Alerts.js               # Funciones de alertas
│   └── view/                       # Vistas (templates HTML)
│       └── layout/
│           └── navbar.php          # Barra de navegación
│
├── 📂 routes/                      # Definición de rutas
│   └── routes.php                  # Archivo principal de rutas
│
├── 📂 service/                     # Servicios (lógica reutilizable)
│   └── (Servicios de la aplicación)
│
├── 📄 .env                         # Variables de entorno (desarrollo)
├── 📄 .env.example                 # Plantilla de variables de entorno
├── 📄 .gitignore                   # Archivos a ignorar en Git
├── 📄 docker-compose.yml           # Configuración de Docker Compose
├── 📄 Dockerfile                   # Configuración de imagen PHP
├── 📄 docker-util.bat              # Script helper (Windows)
└── 📄 README.md                    # Este archivo
```

### 📖 Descripción Detallada de Carpetas

#### **app/** - Lógica de la Aplicación
Contiene la lógica principal de negocio del proyecto:
- `controller/`: Maneja las peticiones HTTP y coordina el flujo
- `model/`: Representa las entidades y maneja datos

#### **config/** - Configuraciones
- `database.php`: Crea la conexión PDO a MySQL usando variables de entorno
- `php.ini`: Parámetros de PHP (memory_limit, upload_max_filesize, etc.)
- `mysql.cnf`: Parámetros de MySQL (charset, conexiones máximas, etc.)

#### **public/** - Archivos Públicos
Carpeta raíz del servidor web. Accesible directamente desde el navegador.
- `index.php`: Punto de entrada principal (router frontal)
- `favicon.ico`: Icono del sitio

#### **resources/** - Recursos Estáticos
- `css/`: Hojas de estilos
- `img/`: Imágenes
- `javascript/`: Código JavaScript del cliente
- `view/`: Plantillas HTML/PHP reutilizables

#### **routes/** - Rutas
Definición de todas las rutas de la aplicación y mapeo a controladores.

#### **service/** - Servicios
Clases reutilizables que manejan lógica específica (validación, emails, pagos, etc.)

#### **middleware/** - Middleware
Filtros que procesan peticiones antes de llegar a los controladores.

---

## ⚙️ Configuración

### Archivo `.env` (Desarrollo)

Las variables ya están configuradas por defecto. Puedes modificarlas según tus necesidades:

```dotenv
# Base de Datos
DB_HOST=mysql              # nombre del servicio Docker
DB_PORT=3306
DB_NAME=tutorium_db
DB_USER=tutorium_user #utilizar un nombre distinto de: 'root'
DB_PASSWORD=tutorium_password
DB_ROOT_PASSWORD=root_password

# Aplicación
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Archivo `.env.example` (Para distribución)

Este archivo es una plantilla. Cópialo a `.env` y personaliza los valores:

```bash
cp .env.example .env
```

---

## 🌐 Acceso a Servicios

Una vez que los contenedores estén corriendo, accede a:

### Aplicación Web
```bash
php -S localhost:8000
```

```
http://localhost:8000
```
Aquí se ejecuta tu aplicación PHP.

### Gestor de Base de Datos (PhpMyAdmin)
```
http://localhost:8080
```
- **Usuario**: `tutorium_user`
- **Contraseña**: `tutorium_password` (configurado en .env)

### Database en tu Aplicación
```
host: mysql
port: 3306
username: tutorium_user (utilizen uno distinto a root)
password: tutorium_password
database: tutorium_db
```

---

## 💻 Comandos Útiles

### Ver Estado de Contenedores

```bash
# Ver todos los contenedores de este proyecto
docker-compose ps

# Ver logs en tiempo real
docker-compose logs -f

# Ver logs de un servicio específico
docker-compose logs php
docker-compose logs mysql
docker-compose logs phpmyadmin

# Ver últimas 50 líneas
docker-compose logs --tail=50
```

### Controlar Contenedores

```bash
# Iniciar contenedores
docker-compose up -d

# Detener contenedores
docker-compose down

# Reiniciar contenedores
docker-compose restart

# Reconstruir imágenes
docker-compose up -d --build
```

### Ejecutar Comandos en Contenedores

```bash
# Ejecutar comando en PHP
docker-compose exec php php -v
docker-compose exec php php -m                    # Listar extensiones
docker-compose exec php composer --version

# Instalar dependencias con Composer
docker-compose exec php composer install
docker-compose exec php composer update

# Ejecutar comandos en MySQL
docker-compose exec mysql mysql -u root -p       # Conectar a MySQL
docker-compose exec mysql mysql -u root -proot_password -e "SHOW DATABASES;"

# Acceder al shell del PHP
docker-compose exec php bash
```

### Acceso a MySQL

```bash
# Conectar a MySQL interactivamente
docker-compose exec mysql mysql -u user -p password 

# entrar a la base de datos
docker-compose exec mysql use tutorium_db

# Ejecutar comando SQL
docker-compose exec mysql mysql -u tutorium_user -ptutorium_password -e "SHOW TABLES;"
```

---

## 🔧 Variables de Entorno

### Variables de Base de Datos

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `DB_HOST` | mysql | Hostname del servicio Docker |
| `DB_PORT` | 3306 | Puerto de MySQL |
| `DB_NAME` | tutorium_db | Nombre de la base de datos |
| `DB_USER` | tutorium_user | Usuario de MySQL |
| `DB_PASSWORD` | tutorium_password | Contraseña del usuario |
| `DB_ROOT_PASSWORD` | root_password | Contraseña del root |

### Variables de Aplicación

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `APP_ENV` | development | Entorno (development/production) |
| `APP_DEBUG` | true | Modo debug (true/false) |
| `APP_URL` | http://localhost:8000 | URL base de la aplicación |

### Cómo Usar Variables en PHP

```php
<?php
require 'config/database.php';  // Aquí se cargan las variables

// Acceder a variables de entorno
$db_host = getenv('DB_HOST');
$db_name = getenv('DB_NAME');
$app_debug = getenv('APP_DEBUG');

// Con valores por defecto
$timeout = getenv('TIMEOUT') ?: 30;
?>
```

---

## 🐛 Errores comunes

### ❌ Error: "El puerto 3306 ya está en uso"

**Problema**: Ya hay otro servicio MySQL/MariaDB ejecutándose.

**Solución** (Opción 1): Usar otro puerto
```yaml
# Editar docker-compose.yml
mysql:
  ports:
    - "3307:3306"  # Cambiar 3306 a 3307
```

**Solución** (Opción 2): Detener el servicio existente
```bash
# En Windows (SQL Server, MySQL, etc.)
net stop MySQL80
```

---

### ❌ Error: "El puerto 8000 ya está en uso"

**Solución**: Cambiar puerto en docker-compose.yml
```yaml
php:
  ports:
    - "8001:8000"  # Cambiar 8000 a 8001
```
Luego acceder a `http://localhost:8001`

---

### ❌ Error: "Connection refused" en Base de Datos

**Problema**: PHP intenta conectar a `localhost` pero está en Docker.

**Solución**: Usar el nombre del servicio
```php
// ❌ INCORRECTO (en Docker)
$db_host = 'localhost';

// ✅ CORRECTO (en Docker)
$db_host = 'mysql';  // nombre del servicio en docker-compose.yml
```

---

### ❌ MySQL no inicia

**Solución**: Ver logs
```bash
docker-compose logs mysql

# Si hay error de permisos
docker-compose down -v
docker-compose up -d --build
```

---

### ❌ Cambios en Dockerfile no se aplican

**Problema**: Las imágenes están en caché.

**Solución**: Reconstruir
```bash
docker-compose up -d --build
```

---

### ✅ Ver información de debug

```bash
# Información del sistema Docker
docker system info

# Ver volúmenes
docker volume ls

# Ver redes
docker network ls

# Inspeccionar contenedor específico
docker-compose exec php php -i  # Info de PHP
```

---

## 📝 Próximos Pasos

1. **Configurar Base de Datos**
   - Abre PhpMyAdmin: http://localhost:8080
   - Crea las tablas necesarias

2. **Desarrollar la Aplicación**
   - Los archivos en `app/`, `resources/`, `routes/` son los puntos principales
   - Edita `routes/routes.php` para agregar rutas
   - Crea controladores en `app/controller/`

3. **Conectar Base de Datos en tu Código**
   ```php
   require 'config/database.php';
   // Ahora $pdo está disponible
   $result = $pdo->query("SELECT * FROM tabla");
   ```

4. **Instalar Dependencias (si usas Composer)**
   ```bash
   docker-compose exec php composer install
   ```

5. **Hacer Deploy a Producción**
   - Cambiar `APP_ENV=production` en `.env`
   - Cambiar `APP_DEBUG=false` en `.env`
   - Usar contraseñas seguras en `DB_PASSWORD`

---

## 📚 Recursos Útiles

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [PHP 8.2 Documentation](https://www.php.net/manual/en/index.php)
- [MySQL 8.2 Documentation](https://dev.mysql.com/doc/refman/8.0/en/)
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)

---

## 🤝 Soporte

Si tienes problemas:

1. Revisa los logs: `docker-compose logs -f`
2. Verifica que Docker esté corriendo
3. Asegúrate de que los puertos no estén en uso
4. Intenta con `docker-compose down -v` y `docker-compose up -d` nuevamente

---

## 📄 iniciar con el srvidor web de PHP

```bash
php -S localhost:8002 -t public
```

> Si estás en Windows, asegúrate de usar la ruta absoluta del router para que la ruta `/` muestre el login.

---

**¡Listo para desarrollar! 🚀 Accede a http://localhost:8000**

