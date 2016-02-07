#!/bin/bash
#
#  _                       _           _ __  __ _             
# (_)                     (_)         | |  \/  (_)            
#  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___  
# | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \ 
# | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/ 
# |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___| 
#                     __/ |                                   
#                    |___/                                                                     
# 
# This program is a third party build by ImagicalMine.
#
# ImagicalMine is free software: you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# @author PocketMine Team, edited by the ImagicalMine team
# @link http://imagicalmine.net
#

DIR="$(cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
cd "$DIR"

# Loop toggling switch
# Set to DO_LOOP="no" if you want don't want it to loop, set to DO_LOOP="yes" if you want it to loop
DO_LOOP="no"

#######################################################################################
# ERROR: Restricted area - access denied. Don't try to edit anything below this line! #
#######################################################################################

while getopts "p:f:l" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
		f)
			POCKETMINE_FILE="$OPTARG"
			;;
		l)
			DO_LOOP="yes"
			;;
		\?)
			break
			;;
	esac
done

if [ "$PHP_BINARY" == "" ]; then
	if [ -f ./bin/php7/bin/php ]; then
		export PHPRC=""
		PHP_BINARY="./bin/php7/bin/php"
	elif [ type php 2>/dev/null ]; then
		PHP_BINARY=$(type -p php)
	else
		echo "Couldn't find a working PHP binary, please use the installer."
		exit 7
	fi
fi

if [ "$POCKETMINE_FILE" == "" ]; then
	if [ -f ./ImagicalMine.phar ]; then
		POCKETMINE_FILE="./ImagicalMine.phar"
	elif [ -f ./src/pocketmine/PocketMine.php ]; then
		POCKETMINE_FILE="./src/pocketmine/PocketMine.php"
	else
		echo "Couldn't find a valid ImagicalMine installation. If you have recently upgraded, ensure that you have renamed PocketMine-MP.phar to ImagicalMine.phar"
		exit 7
	fi
fi

LOOPS=0

set +e
while [ "$LOOPS" -eq 0 ] || [ "$DO_LOOP" == "yes" ]; do
	if [ "$DO_LOOP" == "yes" ]; then
		"$PHP_BINARY" "$POCKETMINE_FILE" $@
	else
		exec "$PHP_BINARY" "$POCKETMINE_FILE" $@
	fi
	((LOOPS++))
done

if [ ${LOOPS} -gt 1 ]; then
	echo "Restarted $LOOPS times"
fi
