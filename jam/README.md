setup mysql development
	apt-get install g++
	apt-get install libmysqlclient-dev
	mysql_config --libs
	mysql_config --cflags
	g++ -Wno-write-strings $1 $(mysql_config --cflags) $(mysql_config --libs)

install jam
	copy jam and jam.cfg to a /cgi-bin/ defined in the site's apache config
	edit jam.cfg - set rootDir to wherever the jam rootdir should be (for templates, its own js, etc)

