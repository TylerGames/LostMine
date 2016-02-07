tar zxf /etc/php5-pm/PHP_5.6.10_x86-64_Linux.tar.gz
cd ..
if [ ! -d "plugins" ]; then  
mkdir "plugins"  
fi
if [ ! -d "releases" ]; then  
mkdir "releases"  
fi
cp /etc/php5-pm/DevTools_v1.10.0.phar ./plugins/DevTools.phar
cd tests
chmod +x ./JenkinsBuild.php
./JenkinsBuild.php
