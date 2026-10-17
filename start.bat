@echo off
echo Menyalakan API Python (model AI)...
start "API Python" cmd /k "cd /d D:\fish_api && venv\Scripts\activate && uvicorn main:app --port 8001"

echo Menunggu model dimuat (30 detik)...
timeout /t 30 /nobreak >nul

echo Menyalakan Laravel...
cd /d %~dp0
start "" http://127.0.0.1:8000
php artisan serve