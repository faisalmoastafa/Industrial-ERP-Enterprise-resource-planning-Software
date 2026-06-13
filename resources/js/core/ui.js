function onReady(callback) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
        return;
    }

    callback();
}

function setupTooltips() {
    if (window.jQuery && typeof window.jQuery.fn.tooltip === 'function') {
        window.jQuery('[data-toggle="tooltip"]').tooltip();
    }
}

function setupDataTablesDefaults() {
    if (window.jQuery && typeof window.jQuery.fn.dataTable !== 'undefined') {
        window.jQuery.extend(true, window.jQuery.fn.dataTable.defaults, {
            stateSave: false,
        });
    }
}

function setupCheckboxSelectAll() {
    document.querySelectorAll('[data-select-all], #select-all').forEach((toggle) => {
        toggle.addEventListener('change', function () {
            const scopeSelector = this.dataset.selectAllScope;
            const scope = scopeSelector ? document.querySelector(scopeSelector) : this.closest('form');
            const target = scope || document;

            target.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
                if (checkbox !== this && !checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
        });
    });
}

function setupActivePermissionTab() {
    if (!window.jQuery || !document.getElementById('v-pills-tab')) {
        return;
    }

    const storageKey = 'activePermissionTab';

    window.jQuery('a[data-toggle="pill"]').on('shown.bs.tab', function (event) {
        localStorage.setItem(storageKey, window.jQuery(event.target).attr('href'));
    });

    const activeTab = localStorage.getItem(storageKey);
    if (activeTab) {
        window.jQuery('#v-pills-tab a[href="' + activeTab + '"]').tab('show');
    }
}

function setupCustomFileInputLabels() {
    document.querySelectorAll('.custom-file-input').forEach((fileInput) => {
        fileInput.addEventListener('change', function () {
            const label = this.nextElementSibling;
            const fileName = this.files && this.files.length ? this.files[0].name : 'Choose file...';

            if (label) {
                label.textContent = fileName;
            }
        });
    });
}

onReady(() => {
    setupTooltips();
    setupDataTablesDefaults();
    setupCheckboxSelectAll();
    setupActivePermissionTab();
    setupCustomFileInputLabels();
});
