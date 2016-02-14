@echo off
/*
 _              _  _____                    
| |    ___  ___| ||_   _|__  __ _ _ __ ___  
| |   / _ \/ __| __|| |/ _ \/ _` | '_ ` _ \ 
| |__| (_) \__ \ |_ | |  __/ (_| | | | | | |
|_____\___/|___/\__||_|\___|\__,_|_| |_| |_|
 */  
   
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
