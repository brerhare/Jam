#!/bin/bash

TESTMODE=0
RUNFILE=

while test $# -gt 0
do
    case "$1" in
        -test|-t) TESTMODE=1
            ;;
        *) RUNFILE=$1
            ;;
    esac
    shift
done

if [ "$RUNFILE" != "" ]; then
	CMD="php util.php $RUNFILE $TESTMODE"
	if $CMD; then
		echo
	else
		echo
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		printf "$cmd failed.  Error (if any): $? \n"
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo
	fi
fi

