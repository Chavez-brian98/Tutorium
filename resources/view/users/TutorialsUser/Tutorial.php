<?php
include __DIR__ . '/../../layout/sidebar.php';
// Variables que llegan desde TutorialController::index():
// $tutorias  — cada una con: id, fecha, hora_inicio, hora_fin, materia_nombre
?>

<div class="min-h-screen md:ml-64 p-6 md:p-8">
    <div class="max-w-6xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                    <span class="text-[#9e2820]">◆</span> Tutorías asignadas
                </h1>
                <p class="text-sm text-gray-400 mt-1.5 flex items-center gap-1.5">
                    <i class="fas fa-layer-group text-gray-300 text-xs"></i>
                    <?= count($tutorias) ?> tutoría<?= count($tutorias) !== 1 ? 's' : '' ?> activa<?= count($tutorias) !== 1 ? 's' : '' ?>
                </p>
            </div>
            <div class="flex items-center gap-3 text-sm bg-white/70 backdrop-blur-sm border border-gray-200/60 rounded-xl px-4 py-2.5 shadow-sm">
                <i class="fas fa-calendar-alt text-[#9e2820]"></i>
                <span class="font-medium text-gray-600"><?= date('d/m/Y') ?></span>
                <span class="w-px h-4 bg-gray-200"></span>
                <span class="text-gray-400 capitalize"><?= date('l') ?></span>
            </div>
        </div>

        <?php if (!empty($tutorias)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php foreach ($tutorias as $tutoria):
                    $fecha = new DateTime($tutoria['fecha']);
                    $hoy = new DateTime();
                    $diff = $hoy->diff($fecha);
                    $esPasada = $fecha < $hoy;
                    $esHoy = $fecha->format('Y-m-d') === $hoy->format('Y-m-d');

                    if ($esHoy) {
                        $badge = ['text' => 'Hoy', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'];
                        $accentColor = '#059669';
                        $accentBg = 'bg-emerald-50';
                    } elseif ($esPasada) {
                        $badge = ['text' => 'Finalizada', 'class' => 'bg-gray-50 text-gray-500 border-gray-200'];
                        $accentColor = '#9ca3af';
                        $accentBg = 'bg-gray-50';
                    } else {
                        $badge = ['text' => 'Próxima', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'];
                        $accentColor = '#d97706';
                        $accentBg = 'bg-amber-50';
                    }

                    $bgCard = $esPasada
                        ? 'bg-white border-gray-200/80 hover:border-gray-300'
                        : 'bg-white border-gray-200/80 hover:border-[#9e2820]/20';
                ?>

                    <a href="/tutorias/<?= $tutoria['id'] ?>/sesiones?numero=1"
                       class="group block <?= $bgCard ?> border-2 rounded-2xl transition-all duration-300 transform hover:-translate-y-1.5 hover:shadow-xl hover:shadow-black/5 cursor-pointer relative overflow-hidden">

                        <div class="h-1.5 w-full bg-gradient-to-r from-[#9e2820] to-[#5c1313]"></div>

                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 rounded-xl <?= $esPasada ? 'bg-gray-100' : 'bg-gradient-to-br from-[#9e2820]/10 to-[#5c1313]/10' ?> flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                                    <i class="fas fa-graduation-cap <?= $esPasada ? 'text-gray-400' : 'text-[#9e2820]' ?> text-lg"></i>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border <?= $badge['class'] ?> shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full <?= str_replace(['bg-', 'text-'], ['bg-', ''], explode(' ', $badge['class'])[0]) ?>"></span>
                                    <?= $badge['text'] ?>
                                </span>
                            </div>

                            <h2 class="text-lg font-bold text-gray-900 group-hover:text-[#9e2820] transition-colors duration-200 mb-4 leading-snug">
                                <?= htmlspecialchars($tutoria['materia_nombre']) ?>
                            </h2>

                            <div class="space-y-3 text-sm">
                                <div class="flex items-center gap-3 text-gray-500 group-hover:text-gray-700 transition-colors duration-200">
                                    <span class="w-8 h-8 rounded-lg <?= $accentBg ?> flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar-day text-xs" style="color: <?= $accentColor ?>"></i>
                                    </span>
                                    <span class="font-medium"><?= $fecha->format('d/m/Y') ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-500 group-hover:text-gray-700 transition-colors duration-200">
                                    <span class="w-8 h-8 rounded-lg <?= $accentBg ?> flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-clock text-xs" style="color: <?= $accentColor ?>"></i>
                                    </span>
                                    <span class="font-medium"><?= htmlspecialchars($tutoria['hora_inicio'] ?? '—') ?> – <?= htmlspecialchars($tutoria['hora_fin'] ?? '—') ?></span>
                                </div>
                            </div>

                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between group-hover:border-[#9e2820]/10 transition-colors duration-200">
                                <span class="text-sm font-semibold text-gray-400 group-hover:text-[#9e2820] transition-colors duration-200 flex items-center gap-2">
                                    Ver sesiones
                                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1.5 transition-transform duration-200"></i>
                                </span>
                                <span class="text-xs text-gray-300 group-hover:text-[#9e2820]/40 transition-colors duration-200">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </div>
                        </div>

                        <div class="absolute -bottom-6 -right-6 w-24 h-24 rounded-full <?= $esPasada ? 'bg-gray-50' : 'bg-[#9e2820]/5' ?> opacity-0 group-hover:opacity-100 transition-all duration-500 transform scale-0 group-hover:scale-100"></div>
                    </a>

                <?php endforeach; ?>

            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-16 text-center">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center mx-auto mb-5 shadow-inner">
                    <i class="fas fa-graduation-cap text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Sin tutorías asignadas</h3>
                <p class="text-gray-400 text-sm">No tienes tutorías asignadas en este momento.</p>
                <div class="mt-6 w-16 h-1 bg-gray-100 rounded-full mx-auto"></div>
            </div>
        <?php endif; ?>

    </div>
</div>