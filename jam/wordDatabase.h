#ifndef __WORDDATABASE_H_INCLUDED
#define __WORDDATABASE_H_INCLUDED

int wordDatabaseListDatabases(int ix, char *defaultTableName);
int wordDatabaseListTables(int ix, char *defaultTableName);
int wordDatabaseNewDatabase(int ix, char *defaultTableName);
int wordDatabaseRemoveDatabase(int ix, char *defaultTableName);
int wordDatabaseDatabase(int ix, char *defaultTableName);
int wordDatabaseDescribe(int ix, char *defaultTableName);
int wordDatabaseGet(int ix, char *defaultTableName);

int wordDatabaseNewTable(int ix, char *defaultTableName);
int wordDatabaseRemoveTable(int ix, char *defaultTableName);

int wordDatabaseNewIndex(int ix, char *defaultTableName);
int wordDatabaseRemoveIndex(int ix, char *defaultTableName);


int wordDatabaseNewItem(int ix, char *defaultTableName);
int wordDatabaseAmendItem(int ix, char *defaultTableName);
int wordDatabaseUpdateItem(int ix, char *defaultTableName);
int wordDatabaseRemoveItem(int ix, char *defaultTableName);

#endif /* __WORDDATABASE_H_INCLUDED */
