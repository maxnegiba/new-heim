@echo off
title JPEG → WEBP (cwebp, same folder)

:: cwebp.exe se află în același folder cu acest script și imaginile
set "CWEPB=%~dp0cwebp.exe"

for %%F in (*.jpg *.jpeg *.JPG *.JPEG) do (
    echo Converting %%~nF...
    "%CWEPB%" "%%F" -q 75 -m 6 -o "%%~nF.webp"
)

echo.
echo Gata! Fișierele WebP au fost create în același folder.
pause