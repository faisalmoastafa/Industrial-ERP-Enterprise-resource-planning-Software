@echo off
setlocal

cd /d "%~dp0"

echo.
echo ========================================
echo   NECI ERP - Clear Cache and Build
echo ========================================
echo.

set "PHP_EXE=C:\xampp\php\php.exe"

if not exist "%PHP_EXE%" (
    echo ERROR: PHP was not found at:
    echo %PHP_EXE%
    echo.
    echo Please update PHP_EXE inside this BAT file if PHP is installed somewhere else.
    echo.
    pause
    exit /b 1
)

where npm.cmd >nul 2>nul
if errorlevel 1 (
    echo ERROR: npm.cmd was not found.
    echo Please install Node.js, then try again.
    echo.
    pause
    exit /b 1
)

echo Clearing Laravel view cache...
"%PHP_EXE%" artisan view:clear
if errorlevel 1 goto failed

echo.
echo Clearing Laravel application cache...
"%PHP_EXE%" artisan cache:clear
if errorlevel 1 goto failed

echo.
echo Clearing Laravel config cache...
"%PHP_EXE%" artisan config:clear
if errorlevel 1 goto failed

echo.
echo Building frontend assets with Vite...
call npm.cmd run build
if errorlevel 1 goto failed

echo.
echo Rebuilding Blade view cache...
"%PHP_EXE%" artisan view:cache
if errorlevel 1 goto failed

echo.
echo ========================================
echo   Done: cache cleared and assets built.
echo ========================================
echo.
pause
exit /b 0

:failed
echo.
echo ========================================
echo   Failed. Please read the error above.
echo ========================================
echo.
pause
exit /b 1
