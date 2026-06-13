@echo off
:: Automatically request administrator privileges
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo Requesting Administrative Privileges...
    powershell -Command "Start-Process cmd -ArgumentList '/c \"%~f0\"' -Verb RunAs"
    exit /b
)

:: Ensure the working directory is the project root (two levels up from this bat)
cd /d "%~dp0.."

title Industrial ERP System - Live Build Progress
color 0A

echo ========================================================
echo         Industrial ERP System - LIVE BUILDER
echo ========================================================
echo.

:: ── Paths (all relative to project root — works wherever the project lives) ──
set "SRC=%CD%"
set "CLEAN=%SRC%\Industrial-ERP-Software\industrial-erp-system"
set "ELECTRON=C:\Industrial-ERP-Builder\electron"

echo Cleaning old database initialization flag...
del /F /Q "%APPDATA%\industrial-erp\db_initialized" 2>nul
echo Done.

echo.
echo Cleaning up old build output...
if exist "C:\Industrial-ERP-Builder\dist" rmdir /s /q "C:\Industrial-ERP-Builder\dist"
echo Done.

echo.
echo ========================================================
echo  STEP 1 — Creating clean Laravel copy for packaging...
echo ========================================================
echo.

:: Remove previous clean copy and rebuild fresh
if exist "%CLEAN%" rmdir /s /q "%CLEAN%"
mkdir "%CLEAN%"

echo Copying app...
xcopy /E /I /Q /Y "%SRC%\app"            "%CLEAN%\app\"
echo Copying bootstrap...
xcopy /E /I /Q /Y "%SRC%\bootstrap"      "%CLEAN%\bootstrap\"
echo Copying config...
xcopy /E /I /Q /Y "%SRC%\config"         "%CLEAN%\config\"
echo Copying database...
xcopy /E /I /Q /Y "%SRC%\database"       "%CLEAN%\database\"
echo Copying Modules...
xcopy /E /I /Q /Y "%SRC%\Modules"        "%CLEAN%\Modules\"
echo Copying public...
xcopy /E /I /Q /Y "%SRC%\public"         "%CLEAN%\public\"
echo Copying resources...
xcopy /E /I /Q /Y "%SRC%\resources"      "%CLEAN%\resources\"
echo Copying routes...
xcopy /E /I /Q /Y "%SRC%\routes"         "%CLEAN%\routes\"
echo Copying storage (skeleton only)...
xcopy /E /I /Q /Y "%SRC%\storage"        "%CLEAN%\storage\"
echo Copying vendor...
xcopy /E /I /Q /Y "%SRC%\vendor"         "%CLEAN%\vendor\"

copy /Y "%SRC%\artisan"               "%CLEAN%\artisan"
copy /Y "%SRC%\server.php"            "%CLEAN%\server.php"
copy /Y "%SRC%\.env"                  "%CLEAN%\.env"
copy /Y "%SRC%\composer.json"         "%CLEAN%\composer.json"
copy /Y "%SRC%\modules_statuses.json" "%CLEAN%\modules_statuses.json"
copy /Y "%SRC%\.htaccess"             "%CLEAN%\.htaccess"

echo.
echo Clean copy created at: %CLEAN%
echo Done.

echo.
echo ========================================================
echo  STEP 2 — Copying Electron build files...
echo ========================================================
echo.

if not exist "%ELECTRON%" mkdir "%ELECTRON%"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\package.json"     "%ELECTRON%\"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\main.js"          "%ELECTRON%\"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\splash.html"      "%ELECTRON%\"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\user-manual.html" "%ELECTRON%\"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\icon.ico"         "%ELECTRON%\"
copy /Y "%SRC%\Industrial-ERP-Software\build-files\logo.png"         "%ELECTRON%\"
echo       All Electron files copied to %ELECTRON%
echo Done.

echo.
echo ========================================================
echo  STEP 3 — Installing Node modules...
echo ========================================================
echo.

cd /D "%ELECTRON%"
call npm install

echo.
echo ========================================================
echo  STEP 4 — Building installer (electron-builder)...
echo  This takes 3-5 minutes. Log: build-log.txt
echo ========================================================
echo.

set DEBUG=electron-builder
call npm.cmd run build > "%SRC%\Industrial-ERP-Software\build-log.txt" 2>&1

echo.
if %ERRORLEVEL% neq 0 (
    color 0C
    echo ========================================================
    echo BUILD FAILED! Check Industrial-ERP-Software\build-log.txt
    echo ========================================================
    echo.
    pause
    exit /b %ERRORLEVEL%
) else (
    echo ========================================================
    echo BUILD COMPLETED SUCCESSFULLY!
    echo Installer: C:\Industrial-ERP-Builder\dist\
    echo ========================================================
    explorer "C:\Industrial-ERP-Builder\dist\"
    timeout /t 3
    exit /b 0
)
