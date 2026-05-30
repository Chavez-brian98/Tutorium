<?php

namespace App\Model;

use App\Database;
use PDO;
use PDOException;

class Session {
    private $db;

    public function __construct() {
        // Asegúrate de que use tu conexión global de base de datos
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene una sesión específica por el ID de tutoría y el número de sesión
     */
    public function obtenerPorTutoria($tutoria_id, $numero) {
    try {
        $sql = "SELECT s.*, t.alumno_id 
                FROM sesiones_tutoria s
                INNER JOIN tutorias t ON s.tutoria_id = t.id
                WHERE s.tutoria_id = ? AND s.numero = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tutoria_id, $numero]);
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    } catch (PDOException $e) {
        die("Error crítico SQL: " . $e->getMessage());
    }
}

    /**
     * Obtiene la lista de todas las sesiones de una tutoría (para el menú lateral)
     */
    public function obtenerTodasPorTutoria($tutoria_id) {
        try {
            $sql = "SELECT id, numero, fecha, link 
                    FROM sesiones_tutoria 
                    WHERE tutoria_id = ? 
                    ORDER BY numero ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tutoria_id]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerTodasPorTutoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene los metadatos de la tutoría mapiando el typo (hora_incio)
     */
    public function obtenerDatosTutoria($tutoria_id) {
        try {
            // Esta consulta une la tutoría con el nombre de la materia y el alumno
            $sql = "SELECT t.fecha, t.hora_incio, t.hora_fin, m.nombre AS materia, 
                           CONCAT(u.nombres, ' ', u.apellidos) AS nombre_alumno
                    FROM tutorias t
                    INNER JOIN materias m ON t.materia_id = m.id
                    INNER JOIN usuarios u ON t.alumno_id = u.id
                    WHERE t.id = ?
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$tutoria_id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en obtenerDatosTutoria: " . $e->getMessage());
            return false;
        }
    }

    public function guardarLink($sesion_id, $link) {
        try {
            $sql = "UPDATE sesiones_tutoria SET link = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$link, $sesion_id]);
        } catch (PDOException $e) { // Quitamos la barra aquí ya que usas 'use PDOException;' arriba
            error_log("Error en guardarLink: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorId($sesion_id) {
        try {
            // Mejoramos la consulta para asegurar que traiga 'alumno_id' de forma idéntica a obtenerPorTutoria
            $sql = "SELECT s.*, t.alumno_id 
                    FROM sesiones_tutoria s
                    INNER JOIN tutorias t ON s.tutoria_id = t.id
                    WHERE s.id = ? 
                    LIMIT 1";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sesion_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC); // Quitamos la barra aquí ya que usas 'use PDO;' arriba
        } catch (PDOException $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return false;
        }
    }
}