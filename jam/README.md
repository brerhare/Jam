setup mysql development
	apt-get install g++
	apt-get install libmysqlclient-dev
	mysql_config --libs
	mysql_config --cflags
	g++ -Wno-write-strings $1 $(mysql_config --cflags) $(mysql_config --libs)

install jam
	apache
		alias /jamcgi/ to /home/SITE/dev/src/www/yii/SITE/jam/cgi/ in config
		create a <directory></directory> entry (copy cgi-bin)
	site root
		create jam
	site root
		create /jam
		cd to /jam
			symlink cgi -> ../../../../jam/cgi
			symlink sys -> ../../../../jam/sys
			any other user dirs can be created at this level (js, css etc)
