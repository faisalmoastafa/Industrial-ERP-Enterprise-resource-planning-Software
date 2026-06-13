@echo off
:: ============================================================
:: Industrial ERP System - Prepare Clean Build Copy
:: Run this from the project root before building the installer
:: ============================================================

title Industrial ERP System - Prepare Clean Build
color 0A

set "SRC=%~dp0"
:: Remove trailing backslash from SRC
if "%SRC:~-1%"=="\" set "SRC=%SRC:~0,-1%"

set "CLEAN=%SRC%\Industrial-ERP-Software\industrial-erp-system"
set "ELECTRON=C:\Industrial-ERP-Builder\electron"

echo ============================================================
echo   Industrial ERP System - Clean Build Preparation
echo ============================================================
echo.
echo   Source    : %SRC%
echo   Clean Copy: %CLEAN%
echo   Electron  : %ELECTRON%
echo.

:: ── STEP 1: Remove old clean copy ───────────────────────────
echo [1/6] Removing old clean copy...
if exist "%CLEAN%" (
    rmdir /s /q "%CLEAN%"
    echo       Old copy removed.
) else (
    echo       No old copy found, skipping.
)
mkdir "%CLEAN%"
echo       New folder created.
echo.

:: ── STEP 2: Copy Laravel runtime folders ────────────────────
echo [2/6] Copying Laravel folders...
echo       app\
xcopy /E /I /Q /Y "%SRC%\app"             "%CLEAN%\app\"             >nul
echo       bootstrap\
xcopy /E /I /Q /Y "%SRC%\bootstrap"       "%CLEAN%\bootstrap\"       >nul
echo       config\
xcopy /E /I /Q /Y "%SRC%\config"          "%CLEAN%\config\"          >nul
echo       database\
xcopy /E /I /Q /Y "%SRC%\database"        "%CLEAN%\database\"        >nul
echo       Modules\
xcopy /E /I /Q /Y "%SRC%\Modules"         "%CLEAN%\Modules\"         >nul
echo       public\
xcopy /E /I /Q /Y "%SRC%\public"          "%CLEAN%\public\"          >nul
echo       resources\
xcopy /E /I /Q /Y "%SRC%\resources"       "%CLEAN%\resources\"       >nul
echo       routes\
xcopy /E /I /Q /Y "%SRC%\routes"          "%CLEAN%\routes\"          >nul
echo       storage\
xcopy /E /I /Q /Y "%SRC%\storage"         "%CLEAN%\storage\"         >nul
echo       vendor\
xcopy /E /I /Q /Y "%SRC%\vendor"          "%CLEAN%\vendor\"          >nul
echo       Done.
echo.

:: ── STEP 3: Copy individual runtime files ───────────────────
echo [3/6] Copying individual files...
copy /Y "%SRC%\artisan"               "%CLEAN%\artisan"               >nul
copy /Y "%SRC%\server.php"            "%CLEAN%\server.php"            >nul
copy /Y "%SRC%\.env"                  "%CLEAN%\.env"                  >nul
copy /Y "%SRC%\composer.json"         "%CLEAN%\composer.json"         >nul
copy /Y "%SRC%\modules_statuses.json" "%CLEAN%\modules_statuses.json" >nul
copy /Y "%SRC%\.htaccess"             "%CLEAN%\.htaccess"             >nul
echo       artisan, server.php, .env, composer.json, modules_statuses.json, .htaccess
echo       Done.
echo.

:: ── STEP 4: Copy Electron build files ───────────────────────
echo [4/6] Copying Electron build files to %ELECTRON%...
if not exist "%ELECTRON%" mkdir "%ELECTRON%"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\package.json"     "%ELECTRON%\" >nul
copy /Y "%SRC%\Industrial-ERP-Software\build-files\main.js"          "%ELECTRON%\" >nul
copy /Y "%SRC%\Industrial-ERP-Software\build-files\splash.html"      "%ELECTRON%\" >nul
copy /Y "%SRC%\Industrial-ERP-Software\build-files\user-manual.html" "%ELECTRON%\" >nul
copy /Y "%SRC%\Industrial-ERP-Software\build-files\icon.ico"         "%ELECTRON%\" >nul
copy /Y "%SRC%\Industrial-ERP-Software\build-files\logo.png"         "%ELECTRON%\" >nul
echo       package.json, main.js, splash.html, user-manual.html, icon.ico, logo.png
echo       Done.
echo.

:: ── STEP 5: npm install in Electron directory ────────────────
echo [5/6] Running npm install in Electron directory...
cd /D "%ELECTRON%"
call npm install
if %ERRORLEVEL% neq 0 (
    color 0C
    echo.
    echo ERROR: npm install failed.
    echo Check the output above for details.
    echo.
    pause
    exit /b %ERRORLEVEL%
)
echo       npm install complete.
echo.

:: ── STEP 6: Verify manual is available ──────────────────────
echo [6/6] Verifying user manual...
if exist "%ELECTRON%\user-manual.html" (
    echo       user-manual.html is present. OK.
) else (
    color 0E
    echo       WARNING: user-manual.html was NOT copied. Check source path.
    color 0A
)
echo.

:: ── Done ─────────────────────────────────────────────────────
color 0A
echo ============================================================
echo   PREPARATION COMPLETE
echo ============================================================
echo.
echo   Clean copy : %CLEAN%
echo   Electron   : %ELECTRON%
echo.
echo   What was EXCLUDED (not copied):
echo     node_modules\   .git\   .kiro\   .vscode\   .npm-cache\
echo     tests\   backup\   docs\   Industrial-ERP-Software\
echo     fix_*.php   *.bat (dev)   *.log   phpunit.xml
echo     vite.config.js   package.json   package-lock.json
echo.
echo   Ready to build! Run Industrial-ERP-Software\build.bat
echo   OR run electron-builder manually from: %ELECTRON%
echo.
pause
