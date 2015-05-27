#!/bin/bash

LOCK=0
SINGLE_IP=87.112.143.230

# Reset iptables to 'factory' settings
echo Resetting iptables
iptables -P INPUT ACCEPT
iptables -P OUTPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -F INPUT
iptables -F OUTPUT
iptables -F FORWARD

if [ "$LOCK" = "1" ]; then
	echo Blocking incoming DNS, HTTP/HTTPS and outgoing SMTP to all but $SINGLE_IP

	# Block DNS lookup queries
	iptables -A INPUT -p udp --dport 53 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT -p tcp --dport 53 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT -p udp --dport 53 -j DROP
	iptables -A INPUT -p tcp --dport 53 -j DROP

	# Block HTTP/HTTPS
	iptables -A INPUT -p tcp --dport 80  -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT -p tcp --dport 443 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT -p tcp --dport 80  -j DROP
	iptables -A INPUT -p tcp --dport 443 -j DROP

	# Block SMTP
	iptables -A OUTPUT -p tcp --dport 25   -s $SINGLE_IP -j ACCEPT
	iptables -A OUTPUT -p tcp --dport 2525 -s $SINGLE_IP -j ACCEPT
	iptables -A OUTPUT -p tcp --dport 2526 -s $SINGLE_IP -j ACCEPT
	iptables -A OUTPUT -p tcp --dport 465  -s $SINGLE_IP -j ACCEPT
	iptables -A OUTPUT -p tcp --dport 587  -s $SINGLE_IP -j ACCEPT
	iptables -A OUTPUT -p tcp --dport 25   -j REJECT
	iptables -A OUTPUT -p tcp --dport 2525 -j REJECT
	iptables -A OUTPUT -p tcp --dport 2526 -j REJECT
	iptables -A OUTPUT -p tcp --dport 465  -j REJECT
	iptables -A OUTPUT -p tcp --dport 587  -j REJECT

	# Block IMAP/IMAPS
	iptables -A INPUT  -p tcp --dport 143 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT  -p tcp --dport 993 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT  -p tcp --dport 143 -j DROP
	iptables -A INPUT  -p tcp --dport 993 -j DROP

	# Block POP3
	iptables -A INPUT  -p tcp --dport 110 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT  -p tcp --dport 995 -s $SINGLE_IP -j ACCEPT
	iptables -A INPUT  -p tcp --dport 110 -j DROP
	iptables -A INPUT  -p tcp --dport 995 -j DROP

fi
echo Fin
