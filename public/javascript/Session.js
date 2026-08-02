document.addEventListener("DOMContentLoaded", function () {
  // 1. Analizar si existen parámetros de éxito en la URL actual
  const urlParams = new URLSearchParams(window.location.search);

  if (urlParams.get("ok") === "evaluacion") {
    // 2. Disparar el Toast de SweetAlert2
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
      },
    });

    Toast.fire({
      icon: "success",
      title: "¡Evaluación creada con éxito!",
    });

    // 3. Opcional: Limpiar la URL para que no vuelva a salir el toast si el usuario refresca la página
    const limpiaUrl =
      window.location.protocol +
      "//" +
      window.location.host +
      window.location.pathname +
      "?numero=1";
    window.history.replaceState({ path: limpiaUrl }, "", limpiaUrl);
  }
});

// ── Toggle edición ───────────────────────────────────────
let editorInstance = null;
let edicionActiva = false;

function toggleEdicion() {
  edicionActiva = !edicionActiva;

  const seccionEditable = document.getElementById("seccion-editable");
  const seccionLectura = document.getElementById("seccion-lectura");
  const toggleBtn = document.getElementById("toggle-btn");
  const toggleDot = document.getElementById("toggle-dot");
  const toggleLabel = document.getElementById("toggle-label");
  const linkInput = document.getElementById("link-sesion");

  if (edicionActiva) {
    seccionEditable.classList.remove("hidden");
    seccionLectura.classList.add("hidden");
    linkInput.disabled = false;

    toggleBtn.classList.remove("bg-gray-200");
    toggleBtn.classList.add("bg-[#9e2820]");
    toggleDot.classList.add("translate-x-5");
    toggleLabel.textContent = "Edición activada";
    toggleLabel.classList.remove("text-gray-400");
    toggleLabel.classList.add("text-[#9e2820]");

    if (!editorInstance) {
      ClassicEditor.create(document.querySelector("#editor-sesion"), {
        placeholder: `Escribe el contenido de la sesión ${SESSION_CONFIG.numero}...`,
        toolbar: [
          "heading",
          "|",
          "bold",
          "italic",
          "underline",
          "|",
          "bulletedList",
          "numberedList",
          "|",
          "link",
          "blockQuote",
          "|",
          "undo",
          "redo",
        ],
      })
        .then((editor) => {
          editorInstance = editor;
        })
        .catch((error) => console.error(error));
    }
  } else {
    seccionEditable.classList.add("hidden");
    seccionLectura.classList.remove("hidden");
    linkInput.disabled = true;

    toggleBtn.classList.remove("bg-[#9e2820]");
    toggleBtn.classList.add("bg-gray-200");
    toggleDot.classList.remove("translate-x-5");
    toggleLabel.textContent = "Edición desactivada";
    toggleLabel.classList.remove("text-[#9e2820]");
    toggleLabel.classList.add("text-gray-400");
  }
}

// ── Asistencia ───────────────────────────────────────────
let asistenciaYaMarcada = SESSION_CONFIG.yaAsistencia;
let asistenciaActual = SESSION_CONFIG.asisPresente;

function marcarAsistencia() {
  const titulo = asistenciaYaMarcada
    ? `Modificar asistencia - Sesión ${SESSION_CONFIG.numero}`
    : `Sesión ${SESSION_CONFIG.numero}`;

  const texto = asistenciaYaMarcada
    ? `Actualmente marcada como <b>${asistenciaActual ? "presente" : "ausente"}</b>. ¿Deseas cambiarla?`
    : "¿El estudiante asistió a esta sesión?";

  Swal.fire({
    title: titulo,
    html: texto,
    icon: "question",
    showDenyButton: true,
    confirmButtonText: "Sí asistió",
    denyButtonText: "No asistió",
    confirmButtonColor: "#9e2820",
    denyButtonColor: "#6b7280",
  }).then((result) => {
    if (!result.isConfirmed && !result.isDenied) return;

    const presente = result.isConfirmed ? 1 : 0;

    fetch("/attendance/marcar", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        tutoria_id: SESSION_CONFIG.tutoriaId,
        sesion_id: SESSION_CONFIG.sesionId,
        alumno_id: SESSION_CONFIG.alumnoId,
        presente: presente,
      }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.ok) {
          asistenciaYaMarcada = true;
          asistenciaActual = presente === 1;

          document.getElementById("icono-asistencia").innerHTML = presente
            ? '<i class="bi bi-check-circle-fill text-green-600"></i>'
            : '<i class="bi bi-x-circle-fill text-red-500"></i>';
          document.getElementById("texto-asistencia").textContent =
            "Modificar asistencia";

          Swal.fire(
            presente ? "Asistencia marcada" : "Ausencia marcada",
            "",
            presente ? "success" : "info",
          );
        } else {
          Swal.fire("Error", data.error ?? "No se pudo guardar.", "error");
        }
      })
      .catch(() =>
        Swal.fire("Error", "No se pudo conectar con el servidor.", "error"),
      );
  });
}

// ── Guardar contenido ────────────────────────────────────
function guardarContenido() {
  const texto = editorInstance ? editorInstance.getData() : "";

  if (!texto.trim()) {
    Swal.fire({
      title: "Sin contenido",
      text: "Escribe algo antes de guardar.",
      icon: "warning",
      confirmButtonColor: "#9e2820",
    });
    return;
  }

  fetch("/material/guardar", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      sesion_id: SESSION_CONFIG.sesionId,
      texto: texto,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.ok) {
        document.getElementById("seccion-lectura").innerHTML =
          `<div class="text-gray-700">${texto}</div>`;
        Swal.fire({
          title: "Guardado",
          text: "El contenido fue guardado correctamente.",
          icon: "success",
          confirmButtonColor: "#9e2820",
          timer: 1500,
          showConfirmButton: false,
        });
      } else {
        Swal.fire("Error", data.error ?? "No se pudo guardar.", "error");
      }
    })
    .catch(() =>
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error"),
    );
}

// ── Modal evaluación ─────────────────────────────────────
const TIPOS_OPCIONES = [
  { value: "VyF", label: "Verdadero / Falso" },
  { value: "cerrada", label: "Pregunta cerrada" },
  { value: "seleccion", label: "Opción múltiple" },
];

let filasAgregadas = 0;

function abrirModalEvaluacion() {
  document.getElementById("modal-evaluacion").classList.remove("hidden");
  document.getElementById("filas-tipos").innerHTML = "";
  filasAgregadas = 0;
  agregarFilaTipo();
}

function cerrarModalEvaluacion() {
  document.getElementById("modal-evaluacion").classList.add("hidden");
}

function agregarFilaTipo() {
  if (filasAgregadas >= 3) return;
  filasAgregadas++;

  const optsHtml = TIPOS_OPCIONES.map(
    (t) => `<option value="${t.value}">${t.label}</option>`,
  ).join("");

  const fila = document.createElement("div");
  fila.style.cssText =
    "display:grid; grid-template-columns:1fr 100px 28px; gap:10px; align-items:center;";
  fila.innerHTML = `
        <div>
            <label style="font-size:11px; color:var(--texto-secundario); display:block; margin-bottom:4px;">Tipo</label>
            <select name="tipo_fila[]"
                style="width:100%; background:var(--fondo-card); border:none; border-radius:8px; padding:9px 12px; font-size:13px; outline:none; color:var(--texto-principal); cursor:pointer;">
                ${optsHtml}
            </select>
        </div>
        <div>
            <label style="font-size:11px; color:var(--texto-secundario); display:block; margin-bottom:4px;">Cantidad</label>
            <input type="number" name="cantidad_fila[]" min="1" max="50" value="1"
                style="width:100%; background:var(--fondo-card); border:none; border-radius:8px; padding:9px 12px; font-size:13px; outline:none; box-sizing:border-box; color:var(--texto-principal);"
                onfocus="this.style.background='var(--fondo-hover)'"
                onblur="this.style.background='var(--fondo-card)'">
        </div>
        <button type="button" onclick="eliminarFilaTipo(this)"
            style="background:none; border:none; color:var(--texto-sutil); cursor:pointer; font-size:16px; padding:0; margin-top:16px;"
            onmouseover="this.style.color='var(--color-error)'"
            onmouseout="this.style.color='var(--texto-sutil)'">✕</button>
    `;

  document.getElementById("filas-tipos").appendChild(fila);
  document.getElementById("btn-agregar-tipo").style.display =
    filasAgregadas >= 3 ? "none" : "inline";
}

function eliminarFilaTipo(btn) {
  btn.parentElement.remove();
  filasAgregadas--;
  document.getElementById("btn-agregar-tipo").style.display = "inline";
}

function abrirImportarXML() {
  document.getElementById("input-xml").click();
}

function procesarXML(input) {
  if (input.files.length > 0) {
    alert("Archivo seleccionado: " + input.files[0].name);
  }
}

function guardarConfigEvaluacion() {
  const titulo = document.getElementById("eval-titulo").value.trim();
  const descripcion = document.getElementById("eval-descripcion").value.trim();
  const selectores = document.querySelectorAll(
    '#filas-tipos select[name="tipo_fila[]"]',
  );
  const cantidades = document.querySelectorAll(
    '#filas-tipos input[name="cantidad_fila[]"]',
  );

  if (!titulo) {
    Swal.fire({
      title: "Falta el título",
      icon: "warning",
      confirmButtonColor: "var(--granate-600)",
    });
    return;
  }
  if (selectores.length === 0) {
    Swal.fire({
      title: "Agrega al menos un tipo de pregunta",
      icon: "warning",
      confirmButtonColor: "var(--granate-600)",
    });
    return;
  }

  let totalPreguntas = 0;
  const tipos = [];
  const distribucionManual = {};

  for (let i = 0; i < selectores.length; i++) {
    const tipo = selectores[i].value;
    const cantidad = parseInt(cantidades[i].value);

    if (!cantidad || cantidad < 1) {
      Swal.fire({
        title: "Cantidad inválida",
        text: "Todas las cantidades deben ser al menos 1.",
        icon: "warning",
        confirmButtonColor: "var(--granate-600)",
      });
      return;
    }

    tipos.push(tipo);
    distribucionManual[tipo] = (distribucionManual[tipo] || 0) + cantidad;
    totalPreguntas += cantidad;
  }

  const params = new URLSearchParams({
    tutoria_id: SESSION_CONFIG.tutoriaId,
    sesion_id: SESSION_CONFIG.sesionId,
    titulo: titulo,
    descripcion: descripcion,
    total: totalPreguntas,
    tipos: tipos.join(","),
    distribucion: JSON.stringify(distribucionManual),
  });

  window.location.href = "/evaluation/crear?" + params.toString();
}

// ── Subir PDF ─────────────────────────────────────────────
function subirPDF(input) {
  const archivo = input.files[0];
  if (!archivo) return;

  if (archivo.type !== "application/pdf") {
    Swal.fire("Error", "Solo se permiten archivos PDF.", "error");
    input.value = "";
    return;
  }

  const formData = new FormData();
  formData.append("sesion_id", SESSION_CONFIG.sesionId);
  formData.append("archivo_pdf", archivo);

  fetch("/material/subir-pdf", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.ok) {
        Swal.fire({
          title: "PDF subido",
          text: "El archivo se ha guardado correctamente.",
          icon: "success",
          confirmButtonColor: "#9e2820",
          timer: 1500,
          showConfirmButton: false,
        });
        // Recargar para mostrar el PDF
        location.reload();
      } else {
        Swal.fire("Error", data.error ?? "No se pudo subir el PDF.", "error");
      }
    })
    .catch(() =>
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error"),
    );

  input.value = "";
}

// ── Eliminar PDF ──────────────────────────────────────────
function eliminarPDF() {
  Swal.fire({
    title: "Eliminar PDF",
    text: "¿Estás seguro de eliminar este archivo?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#9e2820",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (!result.isConfirmed) return;

    fetch("/material/eliminar-pdf", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ sesion_id: SESSION_CONFIG.sesionId }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.ok) {
          Swal.fire({
            title: "Eliminado",
            text: "El PDF fue eliminado correctamente.",
            icon: "success",
            confirmButtonColor: "#9e2820",
            timer: 1500,
            showConfirmButton: false,
          });
          location.reload();
        } else {
          Swal.fire("Error", data.error ?? "No se pudo eliminar.", "error");
        }
      })
      .catch(() =>
        Swal.fire("Error", "No se pudo conectar con el servidor.", "error"),
      );
  });
}

// ── Guardar link ──────────────────────────────────────────
document.getElementById("link-sesion").addEventListener("blur", function () {
  const link = this.value.trim();

  fetch("/session/guardarLink", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      sesion_id: SESSION_CONFIG.sesionId,
      link: link,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (!data.ok) Swal.fire("Error", "No se pudo guardar el link.", "error");
    })
    .catch(() =>
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error"),
    );
});
