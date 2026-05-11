@echo off
REM Script de utilidad para Docker Compose
if "%1"=="start" (
    echo Iniciando contenedores...
    docker-compose up -d
    echo Contenedores iniciados. Accede a http://localhost:8000
) else if "%1"=="stop" (
    echo Deteniendo contenedores...
    docker-compose down
    echo Contenedores detenidos.
) else if "%1"=="logs" (
    docker-compose logs -f
) else if "%1"=="status" (
    docker-compose ps
) else if "%1"=="shell" (
    docker-compose exec php bash
) else if "%1"=="mysql" (
    docker-compose exec mysql mysql -u root -p
) else (
    echo Uso: docker-util.bat [comando]
    echo.
    echo Comandos disponibles:
    echo   start   - Iniciar los contenedores
    echo   stop    - Detener los contenedores
    echo   logs    - Ver los logs en tiempo real
    echo   status  - Ver el estado de los contenedores
    echo   shell   - Acceder al shell del contenedor PHP
    echo   mysql   - Acceder a MySQL
)
