@echo off
setlocal
set SRC=D:\PHP - Vaccination Management System
set OUT=%SRC%\deploy\vms-shared-hosting.zip

if exist "%OUT%" del "%OUT%"

powershell -NoProfile -Command ^
  "$src='%SRC%';" ^
  "$out='%OUT%';" ^
  "$tmp=Join-Path $env:TEMP ('vms_deploy_'+(Get-Random));" ^
  "New-Item -ItemType Directory -Path $tmp | Out-Null;" ^
  "New-Item -ItemType Directory -Path \"$tmp\public_html\" | Out-Null;" ^
  "New-Item -ItemType Directory -Path \"$tmp\database\" | Out-Null;" ^
  "Copy-Item \"$src\deploy\config.php\" \"$tmp\config.php\";" ^
  "Copy-Item \"$src\deploy\public_html\*\" \"$tmp\public_html\" -Force;" ^
  "Copy-Item \"$src\app\" \"$tmp\app\" -Recurse;" ^
  "Copy-Item \"$src\core\" \"$tmp\core\" -Recurse;" ^
  "Copy-Item \"$src\database\schema.sql\" \"$tmp\database\";" ^
  "Copy-Item \"$src\database\seed_pakistan.sql\" \"$tmp\database\";" ^
  "Copy-Item \"$src\deploy\DEPLOY.md\" \"$tmp\DEPLOY.md\";" ^
  "Compress-Archive -Path \"$tmp\*\" -DestinationPath $out -Force;" ^
  "Remove-Item $tmp -Recurse -Force;" ^
  "Write-Host 'ZIP created:' $out"

echo.
echo Done: %OUT%
endlocal
