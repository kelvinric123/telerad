@echo off
setlocal

echo Local Orthanc Test Script
echo =======================
echo.

REM Check service status
echo Checking Orthanc service status...
sc query Orthanc | findstr "STATE"
echo.

REM Test Orthanc web interface
echo Testing Orthanc web interface (http://localhost:8042)...
powershell -Command "try { $response = Invoke-WebRequest -Uri 'http://localhost:8042/system' -UseBasicParsing; if ($response.StatusCode -eq 200) { Write-Host 'SUCCESS: Orthanc web interface is running!' -ForegroundColor Green } else { Write-Host 'ERROR: Unexpected status code:' $response.StatusCode -ForegroundColor Red } } catch [System.Net.WebException] { if ($_.Exception.Response.StatusCode.value__ -eq 401) { Write-Host 'SUCCESS: Orthanc web interface is running but requires authentication' -ForegroundColor Yellow } else { Write-Host 'ERROR: Could not connect to Orthanc web interface. Is it running?' -ForegroundColor Red } }"
echo.

REM Test DICOM port
echo Testing DICOM port (4242)...
powershell -Command "try { $socket = New-Object System.Net.Sockets.TcpClient('localhost', 4242); if ($socket.Connected) { Write-Host 'SUCCESS: DICOM port (4242) is open!' -ForegroundColor Green; $socket.Close() } else { Write-Host 'ERROR: Could not connect to DICOM port.' -ForegroundColor Red } } catch { Write-Host 'ERROR: Could not connect to DICOM port. Is Orthanc running with DICOM server enabled?' -ForegroundColor Red }"
echo.

REM Test integration with Laravel app
echo Testing integration with Laravel app...
if exist telerad\.env (
    echo Checking application .env configuration:
    powershell -Command "Get-Content telerad\.env | Select-String -Pattern 'ORTHANC_URL|ORTHANC_USERNAME|ORTHANC_PASSWORD|OHIF_URL'"
) else (
    echo Warning: Could not find telerad\.env file
)
echo.

echo Testing completed!
echo.
echo If all tests passed, your local Orthanc installation is properly set up and configured.
echo The Laravel application should now be using your local Orthanc server instead of the Docker container.
echo.

pause 