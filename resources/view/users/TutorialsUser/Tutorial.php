<?php
include __DIR__ . '/../../layout/sidebar.php';
// Variables que llegan desde TutorialController::index():
// $tutorias
?>

<div class="min-h-screen md:ml-64 p-6 md:p-8">
    <div class="max-w-6xl mx-auto">
        
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 font-mono tracking-wide">
            Tutorías asignadas
        </h1>

        <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm">
            
            <?php if (!empty($tutorias)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <?php foreach ($tutorias as $index => $tutoria): 
                        // Alternamos colores de tarjetas para simular tu diseño (algunas rosadas, la mayoría azuladas)
                        $esRosado = ($index === 2); // El tercer elemento será rosado claro, el resto celeste
                        $bgClass = $esRosado ? 'bg-red-50/70 border-red-200 hover:bg-red-100/50' : 'bg-blue-50/50 border-blue-200 hover:bg-blue-100/50';
                        $textClass = $esRosado ? 'text-red-900' : 'text-blue-900';
                    ?>
                        
                        <a href="/tutorias/<?= $tutoria['id'] ?>/sesiones?numero=1" 
                            class="group block <?= $bgClass ?> border-2 rounded-2xl p-8 text-center transition-all duration-200 transform hover:-translate-y-1 hover:shadow-md cursor-pointer relative overflow-hidden">
                            
                            <span class="block text-base font-medium <?= $textClass ?> lowercase font-mono">
                                <?= htmlspecialchars($tutoria['materia_nombre']) ?>
                            </span>

                            <span class="block text-xs text-gray-400 mt-2 font-sans opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                Ver sesiones asignadas →
                            </span>
                            
                        </a>

                    <?php endforeach; ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <p class="text-gray-400 text-sm font-mono">No tienes tutorías asignadas en este momento.</p>
                </div>
            <?php endif; ?>

            <div class="flex justify-end mt-6 text-gray-400">
                <span class="text-xl font-bold font-mono select-none">&gt;</span>
            </div>

        </div>

    </div>
</div>