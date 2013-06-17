@echo off
echo Creating keys...
openssl genrsa -out id_rsa 4096 >nul 2>&1
openssl rsa -in id_rsa -pubout -out id_rsa.pub >nul 2>&1
ssh-keygen -f id_rsa.pub -i -m PKCS8 > id_rsa.pub.4auth
dir \"Program Files" | openssl md5 -out id_rsa.hash.4auth >nul 2>&1
chmod o-rwx id_rsa
chmod g-rwx id_rsa
echo ------------------------------------------------
echo Created crypto keys:
dir /b id_*
echo ------------------------------------------------
echo (1) The hash must be edited into vss-exec.cmd
echo (2) Files id_rsa, id_rsa.pub, id_rsa.pub.4auth and id_rsa.hash.4auth (id_*) must be zipped and emailed to kim@wireflydesign.com in order to set up the backup account for this computer
echo (3) Files and folders in rsync-excludes.txt will be backed up
echo (4) Control Panel -- Administrative Tools -- Task Scheduler -- Create Basic Task -- "Backup" -- Daily -- Input a time -- Start a program -- backup.cmd -- Check 'Open the Properties dialog' -- Check 'Run whether logged on or not' and 'Run with highest privileges
echo ------------------------------------------------
