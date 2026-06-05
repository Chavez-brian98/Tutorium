/*
 * Archivo de alertas globales para las vistas.
 * Esta versión usa SweetAlert2 cargado desde CDN en layout/base.php.
 */

window.appAlerts = {
    success(title = 'Operación exitosa', text = 'La acción se completó correctamente') {
        return Swal.fire({
            icon: 'success',
            title,
            text,
            confirmButtonColor: '#781C1C',
        });
    },

    error(title = 'Error', text = 'Ocurrió un problema al ejecutar la acción') {
        return Swal.fire({
            icon: 'error',
            title,
            text,
            confirmButtonColor: '#781C1C',
        });
    },

    confirm(options = {}) {
        return Swal.fire({
            title: options.title || '¿Estás seguro?',
            text: options.text || 'Esta acción no se puede deshacer.',
            icon: options.icon || 'warning',
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || 'Sí, continuar',
            cancelButtonText: options.cancelButtonText || 'Cancelar',
            confirmButtonColor: options.confirmButtonColor || '#9e2a2a',
            cancelButtonColor: options.cancelButtonColor || '#6b7280',
        });
    }
};

