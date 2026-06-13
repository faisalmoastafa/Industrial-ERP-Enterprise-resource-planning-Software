const { app, BrowserWindow, shell, dialog, Menu } = require('electron');
const { spawn, spawnSync } = require('child_process');
const path = require('path');
const fs = require('fs');
const net = require('net');

// ─── GPU / Renderer flags ─────────────────────────────────────────────────────
// Fix: Electron on Windows reports "Unable to create cache / Gpu Cache Creation
// failed: -2" when the AppData cache folder is locked or inaccessible.
// Disabling the GPU shader disk cache eliminates the compositing blink caused
// by uncached GPU layers (especially backdrop-filter and complex gradients).
app.commandLine.appendSwitch('disable-gpu-shader-disk-cache');
app.commandLine.appendSwitch('disable-features', 'HardwareMediaKeyHandling,MediaSessionService');

// ─── Paths ────────────────────────────────────────────────────────────────────
const RESOURCES   = app.isPackaged ? process.resourcesPath : '';
const DEV_APP_DIR = process.env.INDUSTRIAL_ERP_DEV_DIR || path.resolve(__dirname, '..', '..');
const PHP_EXE     = app.isPackaged ? path.join(RESOURCES, 'runtime', 'php', 'php.exe') : path.join(DEV_APP_DIR, 'Industrial-ERP-Software', 'php-8.3.31-Win32-vs16-x64', 'php.exe');
const APP_DIR     = app.isPackaged ? path.join(RESOURCES, 'runtime', 'laravel') : DEV_APP_DIR;
const BACKUP_DIR  = path.join(app.getPath('userData'), 'backups');
let PHP_PORT      = 8765;
let APP_URL       = `http://127.0.0.1:${PHP_PORT}`;

let phpProcess    = null;
let mainWindow    = null;
let splashWindow  = null;

// ─── Logging ──────────────────────────────────────────────────────────────────
const logFile = path.join(app.getPath('userData'), 'industrial-erp.log');
function log(msg) {
    const line = `[${new Date().toISOString()}] ${msg}\n`;
    try { fs.appendFileSync(logFile, line); } catch(_) {}
    console.log(msg);
}

// ─── Port check ───────────────────────────────────────────────────────────────
function isPortOpen(port) {
    return new Promise((resolve) => {
        const s = net.createConnection({ port, host: '127.0.0.1', timeout: 1000 });
        s.on('connect', () => { s.destroy(); resolve(true); });
        s.on('error',   () => resolve(false));
        s.on('timeout', () => { s.destroy(); resolve(false); });
    });
}

function waitForPort(port, retries = 40, delay = 1500) {
    return new Promise((resolve, reject) => {
        let attempts = 0;
        const check = () => {
            isPortOpen(port).then(open => {
                if (open) return resolve();
                if (++attempts >= retries) return reject(new Error(`Port ${port} never opened after ${retries} attempts`));
                setTimeout(check, delay);
            });
        };
        check();
    });
}

// ─── PHP server ───────────────────────────────────────────────────────────────
function startPHP() {
    return new Promise((resolve, reject) => {
        log('Starting PHP on port ' + PHP_PORT + '...');

        const userDataPath = app.getPath('userData');
        const userDataStorage = path.join(userDataPath, 'storage');
        const userDataDB = path.join(userDataPath, 'database.sqlite');
        
        // Ensure storage directory exists in userData
        if (!fs.existsSync(userDataStorage)) {
            try {
                fs.cpSync(path.join(APP_DIR, 'storage'), userDataStorage, { recursive: true });
            } catch(e) { log('Error copying storage: ' + e.message); }
        }
        // Ensure database exists in userData
        if (!fs.existsSync(userDataDB)) {
            try {
                fs.copyFileSync(path.join(APP_DIR, 'database', 'database.sqlite'), userDataDB);
            } catch(e) { log('Error copying database: ' + e.message); }
        }

        const phpEnv = { 
            ...process.env, 
            LARAVEL_STORAGE_PATH: userDataStorage, 
            DB_DATABASE: userDataDB,
            BACKUP_DIR: BACKUP_DIR,
            APP_ENV: app.isPackaged ? 'production' : (process.env.APP_ENV || 'local'),
            APP_DEBUG: app.isPackaged ? 'false' : (process.env.APP_DEBUG || 'true'),
            APP_URL: APP_URL,
            DEBUGBAR_ENABLED: 'false',
            LOG_LEVEL: app.isPackaged ? 'error' : (process.env.LOG_LEVEL || 'debug')
        };

        // Clear stale caches
        try {
            spawnSync(PHP_EXE, [path.join(APP_DIR, 'artisan'), 'config:clear'],
                { cwd: APP_DIR, env: phpEnv, stdio: 'pipe', timeout: 10000, windowsHide: true });
            spawnSync(PHP_EXE, [path.join(APP_DIR, 'artisan'), 'cache:clear'],
                { cwd: APP_DIR, env: phpEnv, stdio: 'pipe', timeout: 10000, windowsHide: true });
            spawnSync(PHP_EXE, [path.join(APP_DIR, 'artisan'), 'migrate', '--force'],
                { cwd: APP_DIR, env: phpEnv, stdio: 'pipe', timeout: 30000, windowsHide: true });
        } catch(_) {}

        // Spawn PHP directly to avoid artisan serve opening a visible CMD window
        phpProcess = spawn(PHP_EXE, [
            '-S', `127.0.0.1:${PHP_PORT}`,
            '-t', path.join(APP_DIR, 'public'),
            path.join(APP_DIR, 'server.php')
        ], { cwd: APP_DIR, env: phpEnv, windowsHide: true });

        phpProcess.stdout && phpProcess.stdout.on('data', d => log('PHP: ' + d.toString().trim()));
        phpProcess.stderr && phpProcess.stderr.on('data', d => log('PHP err: ' + d.toString().trim()));
        phpProcess.on('error', e => log('PHP spawn error: ' + e.message));
        phpProcess.on('close', code => log('PHP exited with code: ' + code));

        waitForPort(PHP_PORT, 40, 1500)
            .then(() => { log('PHP ready at ' + APP_URL); resolve(); })
            .catch(reject);
    });
}

// ─── Splash ───────────────────────────────────────────────────────────────────
function createSplash() {
    splashWindow = new BrowserWindow({
        width: 480, height: 320,
        frame: false, transparent: true,
        alwaysOnTop: true, resizable: false,
        skipTaskbar: true,
        webPreferences: { nodeIntegration: false }
    });
    splashWindow.loadFile(path.join(__dirname, 'splash.html'));
    splashWindow.center();

    // Inject the PHP port once the splash page has loaded so the logo fetch
    // knows which port to hit (port is chosen dynamically at startup).
    splashWindow.webContents.on('did-finish-load', () => {
        if (splashWindow && !splashWindow.isDestroyed()) {
            splashWindow.webContents.executeJavaScript(
                `window.__PHP_PORT__ = ${PHP_PORT};`
            ).catch(() => {});
        }
    });
}

// ─── Backup folder management ─────────────────────────────────────────────────
function ensureBackupFolder() {
    try {
        fs.mkdirSync(BACKUP_DIR, { recursive: true });
        log('Backup folder ready: ' + BACKUP_DIR);
    } catch (err) {
        log('Warning: Could not create backup folder: ' + err.message);
    }
}

function openBackupFolder() {
    try {
        ensureBackupFolder();
        shell.openPath(BACKUP_DIR);
        log('Opened backup folder: ' + BACKUP_DIR);
    } catch (err) {
        log('Error opening backup folder: ' + err.message);
        dialog.showErrorBox('Error', 'Could not open backup folder:\n' + BACKUP_DIR);
    }
}

// ─── User Manual ──────────────────────────────────────────────────────────────
const MANUAL_PATH = app.isPackaged
    ? path.join(process.resourcesPath, 'user-manual.html')
    : path.join(__dirname, 'user-manual.html');

function openUserManual() {
    try {
        if (!fs.existsSync(MANUAL_PATH)) {
            dialog.showErrorBox('User Manual', 'User manual file not found:\n' + MANUAL_PATH);
            return;
        }
        const manualWindow = new BrowserWindow({
            width: 1100, height: 820,
            title: 'Industrial ERP System — User Manual',
            icon: path.join(__dirname, 'icon.ico'),
            webPreferences: { nodeIntegration: false, contextIsolation: true, devTools: false }
        });
        manualWindow.loadFile(MANUAL_PATH);
        manualWindow.setMenuBarVisibility(false);
        log('Opened user manual: ' + MANUAL_PATH);
    } catch (err) {
        log('Error opening user manual: ' + err.message);
        dialog.showErrorBox('Error', 'Could not open user manual:\n' + err.message);
    }
}

// ─── Main window ──────────────────────────────────────────────────────────────
function createMainWindow() {
    mainWindow = new BrowserWindow({
        width: 1280, height: 800,
        minWidth: 1024, minHeight: 700,
        title: 'Industrial ERP System',
        icon: path.join(__dirname, 'icon.ico'),
        show: false,
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true,
            devTools: false
        }
    });

    // Create application menu with backup folder access
    const template = [
        {
            label: 'Navigation',
            submenu: [
                {
                    label: 'Back',
                    accelerator: 'Alt+Left',
                    click: () => {
                        if (mainWindow && mainWindow.webContents.canGoBack()) {
                            mainWindow.webContents.goBack();
                        }
                    }
                },
                {
                    label: 'Forward',
                    accelerator: 'Alt+Right',
                    click: () => {
                        if (mainWindow && mainWindow.webContents.canGoForward()) {
                            mainWindow.webContents.goForward();
                        }
                    }
                },
                {
                    label: 'Reload',
                    accelerator: 'CmdOrCtrl+R',
                    click: () => {
                        if (mainWindow) {
                            mainWindow.webContents.reload();
                        }
                    }
                }
            ]
        },
        {
            label: 'File',
            submenu: [
                {
                    label: 'Open Backup Folder',
                    accelerator: 'Ctrl+B',
                    click: () => openBackupFolder()
                },
                { type: 'separator' },
                {
                    label: 'Exit',
                    accelerator: 'Ctrl+Q',
                    click: () => app.quit()
                }
            ]
        },
        {
            label: 'Help',
            submenu: [
                {
                    label: 'User Manual',
                    accelerator: 'F1',
                    click: () => openUserManual()
                },
                { type: 'separator' },
                {
                    label: 'About Industrial ERP System',
                    click: () => {
                        dialog.showMessageBox(mainWindow, {
                            type: 'info',
                            title: 'About Industrial ERP System',
                            message: 'Industrial ERP System',
                            detail: 'Industrial ERP System\nVersion 1.0.0\n\nBackup Folder: ' + BACKUP_DIR
                        });
                    }
                }
            ]
        }
    ];

    const menu = Menu.buildFromTemplate(template);
    Menu.setApplicationMenu(menu);

    mainWindow.webContents.setWindowOpenHandler(({ url }) => {
        if (!url.startsWith('http://127.0.0.1')) {
            shell.openExternal(url);
            return { action: 'deny' };
        }
        return { action: 'allow' };
    });

    mainWindow.loadURL(APP_URL + '/login');

    mainWindow.once('ready-to-show', () => {
        if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
        mainWindow.show();
        mainWindow.focus();
    });

    mainWindow.on('closed', () => { mainWindow = null; });
}

// ─── Cleanup ──────────────────────────────────────────────────────────────────
function cleanup() {
    log('Shutting down...');
    if (phpProcess) { try { phpProcess.kill(); } catch(_) {} phpProcess = null; }
}

// ─── Single instance ──────────────────────────────────────────────────────────
const gotLock = app.requestSingleInstanceLock();
if (!gotLock) { app.quit(); }
else {
    app.on('second-instance', () => {
        if (mainWindow) {
            if (mainWindow.isMinimized()) mainWindow.restore();
            mainWindow.focus();
        }
    });
}

async function findFreePort(port) {
    while (await isPortOpen(port)) {
        log(`Port ${port} is taken, trying ${port + 1}`);
        port++;
    }
    return port;
}

// ─── App lifecycle ────────────────────────────────────────────────────────────
app.whenReady().then(async () => {
    log('=== Industrial ERP System Starting (SQLite Build) ===');
    log('Resources: ' + RESOURCES);
    log('UserData:  ' + app.getPath('userData'));
    log('Backup Folder: ' + BACKUP_DIR);

    ensureBackupFolder();
    createSplash();

    try {
        PHP_PORT = await findFreePort(8765);
        APP_URL = `http://127.0.0.1:${PHP_PORT}`;
        log(`Dynamic PHP Port Selected: ${PHP_PORT}`);

        await startPHP();
        createMainWindow();
    } catch (err) {
        log('FATAL: ' + err.message);
        if (splashWindow && !splashWindow.isDestroyed()) splashWindow.close();
        dialog.showErrorBox(
            'Industrial ERP System — Startup Error',
            err.message + '\n\nFull log:\n' + logFile
        );
        app.quit();
    }
});

app.on('window-all-closed', () => { cleanup(); app.quit(); });
app.on('before-quit', cleanup);
