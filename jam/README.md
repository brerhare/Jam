setup mysql development
	apt-get install libmysqlclient-dev
	mysql_config --libs
	mysql_config --cflags
	g++ -Wno-write-strings $1 $(mysql_config --cflags) $(mysql_config --libs)

to backup all databases and users
	mysqldump -uroot -p"Wole9anic-" --all-databases > db_backup.sql
