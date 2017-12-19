select email, displayname from member where email > "" INTO OUTFILE '/tmp/fran.txt' FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n';

# Now create a mailing list, and import this
