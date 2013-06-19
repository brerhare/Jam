@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::
::  S E T T I N G S
:::::::::::::::::::::::::::::::::::::::::::::::::::::


:: rsync variables
set id_rsa=id_rsa
set ssh_command=./ssh
set r_user=backupclient
set r_host=wireflydesign.com
set r_path=/home/backupclient/domain.co.uk.whospc.19b0b3f51c3b12164e55a9611b1a7d31
:goto TEST1

call vss-setvar.cmd
dosdev B: %SHADOW_DEVICE_1%

:TEST1

::::::::::::::::::::::::::::::::::::::::::::::::::::
::create snapshot directory
::::::::::::::::::::::::::::::::::::::::::::::::::::

set yyyy=%date:~-4%
set mm=%date:~-7,2%
set dd=%date:~-10,2%
set hr=%time:~0,2%
set min=%time:~3,2%
set sec=%time:~6,2%

if "%mm:~0,1%" equ " " set mm=0%hr:~1,1%
if "%dd:~0,1%" equ " " set dd=0%hr:~1,1%
if "%hr:~0,1%" equ " " set hr=0%hr:~1,1%
if "%min:~0,1%" equ " " set min=0%hr:~1,1%
if "%sec:~0,1%" equ " " set sec=0%hr:~1,1%

set timest=%yyyy%-%mm%-%dd%_%hr%.%min%.%sec%

echo Timestamp for this backup is [%timest%]

::::::::::::::::::::::::::::::::::::::::::::::::::::
::start rsync
::::::::::::::::::::::::::::::::::::::::::::::::::::

set linkdest=%r_path%/current
set r_eoptions="%ssh_command% -i %id_rsa% -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null"
set rsync_cmd=rsync -rtDHhx --chmod=ugo=rwX --stats --progress --delete --delete-excluded --exclude-from=rsync-excludes.txt --link-dest %linkdest% -e %r_eoptions% /cygdrive/c/ %r_user%@%r_host%:%r_path%/%timest%

set linktolatest="rm -f %r_path%/current && ln -s %r_path%/%timest% %r_path%/%current"
set rotate_cmd=ssh %r_user%@%r_host% -i %id_rsa% -o StrictHostKeyChecking=no -o UserKnownHostsFile=/dev/null %linktolatest%

echo ---------- >> log.dat
time /T >> log.dat
echo Starting rsync >> log.dat
echo %rsync_cmd% >> log.dat

%rsync_cmd%

echo ---------- >> log.dat
time /T >> log.dat
echo Starting rotate >> log.dat
echo %rotate_cmd% >> log.dat

%rotate_cmd%

:goto TEST2

:: release VSS snapshot
dosdev -r -d B:

:TEST2
