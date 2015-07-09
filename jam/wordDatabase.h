#ifndef __WORDDATABASE_H_INCLUDED
#define __WORDDATABASE_H_INCLUDED

int wordDatabaseList(int ix, char *defaultTableName);
int wordDatabaseNew(int ix, char *defaultTableName);
int wordDatabaseRemove(int ix, char *defaultTableName);
int wordDatabaseDatabase(int ix, char *defaultTableName);
int wordDatabaseDescribe(int ix, char *defaultTableName);

int wordDatabaseGet(int ix, char *defaultTableName);

#endif /* __WORDDATABASE_H_INCLUDED */
