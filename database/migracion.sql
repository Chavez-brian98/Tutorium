-- ============================================================
-- Migración: Agregar columnas de calificación y backfill
-- de sesiones_tutoria faltantes
-- ============================================================

-- 1. Agregar columna sesion_id a evaluaciones (per-session)
SET @existSesion = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'evaluaciones' AND COLUMN_NAME = 'sesion_id');
SET @sqlSesion = IF(@existSesion = 0,
    'ALTER TABLE evaluaciones ADD COLUMN sesion_id INT DEFAULT NULL AFTER tutoria_id,
     ADD FOREIGN KEY (sesion_id) REFERENCES sesiones_tutoria(id) ON DELETE CASCADE',
    'SELECT 1');
PREPARE stmtSesion FROM @sqlSesion; EXECUTE stmtSesion; DEALLOCATE PREPARE stmtSesion;

-- 2. Agregar columna para calificación manual en evaluaciones
SET @exist1 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'evaluaciones' AND COLUMN_NAME = 'nota_calificada');
SET @exist2 = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'evaluaciones' AND COLUMN_NAME = 'comentarios_calificacion');

SET @sql1 = IF(@exist1 = 0, 'ALTER TABLE evaluaciones ADD COLUMN nota_calificada DECIMAL(4,1) DEFAULT NULL AFTER descripcion', 'SELECT 1');
SET @sql2 = IF(@exist2 = 0, 'ALTER TABLE evaluaciones ADD COLUMN comentarios_calificacion TEXT DEFAULT NULL AFTER nota_calificada', 'SELECT 1');

PREPARE stmt1 FROM @sql1; EXECUTE stmt1; DEALLOCATE PREPARE stmt1;
PREPARE stmt2 FROM @sql2; EXECUTE stmt2; DEALLOCATE PREPARE stmt2;

-- 3. Asignar sesion_id a evaluaciones existentes que no lo tienen
-- (usa la primera sesión de cada tutoría)
UPDATE evaluaciones e
JOIN (
    SELECT tutoria_id, MIN(id) AS primera_sesion_id
    FROM sesiones_tutoria
    GROUP BY tutoria_id
) s ON s.tutoria_id = e.tutoria_id
SET e.sesion_id = s.primera_sesion_id
WHERE e.sesion_id IS NULL;

-- 4. Insertar sesiones faltantes para tutorías existentes
INSERT INTO sesiones_tutoria (tutoria_id, numero, fecha)
SELECT t.id, s.n, COALESCE(t.fecha, CURDATE())
FROM tutorias t
CROSS JOIN (
    SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4
    UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8
    UNION SELECT 9 UNION SELECT 10
) s
WHERE t.num_sesiones > 0
  AND s.n <= t.num_sesiones
  AND NOT EXISTS (
      SELECT 1 FROM sesiones_tutoria st WHERE st.tutoria_id = t.id
  );
