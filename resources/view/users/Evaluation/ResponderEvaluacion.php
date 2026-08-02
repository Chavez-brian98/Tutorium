<div class="min-h-screen bg-[#f0ebe3]">

<?php include __DIR__ . '/../../layout/sidebar.php'; ?>

<div class="md:ml-64 p-6 md:p-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-dorado">Evaluación</p>
            <h1 class="text-3xl font-bold text-granate mt-2"><?= htmlspecialchars($evaluacion['titulo'] ?? 'Evaluación') ?></h1>
            <?php if (!empty($evaluacion['descripcion'])): ?>
                <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($evaluacion['descripcion']) ?></p>
            <?php endif; ?>
        </div>
        <a href="javascript:history.back()"
           class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg transition-colors flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if ($yaRespondida): ?>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
            <div>
                <p class="font-semibold text-emerald-800 text-sm">Evaluación ya respondida</p>
                <p class="text-emerald-600 text-xs">Ya has enviado tus respuestas para esta evaluación.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($preguntasEvaluacion)): ?>
        <div class="text-center py-16 text-gray-400">
            <i class="bi bi-question-circle text-5xl block mb-3"></i>
            <p class="text-sm">Esta evaluación no tiene preguntas configuradas.</p>
        </div>
    <?php else: ?>
        <form id="form-responder-evaluacion" data-evaluacion-id="<?= (int) $evaluacion['id'] ?>">
            <div class="space-y-5">
                <?php foreach ($preguntasEvaluacion as $i => $pq): ?>
                    <div class="bg-white rounded-2xl border border-gray-200 p-6 <?= $yaRespondida ? 'bg-gray-50' : 'hover:border-gray-300' ?> transition-colors shadow-sm">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="w-8 h-8 rounded-lg bg-[#5c1313]/10 text-[#5c1313] flex items-center justify-center text-sm font-bold flex-shrink-0"><?= $i + 1 ?></span>
                            <p class="text-base font-medium text-gray-800"><?= htmlspecialchars($pq['enunciado']) ?></p>
                        </div>

                        <?php if ($pq['tipo'] === 'respuesta_corta'): ?>
                            <textarea name="respuesta_<?= $pq['pregunta_id'] ?>"
                                      data-pregunta-id="<?= $pq['pregunta_id'] ?>"
                                      rows="3"
                                      placeholder="Escribe tu respuesta..."
                                      class="w-full mt-1 px-4 py-3 text-sm border border-gray-200 rounded-xl outline-none focus:border-[#9e2820] focus:ring-2 focus:ring-[#9e2820]/10 transition-all resize-none"
                                      <?= $yaRespondida ? 'disabled' : '' ?>></textarea>

                        <?php elseif ($pq['tipo'] === 'VyF'): ?>
                            <div class="mt-2 flex gap-4">
                                <?php foreach ($pq['opciones'] as $op): ?>
                                    <label class="flex items-center gap-2 px-5 py-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#9e2820] has-[:checked]:bg-[#9e2820]/5 transition-all <?= $yaRespondida ? 'pointer-events-none opacity-70' : '' ?>">
                                        <input type="radio"
                                               name="respuesta_<?= $pq['pregunta_id'] ?>"
                                               value="<?= (int) $op['id'] ?>"
                                               data-pregunta-id="<?= $pq['pregunta_id'] ?>"
                                               class="accent-[#9e2820]"
                                               <?= $yaRespondida ? 'disabled' : '' ?>>
                                        <span class="text-sm font-medium"><?= htmlspecialchars($op['texto']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($pq['tipo'] === 'opcion_multiple'): ?>
                            <div class="mt-2 space-y-2">
                                <?php foreach ($pq['opciones'] as $op): ?>
                                    <label class="flex items-center gap-3 px-4 py-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#9e2820] has-[:checked]:bg-[#9e2820]/5 transition-all <?= $yaRespondida ? 'pointer-events-none opacity-70' : '' ?>">
                                        <input type="radio"
                                               name="respuesta_<?= $pq['pregunta_id'] ?>"
                                               value="<?= (int) $op['id'] ?>"
                                               data-pregunta-id="<?= $pq['pregunta_id'] ?>"
                                               class="accent-[#9e2820]"
                                               <?= $yaRespondida ? 'disabled' : '' ?>>
                                        <span class="text-sm"><?= htmlspecialchars($op['texto']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!$yaRespondida && $rol === 'alumno'): ?>
                <div class="flex justify-end gap-3 mt-6">
                    <a href="javascript:history.back()"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 text-sm font-medium bg-[#9e2820] text-white rounded-xl hover:bg-[#7a2019] transition-colors flex items-center gap-2 shadow-sm">
                        <i class="bi bi-send text-sm"></i>
                        Enviar respuestas
                    </button>
                </div>
            <?php endif; ?>
        </form>

        <?php if ($yaRespondida): ?>
            <div class="flex justify-end mt-6">
                <a href="javascript:history.back()"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    Volver
                </a>
                <a href="/evaluaciones"
                   class="ml-3 px-6 py-2.5 text-sm font-medium bg-[#9e2820] text-white rounded-xl hover:bg-[#7a2019] transition-colors flex items-center gap-2">
                    <i class="bi bi-arrow-right text-sm"></i>
                    Revisar evaluación
                </a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
</div>

<script>
document.getElementById('form-responder-evaluacion')?.addEventListener('submit', function (e) {
    e.preventDefault();

    const evaluacionId = this.dataset.evaluacionId;
    const respuestas = [];
    const inputs = this.querySelectorAll('[data-pregunta-id]');

    inputs.forEach(input => {
        const preguntaId = input.dataset.preguntaId;
        let valor = null;

        if (input.type === 'radio' && input.checked) {
            valor = { opcion_id: input.value };
        } else if (input.tagName === 'TEXTAREA') {
            const texto = input.value.trim();
            if (texto) {
                valor = { respuesta_texto: texto };
            }
        }

        if (valor) {
            const existente = respuestas.find(r => r.pregunta_id === preguntaId);
            if (existente) {
                Object.assign(existente, valor);
            } else {
                respuestas.push({ pregunta_id: preguntaId, ...valor });
            }
        }
    });

    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';

    fetch('/api/evaluacion/responder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ evaluacion_id: evaluacionId, respuestas: JSON.stringify(respuestas) })
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            const container = document.querySelector('.md\\:ml-64');
            container.innerHTML = `
                <div class="flex items-center justify-center min-h-[60vh]">
                    <div class="bg-white rounded-2xl max-w-md w-full mx-4 shadow-xl overflow-hidden text-center p-10">
                        <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-check-lg text-emerald-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Respuestas enviadas</h3>
                        <p class="text-sm text-gray-500 mb-6">Tus respuestas han sido guardadas correctamente.</p>
                        <a href="/evaluaciones"
                           class="px-6 py-2.5 text-sm font-medium bg-[#9e2820] text-white rounded-xl hover:bg-[#7a2019] transition-colors inline-block">
                            Ir a mis evaluaciones
                        </a>
                    </div>
                </div>
            `;
        } else {
            alert('Error al guardar respuestas: ' + (data.error || 'Intenta de nuevo.'));
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send text-sm"></i> Enviar respuestas';
        }
    })
    .catch(err => {
        alert('Error de conexión. Intenta de nuevo.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send text-sm"></i> Enviar respuestas';
    });
});
</script>
