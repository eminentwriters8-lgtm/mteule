@echo off
title MTEULE Ventures Server
color 0A

echo ========================================
echo   MTEULE VENTURES DELIVERY WEBSITE
echo ========================================
echo.

:start
set port=8080
set server=server.exe

echo Checking for server.exe...
if exist "%server%" goto run_server

echo.
echo ERROR: server.exe not found!
echo.
echo Please download it from:
echo https://tinyurl.com/mteule-server
echo.
echo Save it in: F:\MTEULE
echo.
pause
exit /b

:run_server
echo Starting server on port %port%...
echo.
echo Open in browser: http://localhost:%port%
echo Press Ctrl+C to stop server
echo.
echo For public sharing, open another window and run:
echo ngrok http %port%
echo.
%server% -p %port%

if errorlevel 1 (
    echo.
    echo Port %port% is busy. Trying port 8081...
    set port=8081
    goto run_server
)