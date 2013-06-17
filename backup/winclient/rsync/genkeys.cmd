@echo off
openssl genrsa -out id_rsa 4096 >nul 2>&1
openssl rsa -in id_rsa -pubout -out id_rsa.pub >nul 2>&1
ssh-keygen -f id_rsa.pub -i -m PKCS8 > id_rsa.pub.4auth
dir \"Program Files" | openssl md5 -out id_rsa.hash.4auth >nul 2>&1
chmod o-rwx id_rsa
chmod g-rwx id_rsa
echo The long number in the file id_rsa.hash.4auth must be edited in vss-exec.cmd
echo The long number in the file id_rsa.hash.4auth must be recorded on the server
echo The contents of id_rsa.pub.4auth must be recorded on the server
