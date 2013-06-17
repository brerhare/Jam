@echo off
set basedir=%~dp0
set basedrv=%basedir:~0,2%
%basedrv%
cd %basedir%

:: check if this script is run with elevated privileges
set isadmin=n
>nul 2>&1 "%SYSTEMROOT%\system32\cacls.exe" "%SYSTEMROOT%\system32\config\system"&&(
	set isadmin=y
)
if %isadmin%==n (
	echo.
	echo Error: This script must be launched with right-click -^> "Run as Administrator"
	echo.
	pause
	exit
)

echo.
echo ---------- > log.dat
time /T >> log.dat
echo Starting backup >> log.dat
echo Starting backup

sync

:: start correct version of vshadow depending on OS version
SET Version=Unknown
VER | FINDSTR /IL "5.0" > NUL
IF %ERRORLEVEL% EQU 0 SET Version=2000
VER | FINDSTR /IL "5.1." > NUL
IF %ERRORLEVEL% EQU 0 SET Version=XP
VER | FINDSTR /IL "5.2." > NUL
IF %ERRORLEVEL% EQU 0 SET Version=2003
VER | FINDSTR /IL "6.0." > NUL
IF %ERRORLEVEL% EQU 0 SET Version=Vista
VER | FINDSTR /IL "6.1." > NUL
IF %ERRORLEVEL% EQU 0 SET Version=7
:: check for architecture
SET Archit=32
IF DEFINED ProgramFiles^(x86^) SET Archit=64

echo ---------- >> log.dat
time /T >> log.dat
echo Starting disk shadow >> log.dat

vshadow_%Version%_%Archit%.exe -script=vss-setvar.cmd -exec=vss-exec.cmd C:

echo ---------- >> log.dat
time /T >> log.dat
echo Backup finished >> log.dat

echo Backup finished.
echo.
pause
