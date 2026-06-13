@echo off
title NECI ERP - Live Test
color 0B

echo ========================================================
echo          NECI ERP - LIVE DEVELOPMENT TEST
echo ========================================================
echo.
echo Starting the application...
echo.
echo Copying latest files for live testing...
set "PROJECT_DIR=%~dp0"
set "PROJECT_DIR=%PROJECT_DIR:~0,-1%"
set "NECI_ERP_DEV_DIR=%PROJECT_DIR%"

copy /Y "%PROJECT_DIR%\software_ingredients\build-files\package.json" "C:\NECI-ERP-Builder\electron\" >nul
copy /Y "%PROJECT_DIR%\software_ingredients\build-files\main.js" "C:\NECI-ERP-Builder\electron\" >nul
copy /Y "%PROJECT_DIR%\software_ingredients\build-files\splash.html" "C:\NECI-ERP-Builder\electron\" >nul

echo Starting Electron live test...
cd /D C:\NECI-ERP-Builder\electron
call npm start
