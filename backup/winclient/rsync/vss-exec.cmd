@echo off
:::::::::::::::::::::::::::::::::::::::::::::::::::::
::  S E T T I N G S
:::::::::::::::::::::::::::::::::::::::::::::::::::::


:: rsync variables
set id_rsa=id_rsa
set ssh_command=./ssh
set r_user=backupclient
set r_host=wireflydesign.com
set r_path=/home/backupclient/company.co.uk.machinename.32digithash
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
set hh=%time:~0,2%
set mm=%time:~3,2%
set ss=%time:~6,2%

set timest=%yyyy%-%mm%-%dd%_%hh%.%mm%.%ss%

echo Backing up to %timest%/

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
