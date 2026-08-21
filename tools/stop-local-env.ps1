# Stops the standalone local WordPress dev environment for this project.
# Usage: powershell -File tools\stop-local-env.ps1

$root    = Split-Path -Parent $PSScriptRoot
$pidFile = Join-Path $root ".localenv\mysql.pid"

if (Test-Path $pidFile) {
    $mysqlPid = (Get-Content $pidFile) -as [int]
    if ($mysqlPid) {
        Write-Host "Stopping MariaDB (pid $mysqlPid)..."
        Stop-Process -Id $mysqlPid -Force -ErrorAction SilentlyContinue
    }
}

Write-Host "Stopping PHP dev server processes bound to port 8890..."
Get-NetTCPConnection -LocalPort 8890 -State Listen -ErrorAction SilentlyContinue |
    ForEach-Object { Stop-Process -Id $_.OwningProcess -Force -ErrorAction SilentlyContinue }

Write-Host "Done. (mariadbd on port 3307 and php -S on port 8890 stopped, if they were running.)"
