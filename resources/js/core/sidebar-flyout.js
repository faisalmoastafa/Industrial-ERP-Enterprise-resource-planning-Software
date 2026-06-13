// ── Diagnostic counters (open DevTools console to observe) ─────────────────
const _neciDiag = {
    sidebarMounted: 0,
    sidebarUpdated: 0,
    dashboardRendered: 0,
    syncCollapsedCalls: 0,
};
window.__neciDiag = _neciDiag;

const flyout = document.createElement('div');
flyout.className = 'neci-sidebar-flyout';
document.body.appendChild(flyout);

let activeDropdown = null;
let hideTimer = null;
const dropdownSelector = '.c-sidebar-nav-dropdown, .neci-sidebar-dropdown';

const isMinimized = () => {
    const sidebar = document.getElementById('sidebar');
    return sidebar && sidebar.classList.contains('neci-sidebar-collapsed');
};

const closeFlyout = () => {
    flyout.classList.remove('is-visible');
    flyout.innerHTML = '';
    activeDropdown = null;
};

const scheduleClose = () => {
    clearTimeout(hideTimer);
    hideTimer = setTimeout(closeFlyout, 120);
};

const cancelClose = () => {
    clearTimeout(hideTimer);
};

const getDropdownLinks = (dropdown) => {
    return Array.from(dropdown.querySelectorAll(':scope > .c-sidebar-nav-dropdown-items > .c-sidebar-nav-item > .c-sidebar-nav-link'));
};

const normalizeFlyoutLinkLabel = (link) => {
    const label = Array.from(link.childNodes)
        .filter((node) => node.nodeType === Node.TEXT_NODE)
        .map((node) => node.textContent)
        .join(' ')
        .replace(/\s+/g, ' ')
        .trim();

    Array.from(link.childNodes)
        .filter((node) => node.nodeType === Node.TEXT_NODE)
        .forEach((node) => node.remove());

    if (!label) {
        return;
    }

    const labelElement = document.createElement('span');
    labelElement.className = 'neci-sidebar-flyout-label';
    labelElement.textContent = label;
    link.appendChild(labelElement);
};

const syncCollapsedDropdowns = (sidebar) => {
    _neciDiag.syncCollapsedCalls++;
    console.log(`[NECI Sidebar] syncCollapsedDropdowns called (total: ${_neciDiag.syncCollapsedCalls})`);
    const minimized = isMinimized();

    sidebar.classList.remove('c-sidebar-minimized');

    sidebar.querySelectorAll('.c-sidebar-nav-dropdown, .neci-sidebar-dropdown').forEach((dropdown) => {
        const toggle = dropdown.querySelector(':scope > .c-sidebar-nav-link');

        if (minimized) {
            dropdown.classList.add('neci-sidebar-dropdown');
            dropdown.classList.remove('c-sidebar-nav-dropdown');

            if (toggle) {
                toggle.classList.add('neci-sidebar-dropdown-toggle');
                toggle.classList.remove('c-sidebar-nav-dropdown-toggle');
            }

            return;
        }

        dropdown.classList.add('c-sidebar-nav-dropdown');
        dropdown.classList.remove('neci-sidebar-dropdown');

        if (toggle) {
            toggle.classList.add('c-sidebar-nav-dropdown-toggle');
            toggle.classList.remove('neci-sidebar-dropdown-toggle');
        }
    });

    if (!minimized) {
        closeFlyout();
    }
};

const positionFlyout = (dropdown) => {
    const rect = dropdown.getBoundingClientRect();
    const top = Math.min(rect.top + 2, window.innerHeight - flyout.offsetHeight - 16);

    flyout.style.left = `${rect.right + 12}px`;
    flyout.style.top = `${Math.max(12, top)}px`;
};

const openFlyout = (dropdown) => {
    if (!isMinimized()) {
        closeFlyout();
        return;
    }

    const links = getDropdownLinks(dropdown);

    if (!links.length) {
        closeFlyout();
        return;
    }

    activeDropdown = dropdown;
    flyout.innerHTML = '';

    links.forEach((link) => {
        const clonedLink = link.cloneNode(true);
        clonedLink.classList.add('neci-sidebar-flyout-link');
        normalizeFlyoutLinkLabel(clonedLink);
        flyout.appendChild(clonedLink);
    });

    flyout.classList.add('is-visible');
    positionFlyout(dropdown);
};

const SIDEBAR_STATE_KEY = 'neci-sidebar-collapsed';

const syncTogglePressedState = (sidebar, toggle) => {
    if (!toggle) {
        return;
    }

    const collapsed = sidebar.classList.contains('neci-sidebar-collapsed');
    toggle.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
    toggle.classList.toggle('is-active', collapsed);
    toggle.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
    toggle.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
};

const restoreSidebarState = (sidebar) => {
    if (sidebar.classList.contains('neci-sidebar-collapsed')) {
        return;
    }

    if (sessionStorage.getItem(SIDEBAR_STATE_KEY) === '1') {
        sidebar.classList.add('neci-sidebar-collapsed');
    }
};

const persistSidebarState = (sidebar) => {
    sessionStorage.setItem(
        SIDEBAR_STATE_KEY,
        sidebar.classList.contains('neci-sidebar-collapsed') ? '1' : '0'
    );
};

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const desktopToggle = document.getElementById('neciSidebarToggle');

    if (!sidebar) {
        return;
    }

    _neciDiag.sidebarMounted++;
    console.log(`[NECI Sidebar] Sidebar Mounted (total mounts: ${_neciDiag.sidebarMounted})`);
    console.log('[NECI Sidebar] data-sidebar-collapsed on body:', document.body.getAttribute('data-sidebar-collapsed'));
    console.log('[NECI Sidebar] data-app-theme on body:', document.body.dataset.appTheme);

    /* The <head> script has already written data-sidebar-collapsed="1" onto
       <body> if sessionStorage said the sidebar should be collapsed.  Apply
       that state to the sidebar element now — before the first paint completes
       (neci-preload still suppresses transitions at this moment) so there is
       no visible layout jump. */
    sidebar.classList.remove('c-sidebar-minimized');
    if (document.body.getAttribute('data-sidebar-collapsed') === '1') {
        sidebar.classList.add('neci-sidebar-collapsed');
    }

    syncCollapsedDropdowns(sidebar);
    syncTogglePressedState(sidebar, desktopToggle);

    if (desktopToggle) {
        desktopToggle.addEventListener('click', () => {
            sidebar.classList.toggle('neci-sidebar-collapsed');
            persistSidebarState(sidebar);
            closeFlyout();
            syncCollapsedDropdowns(sidebar);
            syncTogglePressedState(sidebar, desktopToggle);
        });
    }

    sidebar.querySelectorAll(dropdownSelector).forEach((dropdown) => {
        dropdown.addEventListener('mouseenter', (event) => {
            if (isMinimized()) {
                event.stopImmediatePropagation();
            }

            cancelClose();
            openFlyout(dropdown);
        }, true);

        dropdown.addEventListener('mouseleave', scheduleClose);
    });

    sidebar.querySelectorAll('.c-sidebar-nav-dropdown-toggle, .neci-sidebar-dropdown-toggle').forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            if (!isMinimized()) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();
            openFlyout(toggle.closest('.c-sidebar-nav-dropdown, .neci-sidebar-dropdown'));
        }, true);
    });

    flyout.addEventListener('mouseenter', cancelClose);
    flyout.addEventListener('mouseleave', scheduleClose);

    sidebar.addEventListener('scroll', () => {
        if (activeDropdown && flyout.classList.contains('is-visible')) {
            positionFlyout(activeDropdown);
        }
    }, true);

    window.addEventListener('resize', closeFlyout);

    document.addEventListener('click', (event) => {
        if (!flyout.contains(event.target)) {
            closeFlyout();
        }
    });
});
