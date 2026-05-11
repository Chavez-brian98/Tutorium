<?php

/**
 * Configuración de Conexión a Base de Datos
 *
 * Usa variables de entorno del archivo .env
 */

// Variables de entorno
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: 3306;
$db_name = getenv('DB_NAME') ?: 'tutorium_db';
$db_user = getenv('DB_USER') ?: 'tutorium_user';
$db_password = getenv('DB_PASSWORD') ?: 'tutorium_password';

try {
    // PDO DSN (Data Source Name)
    $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";

    // Opciones de conexión
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Crear conexión
    $pdo = new PDO($dsn, $db_user, $db_password, $options);

} catch (PDOException $e) {
    die('Error de conexión a base de datos: ' . $e->getMessage());
}

// Retornar la conexión para usar en otras partes
// return $pdo;


