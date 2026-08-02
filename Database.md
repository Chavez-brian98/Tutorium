Aquí tienes la estructura y semántica de tu base de datos documentada en un formato Markdown optimizado. Está diseñada con descripciones claras, tipos de datos y un mapa de relaciones (*Foreign Keys*) pensado específicamente para que un **agente de IA u otro desarrollador** pueda leer, mapear y razonar sobre ella a la perfección.

---

# 📝 Documentación de Base de Datos: `tutorium_db`

Esta base de datos gestiona un sistema de tutorías en línea, incluyendo el control de usuarios (administradores, tutores y alumnos), asignación de tutorías, asistencia a sesiones individuales, material de estudio y un sistema completo de evaluaciones dinámicas.

---

## 🗺️ Resumen de Entidades y Relaciones

El siguiente listado describe las dependencias y llaves foráneas (`FK`) para entender la jerarquía del esquema:

* **`usuarios`**: Entidad base. No depende de ninguna otra tabla.
* **`materias`**: Entidad base de los cursos disponibles.
* **`tutorias`**: Vincula un `alumno_id` (`usuarios`), un `tutor_id` (`usuarios`) y una `materia_id`.
* **`sesiones_tutoria`**: Cada tutoría se divide en múltiples sesiones (`tutoria_id`).
* **`asistencias`**: Registra la asistencia de un alumno a una sesión específica. Depende de `tutorias`, `sesiones_tutoria` y `usuarios`.
* **`material_tutoria`**: Contenido o archivos adjuntos de una sesión (`id_sesion`).
* **`evaluaciones`**: Cuestionarios creados por un tutor (`creada_por`) para una tutoría específica (`tutoria_id`).
* **`preguntas`**: Reactivos asociados a una evaluación (`evaluacion_id`).
* **`opciones_respuesta`**: Opciones múltiples asignadas a una pregunta (`pregunta_id`).
* **`respuestas_alumno`**: Respuestas definitivas enviadas. Conecta `usuarios` (alumno), `evaluaciones`, `preguntas` y opcionalmente `opciones_respuesta`.

---

## 🗂️ Diccionario de Tablas

### 1. Tabla: `usuarios`

Almacena las credenciales, datos de contacto y roles del sistema.

* **Llave Primaria:** `id` (INT, Auto-increment)
* **Restricciones:** `email` debe ser único.

| Campo | Tipo | Atributos / Descripción |
| --- | --- | --- |
| `id` | INT | Clave única autoincrementable. |
| `nombres` | VARCHAR(50) | Nombre(s) del usuario. |
| `apellidos` | VARCHAR(60) | Apellido(s) del usuario. |
| `email` | VARCHAR(100) | Correo electrónico institucional/personal (Único). |
| `telefono` | VARCHAR(8) | Número telefónico de contacto. |
| `password` | VARCHAR(255) | Contraseña encriptada (Ej: Bcrypt hash). |
| `rol` | ENUM | Roles permitidos: `'admin'`, `'tutor'`, `'alumno'`. |
| `estado` | ENUM | Estado de la cuenta: `'ACTIVO'`, `'INACTIVO'`. Por defecto `'ACTIVO'`. |

---

### 2. Tabla: `materias`

Catálogo de asignaturas disponibles para impartir tutorías.

* **Llave Primaria:** `id` (INT, Auto-increment)
* **Restricciones:** `codigo` debe ser único.

| Campo | Tipo | Atributos / Descripción |
| --- | --- | --- |
| `id` | INT | Clave única. |
| `nombre` | VARCHAR(100) | Nombre de la materia (Ej: Matemáticas). |
| `codigo` | VARCHAR(20) | Código identificador (Ej: MAT-101) (Único). |
| `descripcion` | TEXT | Breve resumen de los temas del curso. |
| `estado` | ENUM | Disponibilidad: `'ACTIVO'`, `'INACTIVO'`. Por defecto `'ACTIVO'`. |

---

### 3. Tabla: `tutorias`

Contrato o asignación principal de un ciclo de estudio personalizado entre un tutor y un alumno.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única de la tutoría. |
| `alumno_id` | INT | `usuarios.id` | El alumno que recibe la clase. |
| `tutor_id` | INT | `usuarios.id` | El tutor que imparte la materia. |
| `materia_id` | INT | `materias.id` | La asignatura dictada. |
| `fecha` | DATE | - | Fecha de inicio del bloque de tutorías. |
| `hora_incio` | TIME | - | Hora de inicio planificada. |
| `hora_fin` | TIME | - | Hora de finalización planificada. |
| `num_sesiones` | INT | - | Cantidad total de sesiones contratadas/pautadas (RF-03). |
| `estado` | ENUM | - | Progreso global: `'PENDIENTE'`, `'COMPLETADA'`, `'CANCELADA'`. |

---

### 4. Tabla: `sesiones_tutoria`

Desglose cronológico e individualizado de cada clase perteneciente a una tutoría general.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única de la sesión. |
| `tutoria_id` | INT | `tutorias.id` | Bloque principal de tutoría al que pertenece. |
| `numero` | INT | - | Consecutivo de la clase (Ej: Sesión 1, Sesión 2). |
| `fecha` | DATE | - | Fecha específica en la que se impartirá la sesión. |
| `hora_inicio` | TIME | - | Hora exacta de inicio (Permite null). |
| `hora_fin` | TIME | - | Hora exacta de fin (Permite null). |
| `link` | VARCHAR(255) | - | Enlace URL de la videoconferencia (Ej: YouTube, Meet, Zoom). |

---

### 5. Tabla: `asistencias`

Registro de asistencia por alumno a cada sesión particular.

* **Llave Primaria:** `id` (INT, Auto-increment)
* **Restricciones:** Llave única combinada (`sesion_id`, `alumno_id`) para evitar duplicados en la misma clase.

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Identificador único de asistencia. |
| `tutoria_id` | INT | `tutorias.id` | Contexto de la tutoría global. |
| `sesion_id` | INT | `sesiones_tutoria.id` | La sesión específica evaluada. |
| `alumno_id` | INT | `usuarios.id` | El alumno evaluado. |
| `fecha` | DATE | - | Fecha de toma de asistencia. |
| `presente` | TINYINT(1) | - | `1` = Presente, `0` = Ausente (Por defecto `0`). |

---

### 6. Tabla: `material_tutoria`

Recursos, apuntes didácticos o bitácoras asociadas a una sesión.

* **Llave Primaria:** `id` (INT, Auto-increment)
* **Restricciones:** `id_sesion` es único (Relación 1 a 1 implícita).

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única del material. |
| `id_sesion` | INT | `sesiones_tutoria.id` | Sesión a la que se le asigna el recurso. |
| `texto` | TEXT | - | Contenido enriquecido de texto (Soporta CKeditor). |
| `archivo` | VARCHAR(100) | - | Nombre físico del archivo cargado (Ej: `actividad1.pdf`). |
| `ruta` | VARCHAR(255) | - | Ubicación del archivo en el servidor (Ej: `resources/docs/`). |
| `tipo` | VARCHAR(50) | - | Extensión del documento (Ej: `pdf`, `docx`). |
| `fecha_creacion` | TIMESTAMP | - | Timestamp del momento de registro. |
| `fecha_modificacion` | TIMESTAMP | - | Última edición del material. |

---

### 7. Tabla: `evaluaciones`

Cuestionarios o exámenes generados en el marco de una tutoría.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única del examen. |
| `tutoria_id` | INT | `tutorias.id` | Curso/Tutoría donde se aplica. |
| `creada_por` | INT | `usuarios.id` | Id del usuario que la diseñó (Rol esperado: Tutor). |
| `titulo` | VARCHAR(150) | - | Nombre de la evaluación (Por defecto 'Sin título'). |
| `descripcion` | TEXT | - | Instrucciones u observaciones del examen. |
| `nota_calificada` | DECIMAL(4,1) | - | Calificación asignada manualmente por el tutor (NULL si no se ha calificado). |
| `comentarios_calificacion` | TEXT | - | Retroalimentación escrita del tutor sobre la evaluación. |
| `fecha_creacion` | TIMESTAMP | - | Fecha de alta automática (`NOW()`). |

---

### 8. Tabla: `preguntas`

Cuerpo de reactivos asociados a una evaluación. Soporta tres modalidades de respuesta.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única de la pregunta. |
| `evaluacion_id` | INT | `evaluaciones.id` | Examen al que pertenece. |
| `enunciado` | TEXT | - | Texto de la pregunta o instrucción. |
| `tipo` | ENUM | - | Modalidades: `'opcion_multiple'`, `'respuesta_corta'`, `'VyF'` (Verdadero y Falso). |

---

### 9. Tabla: `opciones_respuesta`

Opciones de selección precargadas para preguntas de tipo `opcion_multiple` o `VyF`.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única de la opción. |
| `pregunta_id` | INT | `preguntas.id` | Pregunta que contiene esta alternativa. |
| `texto` | TEXT | - | La respuesta sugerida como opción. |
| `es_correcta` | TINYINT(1) | - | Indica si es la solución: `1` = Sí, `0` = No. |

---

### 10. Tabla: `respuestas_alumno`

Bitácora donde se consolida el envío de exámenes por parte de los alumnos y sus soluciones.

* **Llave Primaria:** `id` (INT, Auto-increment)

| Campo | Tipo | Relación (FK) | Descripción |
| --- | --- | --- | --- |
| `id` | INT | - | Clave única del registro de respuesta. |
| `evaluacion_id` | INT | `evaluaciones.id` | Evaluación que se está respondiendo. |
| `alumno_id` | INT | `usuarios.id` | Estudiante que responde el examen. |
| `pregunta_id` | INT | `preguntas.id` | Pregunta específica contestada. |
| `opcion_id` | INT (Null) | `opciones_respuesta.id` | Id elegido (Se usa si la pregunta es `opcion_multiple` o `VyF`). |
| `respuesta_texto` | TEXT (Null) | - | Respuesta escrita a mano (Se usa si la pregunta es `respuesta_corta`). |
| `fecha_respuesta` | TIMESTAMP | - | Instante exacto de envío. |

---

## 💡 Notas Técnicas Especiales para el Agente

1. **Booleanos Simulado:** El motor utiliza `TINYINT(1)` con valores `0` y `1` para representar estados lógicos binarios (por ejemplo en `presente` de asistencias y `es_correcta` de opciones de respuesta).
2. **Estrategia de Examen Polimórfico:** Al evaluar las respuestas del alumno en `respuestas_alumno`, el agente debe verificar el campo `tipo` de la tabla `preguntas`. Si es `respuesta_corta`, la columna `opcion_id` se mantendrá en `NULL` y el contenido residirá en `respuesta_texto`. Si es de opción múltiple o falso/verdadero, ocurrirá lo contrario de manera mandatoria.