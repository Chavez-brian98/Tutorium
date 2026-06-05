<?php

namespace App\Model;

class Evaluation {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    public function crear($tutoria_id, $tutor_id, $titulo, $descripcion) {
        $stmt = $this->db->prepare("
            INSERT INTO evaluaciones (tutoria_id, creada_por, titulo, descripcion)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$tutoria_id, $tutor_id, $titulo, $descripcion]);
        return $this->db->lastInsertId();
    }

    public function obtenerDetallePdf($evaluacion_id, $alumno_id) {
        $sql = "
            SELECT
                p.id AS pregunta_id,
                p.enunciado,
                p.tipo,
                ra.opcion_id AS respuesta_alumno_id,
                o_resp.texto AS respuesta_alumno,
                o_corr.texto AS respuesta_correcta,
                o_corr.id AS opcion_correcta_id,
                CASE WHEN o_resp.id = o_corr.id THEN 1 ELSE 0 END AS es_correcta
            FROM preguntas p
            LEFT JOIN respuestas_alumno ra ON ra.pregunta_id = p.id AND ra.alumno_id = ?
            LEFT JOIN opciones_respuesta o_resp ON o_resp.id = ra.opcion_id
            LEFT JOIN opciones_respuesta o_corr ON o_corr.pregunta_id = p.id AND o_corr.es_correcta = 1
            WHERE p.evaluacion_id = ?
            ORDER BY p.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$alumno_id, $evaluacion_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerHistorial($usuario_id, $rol) {
        if ($rol === 'admin') {
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    e.fecha_creacion AS fecha,
                    u.nombres,
                    u.apellidos,
                    m.nombre AS materia_nombre,
                    -- promedio de respuestas correctas
                    COALESCE((
                        SELECT ROUND(AVG(ra_calc.correcta) * 10, 1)
                        FROM (
                            SELECT
                                ra.pregunta_id,
                                MAX(CASE WHEN o.es_correcta = 1 THEN 1 ELSE 0 END) AS correcta
                            FROM respuestas_alumno ra
                            LEFT JOIN opciones_respuesta o ON o.id = ra.opcion_id
                            WHERE ra.evaluacion_id = e.id
                            GROUP BY ra.pregunta_id
                        ) ra_calc
                    ), NULL) AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->query($sql);
        } elseif ($rol === 'tutor') {
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    e.fecha_creacion AS fecha,
                    u.nombres,
                    u.apellidos,
                    m.nombre AS materia_nombre,
                    COALESCE((
                        SELECT ROUND(AVG(ra_calc.correcta) * 10, 1)
                        FROM (
                            SELECT
                                ra.pregunta_id,
                                MAX(CASE WHEN o.es_correcta = 1 THEN 1 ELSE 0 END) AS correcta
                            FROM respuestas_alumno ra
                            LEFT JOIN opciones_respuesta o ON o.id = ra.opcion_id
                            WHERE ra.evaluacion_id = e.id
                            GROUP BY ra.pregunta_id
                        ) ra_calc
                    ), NULL) AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                WHERE t.tutor_id = ?
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
        } else {
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    e.fecha_creacion AS fecha,
                    u.nombres,
                    u.apellidos,
                    m.nombre AS materia_nombre,
                    COALESCE((
                        SELECT ROUND(AVG(ra_calc.correcta) * 10, 1)
                        FROM (
                            SELECT
                                ra.pregunta_id,
                                MAX(CASE WHEN o.es_correcta = 1 THEN 1 ELSE 0 END) AS correcta
                            FROM respuestas_alumno ra
                            LEFT JOIN opciones_respuesta o ON o.id = ra.opcion_id
                            WHERE ra.evaluacion_id = e.id AND ra.alumno_id = ?
                            GROUP BY ra.pregunta_id
                        ) ra_calc
                    ), NULL) AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                WHERE t.alumno_id = ?
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $usuario_id]);
        }

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return [
                'id'          => $row['id'],
                'titulo'      => $row['titulo'],
                'estudiante'  => trim(($row['nombres'] ?? '') . ' ' . ($row['apellidos'] ?? '')),
                'materia'     => $row['materia_nombre'],
                'fecha'       => $row['fecha'],
                'nota'        => $row['nota'] !== null ? (float) $row['nota'] : null,
                'descripcion' => $row['descripcion'] ?? '',
                'creado'      => $row['fecha'],
                'actualizado' => null,
            ];
        }, $rows);
    }
}