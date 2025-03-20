@echo off
setlocal

REM Define variables
set ORTHANC_PATH="C:\laragon\www\telerad4\Orthanc Server"
set CONFIG_DIR=%ORTHANC_PATH%\Configuration
set BACKUP_DIR=%ORTHANC_PATH%\Configuration.backup
set SCRIPT_DIR=%~dp0

echo Orthanc Configuration Update
echo ===========================
echo.

REM Check if Orthanc is installed
if not exist %ORTHANC_PATH% (
    echo ERROR: Orthanc installation not found at %ORTHANC_PATH%
    echo Please verify the path.
    goto :end
)

REM Check if running as administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: This script requires administrative privileges.
    echo Please right-click and select "Run as administrator".
    goto :end
)

REM Stop Orthanc service
echo Stopping Orthanc service...
net stop Orthanc
if %errorLevel% neq 0 (
    echo Warning: Could not stop Orthanc service. It might not be running.
)
timeout /t 2 > nul

REM Backup existing configuration
if exist %CONFIG_DIR% (
    echo Backing up existing configuration to %BACKUP_DIR%...
    if exist %BACKUP_DIR% rd /s /q %BACKUP_DIR%
    mkdir %BACKUP_DIR%
    xcopy /s /i /y %CONFIG_DIR%\*.* %BACKUP_DIR%
)

REM Create new orthanc.json configuration
echo Creating new configuration...
echo {> "%CONFIG_DIR%\orthanc.json"
echo   "Name": "Orthanc",>> "%CONFIG_DIR%\orthanc.json"
echo   "StorageDirectory": "OrthancStorage",>> "%CONFIG_DIR%\orthanc.json"
echo   "IndexDirectory": "OrthancStorage",>> "%CONFIG_DIR%\orthanc.json"
echo   "HttpServerEnabled": true,>> "%CONFIG_DIR%\orthanc.json"
echo   "HttpPort": 8042,>> "%CONFIG_DIR%\orthanc.json"
echo   "RemoteAccessAllowed": true,>> "%CONFIG_DIR%\orthanc.json"
echo   "AuthenticationEnabled": false,>> "%CONFIG_DIR%\orthanc.json"
echo   "DicomServerEnabled": true,>> "%CONFIG_DIR%\orthanc.json"
echo   "DicomAet": "ORTHANC",>> "%CONFIG_DIR%\orthanc.json"
echo   "DicomPort": 4242,>> "%CONFIG_DIR%\orthanc.json"
echo   "DicomCheckCalledAet": false,>> "%CONFIG_DIR%\orthanc.json"
echo   "DicomWeb": {>> "%CONFIG_DIR%\orthanc.json"
echo     "Enable": true,>> "%CONFIG_DIR%\orthanc.json"
echo     "Root": "/dicom-web/",>> "%CONFIG_DIR%\orthanc.json"
echo     "EnableWado": true,>> "%CONFIG_DIR%\orthanc.json"
echo     "WadoRoot": "/wado/">> "%CONFIG_DIR%\orthanc.json"
echo   },>> "%CONFIG_DIR%\orthanc.json"
echo   "WebDavEnabled": true,>> "%CONFIG_DIR%\orthanc.json"
echo   "WebDavUploadAllowed": true>> "%CONFIG_DIR%\orthanc.json"
echo }>> "%CONFIG_DIR%\orthanc.json"

REM Start Orthanc service
echo Starting Orthanc service...
net start Orthanc
if %errorLevel% neq 0 (
    echo ERROR: Failed to start Orthanc service.
    echo Please check the Orthanc logs for more information.
) else (
    echo Orthanc configuration successfully applied!
    echo.
    echo You can access Orthanc via:
    echo - Web interface: http://localhost:8042
    echo - DICOM port: 4242
    echo - AE Title: ORTHANC
)

REM Update the .env file to point to local Orthanc
echo Updating application .env file...
if exist telerad\.env (
    powershell -Command "(Get-Content telerad\.env) -replace 'ORTHANC_URL=.*', 'ORTHANC_URL=http://localhost:8042' | Set-Content telerad\.env"
    powershell -Command "(Get-Content telerad\.env) -replace 'ORTHANC_USERNAME=.*', 'ORTHANC_USERNAME=orthanc' | Set-Content telerad\.env"
    powershell -Command "(Get-Content telerad\.env) -replace 'ORTHANC_PASSWORD=.*', 'ORTHANC_PASSWORD=orthanc' | Set-Content telerad\.env"
    powershell -Command "(Get-Content telerad\.env) -replace 'OHIF_URL=.*', 'OHIF_URL=http://localhost:8042/ohif' | Set-Content telerad\.env"
    echo Application .env file updated to use local Orthanc
) else (
    echo Warning: Could not find telerad\.env file
)

:end
echo.
pause 