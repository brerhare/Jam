setup mysql development
	apt-get install g++
	apt-get install libmysqlclient-dev
	mysql_config --libs
	mysql_config --cflags
	g++ -Wno-write-strings $1 $(mysql_config --cflags) $(mysql_config --libs)

install jam
	copy jam.sh and jam to a /cgi-bin/ defined in the site's apache config
	edit jam.sh to point to wherever the jam rootdir should be (for templates, its own js, etc)
	browsers call jam.sh
