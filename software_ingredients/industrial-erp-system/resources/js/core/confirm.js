const DELETE_DEFAULTS = {
    title: 'Delete this record?',
    text: 'This will permanently delete the data.',
    type: 'danger',
    confirmText: 'Yes, delete',
    cancelText: 'No, cancel',
};

const RESTORE_DEFAULTS = {
    title: 'Restore the system?',
    text: 'This will replace your active database, files, and configuration with the backup contents.',
    type: 'warning',
    confirmText: 'Yes, restore',
    cancelText: 'No, cancel',
};

const CANCEL_LEAVE_DEFAULTS = {
    title: 'Leave this form?',
    text: 'If you cancel now, your changes will not be saved.',
    type: 'info',
    confirmText: 'Yes, leave',
    cancelText: 'No, keep editing',
};

function readDataset(el, key, fallback = '') {
    const value = el?.dataset?.[key];
    return value !== undefined && value !== '' ? value : fallback;
}

function isDangerType(type) {
    return type === 'danger';
}

function iconForType(type) {
    if (isDangerType(type)) {
        return 'warning';
    }

    return type || 'question';
}

function themedSwalOptions(overrides = {}) {
    return {
        buttonsStyling: false,
        showCancelButton: true,
        reverseButtons: true,
        focusCancel: true,
        heightAuto: true,
        customClass: {
            container: 'neci-swal-container',
            popup: 'neci-swal-popup',
            title: 'neci-swal-title',
            htmlContainer: 'neci-swal-text',
            icon: 'neci-swal-icon',
            actions: 'neci-swal-actions',
            confirmButton: 'btn btn-primary neci-swal-btn neci-swal-btn-confirm',
            cancelButton: 'btn btn-light neci-swal-btn neci-swal-btn-cancel',
        },
        ...overrides,
    };
}

function buildConfirmClasses(type) {
    const danger = isDangerType(type);

    return {
        icon: danger ? 'neci-swal-icon neci-swal-icon--danger' : 'neci-swal-icon',
        confirmButton: danger
            ? 'btn btn-danger neci-swal-btn neci-swal-btn-confirm neci-swal-btn-confirm-danger'
            : 'btn btn-primary neci-swal-btn neci-swal-btn-confirm',
    };
}

export function neciConfirm(options = {}) {
    const type = options.type || 'warning';
    const confirmClasses = buildConfirmClasses(type);

    if (typeof Swal === 'undefined') {
        const message = [options.title, options.text].filter(Boolean).join('\n\n');
        return Promise.resolve({ isConfirmed: window.confirm(message) });
    }

    return Swal.fire(themedSwalOptions({
        title: options.title || 'Are you sure?',
        text: options.text || '',
        icon: iconForType(type),
        confirmButtonText: options.confirmText || 'Yes',
        cancelButtonText: options.cancelText || 'No',
        customClass: {
            container: 'neci-swal-container',
            popup: 'neci-swal-popup',
            title: 'neci-swal-title',
            htmlContainer: 'neci-swal-text',
            icon: confirmClasses.icon,
            actions: 'neci-swal-actions',
            confirmButton: confirmClasses.confirmButton,
            cancelButton: 'btn btn-light neci-swal-btn neci-swal-btn-cancel',
        },
    }));
}

function mergeCustomClasses(base = {}, extra = {}) {
    const merged = { ...base };

    Object.keys(extra).forEach((key) => {
        merged[key] = [merged[key], extra[key]].filter(Boolean).join(' ');
    });

    return merged;
}

export function neciFireAlert(config = {}) {
    if (typeof Swal === 'undefined') {
        return null;
    }

    const icon = config.icon || 'success';
    const isToast = Boolean(config.toast);

    if (isToast) {
        const toastClasses = {
            popup: `neci-swal-toast neci-swal-toast--${icon}`,
            icon: `neci-swal-toast-icon neci-swal-toast-icon--${icon}`,
            title: 'neci-swal-toast-title',
            closeButton: 'neci-swal-toast-close',
            timerProgressBar: `neci-swal-toast-progress neci-swal-toast-progress--${icon}`,
        };

        return Swal.fire({
            buttonsStyling: false,
            timer: config.timer ?? 4000,
            timerProgressBar: config.timerProgressBar ?? true,
            showConfirmButton: false,
            ...config,
            customClass: mergeCustomClasses(toastClasses, config.customClass || {}),
            showClass: {
                popup: 'neci-swal-toast-animate-in',
                ...(config.showClass || {}),
            },
            hideClass: {
                popup: 'neci-swal-toast-animate-out',
                ...(config.hideClass || {}),
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);

                if (typeof config.didOpen === 'function') {
                    config.didOpen(toast);
                }
            },
        });
    }

    const modalClasses = buildConfirmClasses(config.type || config.icon || 'warning');

    return Swal.fire(themedSwalOptions({
        buttonsStyling: false,
        ...config,
        customClass: mergeCustomClasses({
            popup: 'neci-swal-popup',
            title: 'neci-swal-title',
            htmlContainer: 'neci-swal-text',
            icon: modalClasses.icon,
            actions: 'neci-swal-actions',
            confirmButton: modalClasses.confirmButton,
            cancelButton: 'btn btn-light neci-swal-btn neci-swal-btn-cancel',
        }, config.customClass || {}),
    }));
}

function confirmFromTrigger(trigger) {
    return neciConfirm({
        title: readDataset(trigger, 'neciConfirmTitle', DELETE_DEFAULTS.title),
        text: readDataset(trigger, 'neciConfirmText', DELETE_DEFAULTS.text),
        type: readDataset(trigger, 'neciConfirmType', DELETE_DEFAULTS.type),
        confirmText: readDataset(trigger, 'neciConfirmYes', DELETE_DEFAULTS.confirmText),
        cancelText: readDataset(trigger, 'neciConfirmNo', DELETE_DEFAULTS.cancelText),
    });
}

function confirmFromForm(form) {
    const isRestore = form.hasAttribute('data-neci-confirm-submit');
    const defaults = isRestore ? RESTORE_DEFAULTS : DELETE_DEFAULTS;

    return neciConfirm({
        title: readDataset(form, 'neciConfirmTitle', defaults.title),
        text: readDataset(form, 'neciConfirmText', defaults.text),
        type: readDataset(form, 'neciConfirmType', defaults.type),
        confirmText: readDataset(form, 'neciConfirmYes', defaults.confirmText),
        cancelText: readDataset(form, 'neciConfirmNo', defaults.cancelText),
    });
}

function setupDeleteConfirmTriggers() {
    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-neci-confirm]');

        if (!trigger) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        const formId = trigger.dataset.neciConfirmForm;

        if (!formId) {
            return;
        }

        const result = await confirmFromTrigger(trigger);

        if (result.isConfirmed) {
            document.getElementById(formId)?.submit();
        }
    });
}

function setupCancelLeaveConfirm() {
    document.addEventListener('click', async (event) => {
        const trigger = event.target.closest('[data-neci-cancel-confirm]');

        if (!trigger) {
            return;
        }

        event.preventDefault();

        const result = await neciConfirm({
            title: readDataset(trigger, 'neciConfirmTitle', CANCEL_LEAVE_DEFAULTS.title),
            text: readDataset(trigger, 'neciConfirmText', CANCEL_LEAVE_DEFAULTS.text),
            type: readDataset(trigger, 'neciConfirmType', CANCEL_LEAVE_DEFAULTS.type),
            confirmText: readDataset(trigger, 'neciConfirmYes', CANCEL_LEAVE_DEFAULTS.confirmText),
            cancelText: readDataset(trigger, 'neciConfirmNo', CANCEL_LEAVE_DEFAULTS.cancelText),
        });

        if (result.isConfirmed) {
            const href = trigger.getAttribute('href');

            if (href) {
                window.location.href = href;
            }
        }
    });
}

function activateSubmitLoading(form) {
    if (!form.hasAttribute('data-neci-submit-loading')) {
        return;
    }

    form.classList.add('is-restoring');

    const submitBtn = form.querySelector('[data-neci-submit-btn]');

    if (submitBtn) {
        submitBtn.classList.add('is-restoring');
        submitBtn.disabled = true;
        submitBtn.setAttribute('aria-busy', 'true');
    }

    // Do not disable hidden fields or the file input — disabled fields are omitted from POST
    // (CSRF _token + backup_file must stay enabled or Laravel returns 419 / empty upload).
}

function submitFormAfterPaint(form) {
    // Programmatic form.submit() does not fire submit listeners — show loading first, then post.
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            form.submit();
        });
    });
}

function setupFormConfirmSubmit() {
    document.addEventListener('submit', async (event) => {
        const form = event.target;

        if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-neci-confirm-submit')) {
            return;
        }

        event.preventDefault();

        const result = await confirmFromForm(form);

        if (result.isConfirmed) {
            activateSubmitLoading(form);
            submitFormAfterPaint(form);
        }
    }, true);
}

function flushQueuedSessionAlert() {
    if (!window.__neciAlertConfig) {
        return;
    }

    const config = window.__neciAlertConfig;
    delete window.__neciAlertConfig;

    neciFireAlert(typeof config === 'string' ? JSON.parse(config) : config);
}

function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
        return;
    }

    callback();
}

onReady(() => {
    setupDeleteConfirmTriggers();
    setupCancelLeaveConfirm();
    setupFormConfirmSubmit();
    flushQueuedSessionAlert();
});

window.NeciConfirm = neciConfirm;
window.neciFireAlert = neciFireAlert;
