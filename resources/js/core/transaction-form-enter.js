const ENTER_NAV_SELECTOR = [
    'input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([disabled])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    'button.neci-product-search__item:not([disabled])',
].join(', ');

let focusLineQuantityAfterAdd = false;

function isVisible(el) {
    return el && (el.offsetParent !== null || el.getClientRects().length > 0);
}

function shouldSkipField(el) {
    if (!isVisible(el)) {
        return true;
    }

    if (el.readOnly && el.tagName === 'INPUT') {
        return true;
    }

    if (el.closest('.neci-product-search__dropdown--loading')) {
        return true;
    }

    if (el.closest('.neci-tx-cart-showcase')) {
        return true;
    }

    return false;
}

function getTransactionForm(target) {
    return target?.closest?.('form.neci-transaction-form') ?? null;
}

function getEnterNavFields(form) {
    return Array.from(form.querySelectorAll(ENTER_NAV_SELECTOR)).filter((el) => !shouldSkipField(el));
}

function focusField(el) {
    if (!el) {
        return;
    }

    el.focus();

    if (el.tagName === 'SELECT') {
        return;
    }

    if (el.tagName === 'INPUT' && ['text', 'search', 'number', 'date'].includes(el.type)) {
        el.select?.();
    }
}

function resolveCurrentField(form, current) {
    const fields = getEnterNavFields(form);

    if (current instanceof HTMLElement && fields.includes(current)) {
        return { fields, current, index: fields.indexOf(current) };
    }

    const active = document.activeElement;

    if (active instanceof HTMLElement && fields.includes(active)) {
        return { fields, current: active, index: fields.indexOf(active) };
    }

    return { fields, current, index: -1 };
}

function focusNextField(form, current) {
    const { fields, index } = resolveCurrentField(form, current);

    if (index === -1) {
        return;
    }

    const next = fields[index + 1];

    if (next) {
        focusField(next);
        return;
    }

    const submit = form.querySelector('button[type="submit"], .neci-tx-submit');
    focusField(submit);
}

function focusLineQuantity(form, attempt = 0) {
    const quantityInput = form.querySelector('[data-neci-line="quantity"]');

    if (quantityInput && isVisible(quantityInput) && form.contains(quantityInput)) {
        focusField(quantityInput);
        return true;
    }

    if (attempt < 25) {
        window.setTimeout(() => focusLineQuantity(form, attempt + 1), 100);
    }

    return false;
}

function focusFirstProductResult(form) {
    const first = form.querySelector('.neci-product-search__dropdown--results .neci-product-search__item');

    if (first) {
        first.focus();
        return true;
    }

    return false;
}

function applyLineItemUpdate(form) {
    const applyButton = form.querySelector('[data-neci-line="apply"]');

    if (applyButton) {
        applyButton.click();
    }
}

function isLineItemField(el) {
    return el instanceof HTMLInputElement && Boolean(el.dataset.neciLine);
}

function handleEnterNavigation(event) {
    if (event.key !== 'Enter') {
        return;
    }

    const target = event.target;

    if (!(target instanceof HTMLElement)) {
        return;
    }

    // ── Transaction form — existing dedicated logic ──────────────────────────
    const txForm = getTransactionForm(target);

    if (txForm) {
        if (target.closest('.neci-product-search__load-more')) {
            return;
        }

        if (target.classList.contains('neci-product-search__item')) {
            event.preventDefault();
            target.click();
            return;
        }

        if (target.tagName === 'TEXTAREA') {
            if (event.shiftKey) {
                return;
            }
            event.preventDefault();
            focusNextField(txForm, target);
            return;
        }

        if (target.tagName === 'BUTTON' && target.type === 'submit') {
            return;
        }

        if (target.tagName !== 'INPUT' && target.tagName !== 'SELECT') {
            return;
        }

        const inSearch = target.closest('.neci-product-search');

        if (inSearch && target.type === 'text') {
            const query = target.value?.trim() ?? '';

            if (query !== '' && focusFirstProductResult(txForm)) {
                event.preventDefault();
                return;
            }
        }

        event.preventDefault();
        event.stopPropagation();

        if (isLineItemField(target)) {
            applyLineItemUpdate(txForm);
            window.setTimeout(() => focusNextField(txForm, target), 80);
            return;
        }

        focusNextField(txForm, target);
        return;
    }

    // ── Generic form — universal Enter navigation (all forms everywhere) ─────
    const genericForm = target.closest('form');

    if (!genericForm) {
        return;
    }

    // Don't intercept submit buttons
    if (target.tagName === 'BUTTON') {
        return;
    }

    if (target.tagName !== 'INPUT' && target.tagName !== 'SELECT' && target.tagName !== 'TEXTAREA') {
        return;
    }

    // Textarea: shift+enter = newline, plain enter = next field
    if (target.tagName === 'TEXTAREA') {
        if (event.shiftKey) {
            return;
        }
        event.preventDefault();
        focusNextGenericField(genericForm, target);
        return;
    }

    event.preventDefault();
    focusNextGenericField(genericForm, target);
}

/**
 * Move focus to the next focusable field inside a generic form.
 * Falls back to the primary submit button when no next field exists.
 */
function focusNextGenericField(form, current) {
    const GENERIC_SELECTOR = [
        'input:not([type="hidden"]):not([type="submit"]):not([type="button"]):not([type="checkbox"]):not([type="radio"]):not([disabled])',
        'select:not([disabled])',
        'textarea:not([disabled])',
    ].join(', ');

    const fields = Array.from(form.querySelectorAll(GENERIC_SELECTOR)).filter(
        (el) => !shouldSkipField(el)
    );

    const index = fields.indexOf(current);

    if (index === -1) {
        return;
    }

    const next = fields[index + 1];

    if (next) {
        focusField(next);
        return;
    }

    // Last field — focus the submit button so the user can press Enter again to submit
    const submit = form.querySelector('button[type="submit"]');
    if (submit) {
        submit.focus();
    }
}

function scheduleLineQuantityFocus() {
    focusLineQuantityAfterAdd = true;
}

function runScheduledLineQuantityFocus() {
    if (!focusLineQuantityAfterAdd) {
        return;
    }

    const form = document.querySelector('form.neci-transaction-form');

    if (!form) {
        return;
    }

    focusLineQuantityAfterAdd = false;
    window.requestAnimationFrame(() => {
        focusLineQuantity(form);
    });
}

function setupTransactionFormEnterNavigation() {
    if (document.body.dataset.neciEnterNavReady === '1') {
        return;
    }

    document.body.dataset.neciEnterNavReady = '1';
    document.addEventListener('keydown', handleEnterNavigation, true);
}

function setupLivewireProductAddedHook() {
    if (!window.Livewire) {
        return;
    }

    Livewire.on('neci-product-added', scheduleLineQuantityFocus);

    Livewire.hook('morph.updated', () => {
        runScheduledLineQuantityFocus();
    });
}

function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
        return;
    }

    callback();
}

onReady(() => {
    setupTransactionFormEnterNavigation();
});

document.addEventListener('livewire:navigated', setupTransactionFormEnterNavigation);
document.addEventListener('livewire:init', setupLivewireProductAddedHook);

// Do NOT call setupLivewireProductAddedHook() eagerly here —
// livewire:init fires once when Livewire boots, which is the right moment.
// Calling it again immediately causes duplicate event listener registration.
