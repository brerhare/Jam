#!/bin/sh

# -------------------------------------------------------------------------

deps="php5-mcrypt opendkim opendkim-tools build-essential git whois"

# -------------------------------------------------------------------------

LANG=
export LANG

while [ "$1" != "" ]; do
	case $1 in
		--help|-h)
			echo "Usage: `basename $0` [--help|-h]"
			echo
			echo "  --help|-h: This message"
			echo
			exit 0
		;;
		*)
		;;
	esac
	shift
done

arch=`uname -m`
if [ "$arch" = "i686" ]; then
	arch=i386
fi

yesno () {
	if [ "$NONINTERACTIVE_MODE" != "" ]; then
		return 1
	fi
	while read line; do
		case $line in
			y|Y|Yes|YES|yes|yES|yEs|YeS|yeS) return 0
			;;
			n|N|No|NO|no|nO) return 1
			;;
			*)
			printf "\nPlease enter y or n: "
			;;
		esac
	done
}


# Perform an action and log it
runner () {
	cmd=$1
	printf "Running command ... "
	if $cmd; then
		sleep 1
		printf "Success \n"
		return 0
	else
		sleep 1
		echo
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		printf "$cmd failed.  Error (if any): $? \n"
		echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!"
		echo
		return 1
	fi
}

# Only root can run this
id | grep "uid=0(" >/dev/null
if [ "$?" != "0" ]; then
	uname -a | grep -i CYGWIN >/dev/null
	if [ "$?" != "0" ]; then
		echo "Fatal Error: This script must be run as root"
		exit 1
	fi
fi

# Install ------------------------------------------------------------------------------

echo
echo "Welcome to the new server installer"
echo "This hostname is `hostname`"
printf "Continue? y/n "
if [ "$skipyesno" != 1 ]; then
	if ! yesno
	then exit
	fi
fi

# First the interactive stuff -----------------------------------------------------------

echo "Setting time zone"
runner "dpkg-reconfigure tzdata"

# Then the non-interactive stuff --------------------------------------------------------

# Create a noninteractive apt-get configuration file (this is stupid... -y ought to do all of this)
cat << EOF > /tmp/apt.conf.noninteractive
APT::Get::Assume-Yes "true";
APT::Get::Show-Upgraded "true";
APT::Quiet "true";
DPkg::Options {"--force-confmiss";"--force-confold"};
DPkg::Pre-Install-Pkgs {"/usr/sbin/dpkg-preconfigure --apt";};
Dir::Etc::SourceList "/etc/apt/sources.list";
EOF
export DEBIAN_FRONTEND=noninteractive
apt_get_install="/usr/bin/apt-get --config-file /tmp/apt.conf.noninteractive -y --force-yes install"

echo "Cleaning up apt headers and packages, so we can start fresh..."
apt-get clean

echo "Installing/Updating packages..."
runner "$apt_get_install $deps"

# Then apply settings --------------------------------------------------------------------

php5enmod mcrypt
/etc/init.d/apache2 restart

# All done -------------------------------------------------------------------------------
echo "Fin"





