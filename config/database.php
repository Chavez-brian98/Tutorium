<?php

namespace App;

use PDO;
use PDOException;

/**
 * Clase Database para gestionar la conexión a la base de datos
 *
 * Uso:
 *   $db = Database::getConnection();
 *   $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
 *   $stmt->execute([$id]);
 */
class Database
{
    private static $connection = null;

    /**
     * Obtener la conexión a la base de datos
     * Singleton: retorna la misma instancia siempre
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }

        return self::$connection;
    }

    /**
     * Crear una nueva conexión PDO
     *
     * @return PDO
     * @throws PDOException
     */
   private static function createConnection()
{
    $db_host     = getenv('DB_HOST') ?: 'mysql';
    $db_port     = getenv('DB_PORT') ?: 3306;
    $db_name     = getenv('DB_NAME') ?: 'tutorium_db';
    $db_user     = getenv('DB_USER') ?: 'karla';
    $db_password = getenv('DB_PASSWORD') ?: '123456';

    try {
        $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $pdo = new PDO($dsn, $db_user, $db_password, $options);
        $pdo->exec("SET NAMES utf8mb4");
        $pdo->exec("SET time_zone = '-06:00'");

        return $pdo;

    } catch (PDOException $e) {
        throw new PDOException('Error de conexión a base de datos: ' . $e->getMessage());
    }
}

    /**
     * Cerrar la conexión
     */
    public static function closeConnection()
    {
        self::$connection = null;
    }
}

