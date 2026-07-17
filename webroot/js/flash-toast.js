(function () {
    'use strict';

    function dismiss(el) {
        if (!el || el.classList.contains('flash-toast--leaving')) {
            return;
        }
        el.classList.add('flash-toast--leaving');
        var done = function () {
            el.remove();
        };
        el.addEventListener('animationend', done, { once: true });
        window.setTimeout(done, 400);
    }

    function autoDismissMs(el) {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return null;
        }
        if (el.classList.contains('flash-toast--success')) {
            return 5500;
        }
        if (el.classList.contains('flash-toast--error')) {
            return 9000;
        }
        return 7000;
    }

    function bindToast(el) {
        if (el.dataset.flashToastInit === '1') {
            return;
        }
        el.dataset.flashToastInit = '1';

        var closeBtn = el.querySelector('.flash-toast__close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                dismiss(el);
            });
        }

        var ms = autoDismissMs(el);
        if (ms !== null) {
            window.setTimeout(function () {
                dismiss(el);
            }, ms);
        }
    }

    function scan(root) {
        if (!root) {
            return;
        }
        root.querySelectorAll('[data-flash-toast]').forEach(bindToast);
    }

    document.addEventListener('DOMContentLoaded', function () {
        scan(document.getElementById('flash-toast-region'));
    });
})();
