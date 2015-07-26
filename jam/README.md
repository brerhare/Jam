setup mysql development
	apt-get install g++
	apt-get install libmysqlclient-dev
	mysql_config --libs
	mysql_config --cflags
	g++ -Wno-write-strings $1 $(mysql_config --cflags) $(mysql_config --libs)

install jam
	alias this dir as /jam/ in the site's apache config and create a <directory></directory> entry (copy cgi-bin)
	remember to restart apache :-/
	softlink ./jamjar from the site's root
	create a template directory (suggested: jamtemplate) in the site's root. All jam calls will need to include '?template=xxx.tpl"

