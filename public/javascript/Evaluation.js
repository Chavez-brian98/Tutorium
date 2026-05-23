const ETIQUETAS = {
  VyF: "Verdadero / Falso",
  cerrada: "Pregunta cerrada",
  seleccion: "Opción múltiple",
};

let contadorPreguntas = 0;
let contadorOpciones = {};

document.addEventListener("DOMContentLoaded", function () {
  // Generar preguntas respetando la distribución
  for (const [tipo, cantidad] of Object.entries(DISTRIBUCION)) {
    for (let i = 0; i < cantidad; i++) {
      agregarPregunta(tipo);
    }
  }
});

// El botón "Agregar pregunta" deja elegir el tipo libremente
function agregarPreguntaManual() {
  agregarPregunta(TIPOS_DISPONIBLES[0]);
}

function agregarPregunta(tipoInicial) {
  contadorPreguntas++;
  const n = contadorPreguntas;
  contadorOpciones[n] = 0;

  const optsHtml = TIPOS_DISPONIBLES.map(
    (t) =>
      `<option value="${t}" ${t === tipoInicial ? "selected" : ""}>${ETIQUETAS[t]}</option>`,
  ).join("");

  const div = document.createElement("div");
  div.id = `pregunta-${n}`;
  div.style.cssText = `
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 14px;
        border-left: 4px solid var(--granate-500);
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    `;
  div.innerHTML = `
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
            <span style="font-size:12px; font-weight:700; color:var(--granate-500); text-transform:uppercase; letter-spacing:.05em;">
                Pregunta ${n}
            </span>
            <button type="button" onclick="eliminarPregunta(${n})"
                    style="color:var(--texto-sutil); background:none; border:none; font-size:1.1rem; cursor:pointer;"
                    onmouseover="this.style.color='var(--color-error)'"
                    onmouseout="this.style.color='var(--texto-sutil)'">✕</button>
        </div>

        <textarea name="enunciado[${n}]" rows="2"
            placeholder="Escribe el enunciado..."
            style="width:100%; border:1.5px solid #e0dbd3; border-radius:8px; padding:8px 12px; font-size:14px; outline:none; resize:none; box-sizing:border-box; font-family:'Nunito',sans-serif; margin-bottom:12px;"
            onfocus="this.style.borderColor='var(--granate-500)'"
            onblur="this.style.borderColor='#e0dbd3'"></textarea>

        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <label style="font-size:12px; color:var(--texto-secundario);">Tipo:</label>
            <select name="tipo[${n}]" onchange="cambiarTipo(${n}, this.value)"
                style="border:1.5px solid #e0dbd3; border-radius:8px; padding:5px 10px; font-size:13px; outline:none; color:var(--texto-principal); background:white;">
                ${optsHtml}
            </select>
        </div>

        <div id="opciones-${n}"></div>
    `;

  document.getElementById("preguntas-container").appendChild(div);
  cambiarTipo(n, tipoInicial);
}

function cambiarTipo(n, tipo) {
  const contenedor = document.getElementById(`opciones-${n}`);
  contadorOpciones[n] = 0;

  if (tipo === "VyF") {
    contenedor.innerHTML = `
            <div style="display:flex; gap:24px; margin-top:4px;">
                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                    <input type="radio" name="correcta_vyf[${n}]" value="verdadero" style="accent-color:var(--granate-600);">
                    Verdadero
                </label>
                <label style="display:flex; align-items:center; gap:6px; font-size:13px; cursor:pointer;">
                    <input type="radio" name="correcta_vyf[${n}]" value="falso" style="accent-color:var(--granate-600);">
                    Falso
                </label>
            </div>
            <p style="font-size:11px; color:var(--texto-sutil); margin-top:4px;">Marca la respuesta correcta.</p>
        `;
  } else if (tipo === "cerrada") {
    contenedor.innerHTML = `
            <p style="font-size:12px; color:var(--texto-sutil); margin-top:4px;">
                El alumno responderá con texto libre.
            </p>
        `;
  } else if (tipo === "seleccion") {
    contenedor.innerHTML = `
            <div id="opts-${n}" style="display:flex; flex-direction:column; gap:8px; margin-bottom:8px;"></div>
            <button type="button" onclick="agregarOpcion(${n})"
                style="font-size:13px; color:var(--granate-600); background:none; border:none; cursor:pointer; padding:0; font-family:'Nunito',sans-serif;">
                + Agregar opción
            </button>
            <p style="font-size:11px; color:var(--texto-sutil); margin-top:4px;">Marca el radio de la opción correcta.</p>
        `;
    agregarOpcion(n);
    agregarOpcion(n);
  }
}

function agregarOpcion(n) {
  const idx = contadorOpciones[n]++;
  const container = document.getElementById(`opts-${n}`);
  const div = document.createElement("div");
  div.style.cssText = "display:flex; align-items:center; gap:8px;";
  div.innerHTML = `
        <input type="radio" name="correcta_sel[${n}]" value="${idx}"
               style="accent-color:var(--granate-600);" title="Marcar como correcta">
        <input type="text" name="opcion[${n}][]" placeholder="Opción ${idx + 1}"
               style="flex:1; border:1.5px solid #e0dbd3; border-radius:8px; padding:6px 10px; font-size:13px; outline:none; font-family:'Nunito',sans-serif;"
               onfocus="this.style.borderColor='var(--granate-500)'"
               onblur="this.style.borderColor='#e0dbd3'">
        <button type="button" onclick="this.parentElement.remove()"
                style="color:var(--texto-sutil); background:none; border:none; cursor:pointer; font-size:1rem;"
                onmouseover="this.style.color='var(--color-error)'"
                onmouseout="this.style.color='var(--texto-sutil)'">✕</button>
    `;
  container.appendChild(div);
}

function eliminarPregunta(n) {
  const el = document.getElementById(`pregunta-${n}`);
  if (el) el.remove();
}

function guardarEvaluacion() {
  const enunciados = document.querySelectorAll('textarea[name^="enunciado"]');
  for (const e of enunciados) {
    if (!e.value.trim()) {
      Swal.fire({
        title: "Falta información",
        text: "Todos los enunciados deben estar completos.",
        icon: "warning",
        confirmButtonColor: "var(--granate-600)",
      });
      e.focus();
      return;
    }
  }
  // Si todo está bien, hace submit real al controller
  document.getElementById("form-evaluacion").submit();
}

Swal.fire({
  title: "¿Guardar evaluación?",
  text: "Se guardará con todas las preguntas ingresadas.",
  icon: "question",
  confirmButtonText: "Sí, guardar",
  cancelButtonText: "Revisar",
  showCancelButton: true,
  confirmButtonColor: "var(--granate-600)",
  cancelButtonColor: "var(--texto-secundario)",
}).then((result) => {
  if (result.isConfirmed) {
    // fetch('/evaluation/guardar', { method:'POST', body: new FormData(document.getElementById('form-evaluacion')) })
    Swal.fire({
      title: "Guardado",
      text: "La evaluación fue creada correctamente.",
      icon: "success",
      confirmButtonColor: "var(--granate-600)",
    }).then(() => (window.location.href = "/session"));
  }
});
