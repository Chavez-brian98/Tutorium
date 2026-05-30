<div class="min-h-screen" style="background-color: #f0ebe3;">

    <?php include __DIR__ . '/../layout/sidebar.php'; ?>

    <div class="md:ml-64 p-6 md:p-8">

        <div class="mb-8">
            <h2 class="text-3xl font-bold" style="color: #5c1313;">
                ¡Bienvenido, <?= htmlspecialchars($usuario['nombre'] ?? 'Administrador'); ?>!
            </h2>
            <p class="text-lg mt-1" style="color: #c8922a;">Resumen general del Sistema de Tutorías UNICAES</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

            <div class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1"
                 style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background-color: rgba(46, 125, 82, 0.1);">
                        <i class="fas fa-graduation-cap text-xl" style="color: #2e7d52;"></i>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-lg"
                          style="background-color: rgba(46, 125, 82, 0.1); color: #2e7d52;">
                        Histórico
                    </span>
                </div>
                <h3 class="text-sm font-semibold mb-1" style="color: #5a5a5a;">Total Tutorías</h3>
                <p class="text-3xl font-bold" style="color: #5c1313;"><?= $stats['total_tutorias'] ?? '0' ?></p>
                <p class="text-xs mt-2" style="color: #9a9a9a;">Registradas en el sistema</p>
            </div>

            <div class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1"
                 style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background-color: rgba(29, 95, 165, 0.1);">
                        <i class="fas fa-calendar-alt text-xl" style="color: #1d5fa5;"></i>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-lg"
                          style="background-color: rgba(200, 146, 42, 0.1); color: #a5721a;">
                        <?= $stats['sesiones_este_mes'] ?? '0' ?> este mes
                    </span>
                </div>
                <h3 class="text-sm font-semibold mb-1" style="color: #5a5a5a;">Tutorías Activas</h3>
                <p class="text-3xl font-bold" style="color: #5c1313;"><?= $stats['tutorias_activas'] ?? '0' ?></p>
                <p class="text-xs mt-2">
                    <span style="color: #c8922a; font-weight: 600;"><?= $stats['tutorias_pendientes'] ?? '0' ?> pendientes de asignación</span>
                </p>
            </div>

            <div class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1"
                 style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background-color: rgba(123, 28, 28, 0.1);">
                        <i class="fas fa-book text-xl" style="color: #9e2a2a;"></i>
                    </div>
                </div>
                <h3 class="text-sm font-semibold mb-1" style="color: #5a5a5a;">Materias Activas</h3>
                <p class="text-3xl font-bold" style="color: #5c1313;"><?= $stats['materias_activas'] ?? '0' ?></p>
                <p class="text-xs mt-2" style="color: #9a9a9a;">
                    de <?= $stats['total_materias'] ?? '0' ?> materias en catálogo
                </p>
            </div>

            <div class="rounded-2xl p-6 transition-all hover:shadow-lg hover:-translate-y-1"
                 style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         style="background-color: rgba(200, 146, 42, 0.1);">
                        <i class="fas fa-users text-xl" style="color: #c8922a;"></i>
                    </div>
                </div>
                <h3 class="text-sm font-semibold mb-1" style="color: #5a5a5a;">Total Usuarios</h3>
                <p class="text-3xl font-bold" style="color: #5c1313;"><?= $stats['total_usuarios'] ?? '0' ?></p>
                <p class="text-xs mt-2" style="color: #5a5a5a;">
                    <span class="font-bold text-slate-700"><?= $stats['total_tutores'] ?? '0' ?></span> Tutores |
                    <span class="font-bold text-slate-700"><?= $stats['total_alumnos'] ?? '0' ?></span> Alumnos
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 rounded-2xl p-6"
                 style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="lg:col-span-2 rounded-2xl p-6"
                     style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold" style="color: #5c1313;">Demanda de Tutorías por Mes</h3>
                        <select class="px-3 py-1.5 rounded-lg text-sm border-0 focus:ring-2 focus:ring-c8922a"
                                style="background-color: #ede8e0; color: #5c1313;">
                            <option>2026</option>
                            <option>2025</option>
                        </select>
                    </div>

                    <div class="h-64 relative">
                        <canvas id="tutoriasChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl p-6" style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold" style="color: #5c1313;">Próximas Sesiones</h3>
                    <a href="/tutorias/admin" class="text-sm font-semibold transition-colors" style="color: #c8922a;">Ver
                        todas</a>
                </div>
                <div class="space-y-4">
                    <?php if (!empty($proximas_sesiones)): ?>
                        <?php foreach ($proximas_sesiones as $sesion): ?>
                            <div class="flex items-center justify-between p-3 rounded-xl transition-colors"
                                 style="background-color: rgba(123, 28, 28, 0.05);">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                         style="background-color: rgba(123, 28, 28, 0.1);">
                                        <i class="fas fa-bookmark text-sm" style="color: #9e2a2a;"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold" style="color: #5c1313;">Sesión
                                            #<?= htmlspecialchars($sesion['numero']) ?></p>
                                        <p class="text-xs"
                                           style="color: #5a5a5a;"><?= htmlspecialchars($sesion['alumno_nombre']) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold"
                                       style="color: #5c1313;"><?= htmlspecialchars($sesion['hora_inicio']) ?></p>
                                    <span class="text-xs px-2 py-0.5 rounded-lg font-semibold"
                                          style="background-color: rgba(200, 146, 42, 0.1); color: #a5721a;"><?= htmlspecialchars($sesion['fecha']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="flex items-center justify-between p-3 rounded-xl"
                             style="background-color: rgba(123, 28, 28, 0.05);">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                     style="background-color: rgba(123, 28, 28, 0.1);"><i
                                            class="fas fa-bookmark text-sm" style="color: #9e2a2a;"></i></div>
                                <div>
                                    <p class="text-sm font-semibold" style="color: #5c1313;">Sesión #1 - Programación
                                        I</p>
                                    <p class="text-xs" style="color: #5a5a5a;">Kevin Alvarado (Alumno)</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold" style="color: #5c1313;">14:00</p>
                                <span class="text-xs px-2 py-0.5 rounded-lg font-semibold"
                                      style="background-color: rgba(46, 125, 82, 0.1); color: #2e7d52;">Hoy</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="rounded-2xl p-6" style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <h3 class="text-lg font-bold mb-6" style="color: #5c1313;">
                    <i class="fas fa-trophy mr-2" style="color: #c8922a;"></i>Tutores con Mayor Rendimiento
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl"
                         style="background-color: rgba(123, 28, 28, 0.05);">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm"
                                 style="background-color: rgba(200, 146, 42, 0.2); color: #a5721a;">#1
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: #5c1313;">Ing. Laura Fernández</p>
                                <p class="text-xs" style="color: #5a5a5a;">Facultad de Ingeniería</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800">12 Tutorías Completadas</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl"
                         style="background-color: rgba(123, 28, 28, 0.05);">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm"
                                 style="background-color: rgba(90, 90, 90, 0.1); color: #5a5a5a;">#2
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: #5c1313;">Lic. Roberto Díaz</p>
                                <p class="text-xs" style="color: #5a5a5a;">Facultad de Ciencias y Humanidades</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800">9 Tutorías Completadas</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl p-6" style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
                <h3 class="text-lg font-bold mb-6" style="color: #5c1313;">
                    <i class="fas fa-fire mr-2" style="color: #b91c1c;"></i>Materias con Mayor Demanda
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-xl"
                         style="background-color: rgba(123, 28, 28, 0.05);">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: linear-gradient(135deg, #fdeaea 0%, #f5c0c0 100%);">
                                <i class="fas fa-code text-sm" style="color: #9e2a2a;"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: #5c1313;">Programación Orientada a
                                    Objetos</p>
                                <p class="text-xs" style="color: #5a5a5a;">Código: POO-I</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-slate-700">24 Alumnos inscritos</span>
                    </div>

                    <div class="flex items-center justify-between p-3 rounded-xl"
                         style="background-color: rgba(123, 28, 28, 0.05);">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                                 style="background: linear-gradient(135deg, #fdeaea 0%, #f5c0c0 100%);">
                                <i class="fas fa-calculator text-sm" style="color: #9e2a2a;"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold" style="color: #5c1313;">Matemática I</p>
                                <p class="text-xs" style="color: #5a5a5a;">Código: MAT-I</p>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-slate-700">18 Alumnos inscritos</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-6" style="background-color: #f5f0e8; border: 1px solid rgba(123, 28, 28, 0.1);">
            <h3 class="text-lg font-bold mb-6" style="color: #5c1313;">
                <i class="fas fa-chart-bar mr-2" style="color: #9e2a2a;"></i>Estado de Solicitudes de Tutoría
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-xl p-4 text-center transition-all hover:shadow-md"
                     style="background-color: rgba(200, 146, 42, 0.1); border: 1px solid rgba(200, 146, 42, 0.2);">
                    <i class="fas fa-clock text-2xl mb-2" style="color: #c8922a;"></i>
                    <p class="text-2xl font-bold"
                       style="color: #5c1313;"><?= $stats['tutorias_pendientes'] ?? '0' ?></p>
                    <p class="text-xs font-medium mt-1" style="color: #5a5a5a;">Pendientes de Aprobación</p>
                </div>
                <div class="rounded-xl p-4 text-center transition-all hover:shadow-md"
                     style="background-color: rgba(46, 125, 82, 0.1); border: 1px solid rgba(46, 125, 82, 0.2);">
                    <i class="fas fa-check-circle text-2xl mb-2" style="color: #2e7d52;"></i>
                    <p class="text-2xl font-bold"
                       style="color: #5c1313;"><?= $stats['tutorias_completadas'] ?? '0' ?></p>
                    <p class="text-xs font-medium mt-1" style="color: #5a5a5a;">Completadas Exitosamente</p>
                </div>
                <div class="rounded-xl p-4 text-center transition-all hover:shadow-md"
                     style="background-color: rgba(185, 28, 28, 0.1); border: 1px solid rgba(185, 28, 28, 0.2);">
                    <i class="fas fa-times-circle text-2xl mb-2" style="color: #b91c1c;"></i>
                    <p class="text-2xl font-bold"
                       style="color: #5c1313;"><?= $stats['tutorias_canceladas'] ?? '0' ?></p>
                    <p class="text-xs font-medium mt-1" style="color: #5a5a5a;">Canceladas o Rechazadas</p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Tu script asíncrono perfectamente adaptado al endpoint correcto del sistema
    setInterval(() => {
        fetch('/api/admin/stats')
            .then(response => response.json())
            .then(data => {
                console.log('Métricas de tutorías actualizadas en tiempo real:', data);
            })
            .catch(error => console.error('Error al actualizar métricas:', error));
    }, 30000);

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('tutoriasChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [{
                    label: 'Tutorías Solicitadas',
                    data: [12, 45, 68, 80, 95, 30, 15, 55, 88, 110, 130, 40],
                    borderColor: '#5c1313',
                    backgroundColor: 'rgba(200, 146, 42, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#c8922a',
                    pointRadius: 4,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {display: false}
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {color: 'rgba(123, 28, 28, 0.05)'},
                        ticks: {color: '#5a5a5a'}
                    },
                    x: {
                        grid: {display: false},
                        ticks: {color: '#5a5a5a'}
                    }
                }
            }
        });
    });
</script>