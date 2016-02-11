wget https://bintray.com/artifact/download/pocketmine/PocketMine/PHP_5.6.10_x86-64_Linux.tar.gz
tar zxf PHP_5.6.10_x86-64_Linux.tar.gz
if [ ! -d "plugins" ]; then  
mkdir "plugins"  
fi
if [ ! -d "releases" ]; then  
mkdir "releases"  
fi
wget -O plugins/DevTools.phar https://github.com/PocketMine/DevTools/releases/download/v1.9.0/DevTools_v1.9.0.phar
chmod +x ./JenkinsBuild.php
./JenkinsBuild.php ${BUILD_NUMBER}
