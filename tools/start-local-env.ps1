# Starts the standalone local WordPress dev environment for this project.
# Uses PHP/MariaDB binaries bundled with LocalWP, but runs independently
# of Local's own GUI/site registry - see docs/HANDOFF.md for why.
#
# Usage: powershell -File tools\start-local-env.ps1

$ErrorActionPreference = "Stop"

$root      = Split-Path -Parent $PSScriptRoot
$localenv  = Join-Path $root ".localenv"
$phpDir    = "C:\Users\aleks\AppData\Local\Programs\Local\resources\extraResources\lightning-services\php-8.2.29+0\bin\win32"
$mariaDir  = "C:\Users\aleks\AppData\Local\Programs\Local\resources\extraResources\lightning-services\mariadb-10.6.23+0\bin\win32\bin"

$php       = Join-Path $phpDir "php.exe"
$mariadbd  = Join-Path $mariaDir "mariadbd.exe"
$ini       = Join-Path $localenv "php.ini"
$dataDir   = Join-Path $localenv "mysql-data"
$wpPath    = Join-Path $localenv "wordpress"
$dbLog     = Join-Path $localenv "mysql-logs\mariadbd.log"
$phpLog    = Join-Path $localenv "php-server.log"
$pidFile   = Join-Path $localenv "mysql.pid"
$sockFile  = Join-Path $localenv "mysql.sock"

Write-Host "Starting MariaDB (127.0.0.1:3307)..."
Start-Process -FilePath $mariadbd -ArgumentList @(
    "--datadir=$dataDir",
    "--port=3307",
    "--socket=$sockFile",
    "--pid-file=$pidFile",
    "--log-error=$dbLog",
    "--bind-address=127.0.0.1"
) -WindowStyle Hidden

Start-Sleep -Seconds 3

Write-Host "Starting PHP dev server (http://localhost:8890)..."
Start-Process -FilePath $php -ArgumentList @(
    "-c", $ini,
    "-S", "localhost:8890",
    "-t", $wpPath
) -RedirectStandardOutput $phpLog -RedirectStandardError $phpLog -WindowStyle Hidden

Write-Host ""
Write-Host "Site:      http://localhost:8890"
Write-Host "Admin:     http://localhost:8890/wp-admin  (user: zeus_admin, password in .localenv\wp-admin-password.txt)"
Write-Host "DB:        127.0.0.1:3307, db=zeus_rebuild, user=zeus_dev, password in .localenv\db-password.txt"
