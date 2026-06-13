function moneyOptionsFor(input) {
    return {
        prefix: input.dataset.moneyPrefix || '',
        thousands: input.dataset.moneyThousands || ',',
        decimal: input.dataset.moneyDecimal || '.',
        allowZero: input.dataset.moneyAllowZero !== 'false',
    };
}

function formatMoney(value, options) {
    if (value === null || value === undefined || isNaN(value)) {
        return '';
    }
    const fixed = Number(value).toFixed(2);
    const parts = fixed.split('.');
    let integerPart = parts[0];
    const decimalPart = parts[1];

    const thousandsSep = options.thousands !== undefined ? options.thousands : ',';
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

    const prefix = options.prefix || '';
    const decimalSep = options.decimal || '.';

    return `${prefix}${integerPart}${decimalSep}${decimalPart}`;
}

function parseMoney(value, options) {
    if (!value) return 0;
    let clean = String(value).trim();
    if (options.prefix && clean.startsWith(options.prefix)) {
        clean = clean.substring(options.prefix.length).trim();
    }
    // Remove thousands separators
    const escapedThousands = (options.thousands || ',').replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    clean = clean.replace(new RegExp(escapedThousands, 'g'), '');
    
    // Replace decimal separator with standard '.'
    const escapedDecimal = (options.decimal || '.').replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
    clean = clean.replace(new RegExp(escapedDecimal, 'g'), '.');

    const num = parseFloat(clean);
    return isNaN(num) ? 0 : num;
}

// Override jQuery maskMoney plugin with our custom left-to-right implementation
if (window.jQuery) {
    window.jQuery.fn.maskMoney = function (action, ...args) {
        const $el = window.jQuery(this);
        if ($el.length === 0) return this;

        if (typeof action === 'object' || !action) {
            const options = window.jQuery.extend({
                prefix: '',
                thousands: ',',
                decimal: '.',
                allowZero: true
            }, action);

            return this.each(function () {
                const el = this;
                const $item = window.jQuery(el);
                $item.data('maskMoney-options', options);

                if ($item.data('maskMoney-bound')) {
                    return;
                }
                $item.data('maskMoney-bound', true);

                // Format initial value
                const val = parseMoney(el.value, options);
                el.value = formatMoney(val, options);

                $item.on('focus.maskMoney', function () {
                    const rawVal = parseMoney(el.value, options);
                    el.value = rawVal === 0 && !options.allowZero ? '' : String(rawVal);
                    setTimeout(() => {
                        try {
                            el.setSelectionRange(0, el.value.length);
                        } catch (e) {}
                    }, 0);
                });

                $item.on('input.maskMoney', function () {
                    let val = el.value.replace(/[^0-9.-]/g, '');
                    const dotIdx = val.indexOf('.');
                    if (dotIdx !== -1) {
                        val = val.substring(0, dotIdx + 1) + val.substring(dotIdx + 1).replace(/\./g, '');
                    }
                    const isNegative = val.startsWith('-');
                    val = val.replace(/-/g, '');
                    if (isNegative) {
                        val = '-' + val;
                    }
                    el.value = val;
                });

                $item.on('blur.maskMoney', function () {
                    let rawVal = parseFloat(el.value);
                    if (isNaN(rawVal)) {
                        rawVal = 0;
                    }
                    el.value = formatMoney(rawVal, options);
                });
            });
        }

        if (action === 'unmasked') {
            const results = [];
            this.each(function () {
                const options = window.jQuery(this).data('maskMoney-options') || moneyOptionsFor(this);
                results.push(parseMoney(this.value, options));
            });
            return results;
        }

        if (action === 'mask') {
            const valueArg = args[0];
            return this.each(function () {
                const options = window.jQuery(this).data('maskMoney-options') || moneyOptionsFor(this);
                if (typeof valueArg !== 'undefined') {
                    this.value = formatMoney(valueArg, options);
                } else {
                    const currentVal = parseMoney(this.value, options);
                    this.value = formatMoney(currentVal, options);
                }
            });
        }

        return this;
    };
}

function setupMoneyFields(root = document) {
    if (!window.jQuery || typeof window.jQuery.fn.maskMoney !== 'function') {
        return;
    }

    root.querySelectorAll('[data-money]').forEach((input) => {
        const field = window.jQuery(input);

        if (field.data('neciMoneyReady')) {
            return;
        }

        field.maskMoney(moneyOptionsFor(input));
        field.data('neciMoneyReady', true);

        if (input.dataset.moneyMaskOnLoad === 'true') {
            field.maskMoney('mask');
        }
    });
}

function setupMoneyFillButtons(root = document) {
    root.querySelectorAll('[data-money-fill-target]').forEach((button) => {
        if (button.dataset.neciMoneyFillReady === 'true') {
            return;
        }

        button.dataset.neciMoneyFillReady = 'true';
        button.addEventListener('click', function () {
            if (!window.jQuery || typeof window.jQuery.fn.maskMoney !== 'function') {
                return;
            }

            const target = document.querySelector(this.dataset.moneyFillTarget);
            if (!target) {
                return;
            }

            const source = this.dataset.moneyFillSource
                ? document.querySelector(this.dataset.moneyFillSource)
                : null;
            const value = source ? source.value : this.dataset.moneyFillValue;

            window.jQuery(target).maskMoney('mask', Number(value || 0));
        });
    });
}

function setupMoneyFormUnmask(root = document) {
    root.querySelectorAll('form').forEach((form) => {
        if (form.dataset.neciMoneySubmitReady === 'true') {
            return;
        }

        form.dataset.neciMoneySubmitReady = 'true';
        form.addEventListener('submit', function () {
            if (!window.jQuery || typeof window.jQuery.fn.maskMoney !== 'function') {
                return;
            }

            this.querySelectorAll('[data-money]').forEach((input) => {
                const values = window.jQuery(input).maskMoney('unmasked');
                input.value = values && typeof values[0] !== 'undefined' ? values[0] : input.value;
            });
        });
    });
}

function setupMoneyFieldsWhenReady() {
    setupMoneyFields();
    setupMoneyFillButtons();
    setupMoneyFormUnmask();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupMoneyFieldsWhenReady);
} else {
    setupMoneyFieldsWhenReady();
}

window.addEventListener('showCheckoutModal', () => {
    window.NeciMoneyFields.refresh();
});

window.NeciMoneyFields = {
    refresh(root = document) {
        setupMoneyFields(root);
        setupMoneyFillButtons(root);
        setupMoneyFormUnmask(root);
    },
};
