(function () {
    function showAlert(icon, title, text = '') {
        const hasSwal = typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function';

        if (hasSwal) {
            window.Swal.fire({
                icon,
                title,
                text,
                confirmButtonText: 'OK',
            });
            return;
        }

        const message = [title, text].filter(Boolean).join('\n');
        window.alert(message);
    }

    window.TutoriumAlerts = {
        success(title, text = '') {
            showAlert('success', title, text);
        },
        error(title, text = '') {
            showAlert('error', title, text);
        },
        info(title, text = '') {
            showAlert('info', title, text);
        },
        warning(title, text = '') {
            showAlert('warning', title, text);
        },
    };

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-alert-success]').forEach(function (element) {
            window.TutoriumAlerts.success(
                element.getAttribute('data-alert-success'),
                element.getAttribute('data-alert-message') || ''
            );
        });

        document.querySelectorAll('[data-alert-error]').forEach(function (element) {
            window.TutoriumAlerts.error(
                element.getAttribute('data-alert-error'),
                element.getAttribute('data-alert-message') || ''
            );
        });
    });
})();

