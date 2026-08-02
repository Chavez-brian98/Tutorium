<?php

namespace App\Model;

class Evaluation {
    private $db;

    public function __construct() {
        $this->db = \App\Database::getConnection();
    }

    public function crear($tutoria_id, $tutor_id, $titulo, $descripcion, $sesion_id = null) {
        $tieneSesion = $this->columnaExiste('evaluaciones', 'sesion_id');
        if ($tieneSesion) {
            $stmt = $this->db->prepare("
                INSERT INTO evaluaciones (tutoria_id, sesion_id, creada_por, titulo, descripcion)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$tutoria_id, $sesion_id, $tutor_id, $titulo, $descripcion]);
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO evaluaciones (tutoria_id, creada_por, titulo, descripcion)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$tutoria_id, $tutor_id, $titulo, $descripcion]);
        }
        return $this->db->lastInsertId();
    }

    public function obtenerDatosPdf($evaluacion_id) {
        $stmt = $this->db->prepare("
            SELECT e.id, e.titulo, e.descripcion, e.fecha_creacion,
                   u.nombres, u.apellidos, m.nombre AS materia,
                   CONCAT(a.nombres, ' ', a.apellidos) AS alumno_nombre
            FROM evaluaciones e
            JOIN tutorias t ON t.id = e.tutoria_id
            JOIN materias m ON m.id = t.materia_id
            JOIN usuarios u ON u.id = e.creada_por
            JOIN usuarios a ON a.id = t.alumno_id
            WHERE e.id = ?
        ");
        $stmt->execute([$evaluacion_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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

    public function obtenerPreguntasConOpciones($evaluacion_id) {
        $stmt = $this->db->prepare("
            SELECT p.id AS pregunta_id, p.enunciado, p.tipo
            FROM preguntas p
            WHERE p.evaluacion_id = ?
            ORDER BY p.id
        ");
        $stmt->execute([$evaluacion_id]);
        $preguntas = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($preguntas as &$pregunta) {
            $stmtOpciones = $this->db->prepare("
                SELECT id, texto, es_correcta
                FROM opciones_respuesta
                WHERE pregunta_id = ?
                ORDER BY id
            ");
            $stmtOpciones->execute([$pregunta['pregunta_id']]);
            $pregunta['opciones'] = $stmtOpciones->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $preguntas;
    }

    public function guardarRespuestas($evaluacion_id, $alumno_id, $respuestas) {
        $deleteStmt = $this->db->prepare("DELETE FROM respuestas_alumno WHERE evaluacion_id = ? AND alumno_id = ?");
        $deleteStmt->execute([$evaluacion_id, $alumno_id]);

        $stmt = $this->db->prepare("
            INSERT INTO respuestas_alumno (evaluacion_id, alumno_id, pregunta_id, opcion_id, respuesta_texto, fecha_respuesta)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        foreach ($respuestas as $r) {
            if (empty($r['opcion_id']) && empty($r['respuesta_texto'])) {
                continue;
            }
            $stmt->execute([
                $evaluacion_id,
                $alumno_id,
                $r['pregunta_id'],
                $r['opcion_id'] ?? null,
                $r['respuesta_texto'] ?? null,
            ]);
        }
        return true;
    }

    public function obtenerConPreguntas($evaluacion_id) {
        $stmt = $this->db->prepare("SELECT * FROM evaluaciones WHERE id = ?");
        $stmt->execute([$evaluacion_id]);
        $evaluacion = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$evaluacion) return null;
        $evaluacion['preguntas'] = $this->obtenerPreguntasConOpciones($evaluacion_id);
        return $evaluacion;
    }

    public function actualizar($evaluacion_id, $titulo, $descripcion) {
        $stmt = $this->db->prepare("UPDATE evaluaciones SET titulo = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$titulo, $descripcion, $evaluacion_id]);
    }

    public function eliminarPreguntasConOpciones($evaluacion_id) {
        $this->db->prepare("DELETE FROM respuestas_alumno WHERE evaluacion_id = ?")->execute([$evaluacion_id]);
        $this->db->prepare("
            DELETE o FROM opciones_respuesta o
            JOIN preguntas p ON p.id = o.pregunta_id
            WHERE p.evaluacion_id = ?
        ")->execute([$evaluacion_id]);
        $this->db->prepare("DELETE FROM preguntas WHERE evaluacion_id = ?")->execute([$evaluacion_id]);
    }

    public function eliminar($evaluacion_id) {
        $this->eliminarPreguntasConOpciones($evaluacion_id);
        $this->db->prepare("DELETE FROM evaluaciones WHERE id = ?")->execute([$evaluacion_id]);
    }

    public function guardarCalificacion($evaluacion_id, $nota, $comentarios) {
        if (!$this->columnaExiste('evaluaciones', 'nota_calificada')) {
            return false;
        }
        $stmt = $this->db->prepare("
            UPDATE evaluaciones
            SET nota_calificada = ?, comentarios_calificacion = ?
            WHERE id = ?
        ");
        $stmt->execute([$nota, $comentarios, $evaluacion_id]);
        return true;
    }

    public function obtenerPorTutoria($tutoria_id) {
        $stmt = $this->db->prepare("
            SELECT e.id, e.titulo, e.descripcion, e.fecha_creacion,
                   CONCAT(u.nombres, ' ', u.apellidos) AS creado_por
            FROM evaluaciones e
            JOIN usuarios u ON u.id = e.creada_por
            WHERE e.tutoria_id = ?
            LIMIT 1
        ");
        $stmt->execute([$tutoria_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function obtenerPorSesion($sesion_id) {
        if (!$this->columnaExiste('evaluaciones', 'sesion_id')) {
            return null;
        }
        $stmt = $this->db->prepare("
            SELECT e.id, e.titulo, e.descripcion, e.fecha_creacion, e.sesion_id,
                   CONCAT(u.nombres, ' ', u.apellidos) AS creado_por
            FROM evaluaciones e
            JOIN usuarios u ON u.id = e.creada_por
            WHERE e.sesion_id = ?
            LIMIT 1
        ");
        $stmt->execute([$sesion_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function obtenerRespuestasAlumno($evaluacion_id, $alumno_id) {
        $sql = "
            SELECT
                p.id AS pregunta_id,
                p.enunciado,
                p.tipo,
                ra.opcion_id,
                ra.respuesta_texto,
                o_resp.texto AS respuesta_alumno_texto,
                o_corr.texto AS respuesta_correcta_texto,
                CASE WHEN o_resp.id IS NOT NULL AND o_resp.id = o_corr.id THEN 1 ELSE 0 END AS es_correcta
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
        $tieneCalif = $this->columnaExiste('evaluaciones', 'nota_calificada');

        $subqueryNota = "
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
        ";

        $colsCalif = $tieneCalif
            ? "e.nota_calificada, e.comentarios_calificacion,"
            : "";
        $notaExpr = $tieneCalif
            ? "COALESCE(e.nota_calificada, ({$subqueryNota}))"
            : "({$subqueryNota})";

        if ($rol === 'admin') {
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    {$colsCalif}
                    e.fecha_creacion AS fecha,
                    CONCAT(a.nombres, ' ', a.apellidos) AS estudiante,
                    m.nombre AS materia_nombre,
                    t.alumno_id,
                    t.id AS tutoria_id,
                    {$notaExpr} AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                JOIN usuarios a ON a.id = t.alumno_id
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->query($sql);
        } elseif ($rol === 'tutor') {
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    {$colsCalif}
                    e.fecha_creacion AS fecha,
                    CONCAT(a.nombres, ' ', a.apellidos) AS estudiante,
                    m.nombre AS materia_nombre,
                    t.alumno_id,
                    t.id AS tutoria_id,
                    {$notaExpr} AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                JOIN usuarios a ON a.id = t.alumno_id
                WHERE t.tutor_id = ?
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id]);
        } else {
            $subqueryAlumno = "
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
            ";
            $notaExprAlumno = $tieneCalif
                ? "COALESCE(e.nota_calificada, ({$subqueryAlumno}))"
                : "({$subqueryAlumno})";
            $sql = "
                SELECT
                    e.id,
                    e.titulo,
                    e.descripcion,
                    {$colsCalif}
                    e.fecha_creacion AS fecha,
                    CONCAT(a.nombres, ' ', a.apellidos) AS estudiante,
                    m.nombre AS materia_nombre,
                    {$notaExprAlumno} AS nota
                FROM evaluaciones e
                JOIN tutorias t ON t.id = e.tutoria_id
                JOIN materias m ON m.id = t.materia_id
                JOIN usuarios u ON u.id = e.creada_por
                JOIN usuarios a ON a.id = t.alumno_id
                WHERE t.alumno_id = ?
                ORDER BY e.fecha_creacion DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$usuario_id, $usuario_id]);
        }

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($row) {
            return [
                'id'                       => $row['id'],
                'titulo'                   => $row['titulo'],
                'estudiante'               => $row['estudiante'] ?? '',
                'materia'                  => $row['materia_nombre'],
                'fecha'                    => $row['fecha'],
                'nota'                     => $row['nota'] !== null ? (float) $row['nota'] : null,
                'descripcion'              => $row['descripcion'] ?? '',
                'comentarios_calificacion' => $row['comentarios_calificacion'] ?? '',
                'alumno_id'                => $row['alumno_id'] ?? null,
                'tutoria_id'               => $row['tutoria_id'] ?? null,
                'creado'                   => $row['fecha'],
                'actualizado'              => $row['fecha'],
            ];
        }, $rows);
    }

    public function obtenerNotasTutoria($tutoria_id, $alumno_id) {
        $tieneCalif = $this->columnaExiste('evaluaciones', 'nota_calificada');
        $subquery = "
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
        ";
        $notaExpr = $tieneCalif
            ? "COALESCE(e.nota_calificada, ({$subquery}))"
            : "({$subquery})";

        $sql = "
            SELECT
                e.id,
                e.titulo,
                {$notaExpr} AS nota
            FROM evaluaciones e
            WHERE e.tutoria_id = ?
            ORDER BY e.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$alumno_id, $tutoria_id]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $notas = [];
        $suma = 0;
        foreach ($rows as $r) {
            $n = $r['nota'] !== null ? (float) $r['nota'] : null;
            $notas[] = [
                'id'    => $r['id'],
                'titulo'=> $r['titulo'],
                'nota'  => $n,
            ];
            if ($n !== null) {
                $suma += $n;
            }
        }
        $promedio = count($notas) > 0 ? round($suma / count($notas), 1) : 0;

        return [
            'evaluaciones' => $notas,
            'promedio'     => $promedio,
        ];
    }

    public function columnaExiste($tabla, $columna) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
            ");
            $stmt->execute([$tabla, $columna]);
            return (int) $stmt->fetchColumn() > 0;
        } catch (\PDOException $e) {
            return false;
        }
    }
}