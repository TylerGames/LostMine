@echo off
::
::  _                       _           _ __  __ _             
:: (_)                     (_)         | |  \/  (_)            
::  _ _ __ ___   __ _  __ _ _  ___ __ _| | \  / |_ _ __   ___  
:: | | '_ ` _ \ / _` |/ _` | |/ __/ _` | | |\/| | | '_ \ / _ \ 
:: | | | | | | | (_| | (_| | | (_| (_| | | |  | | | | | |  __/ 
:: |_|_| |_| |_|\__,_|\__, |_|\___\__,_|_|_|  |_|_|_| |_|\___| 
::                     __/ |                                   
::                    |___/                                                                     
:: 
:: This program is a third party build by ImagicalMine.
::
:: ImagicalMine is free software: you can redistribute it and/or modify
:: it under the terms of the GNU Lesser General Public License as published by
:: the Free Software Foundation, either version 3 of the License, or
:: (at your option) any later version.
::
:: @author PocketMine Team, edited by the ImagicalMine team
:: @link http://imagicalmine.net
::

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
:: ERROR: Restricted area - access denied. Don't try to edit anything below this line! ::
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

TITLE LostTeam server software for Minecraft: Pocket Edition
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist LostTeam.phar (
	set POCKETMINE_FILE=LostTeam.phar
) else (
	if exist src\pocketmine\PocketMine.php (
		set POCKETMINE_FILE=src\pocketmine\PocketMine.php
	) else (
		echo "Couldn't find a valid ImagicalMine installation. If you have recently upgraded, ensure that you have renamed PocketMine-MP.phar to ImagicalMine.phar"
		pause
		exit 7
	)
)
if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "ImagicalMine" -i bin/pocketmine.ico -w max %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
) else (
	%PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*
)
