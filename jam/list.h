#ifndef __LIST_H_INCLUDED
#define __LIST_H_INCLUDED

#include "linkListUtil.h"

#define LIST_MYSQL_TYPE     0
#define LIST_LINKLIST_TYPE  1

#define MAXIMUM_NUMBER_OF_FIELDS    4096    // Allegedly this is mysql's maximum but I dont find it anywhere

typedef struct {
    linkList *list;
} LIST_DATA_LINKLIST;

typedef struct {
    char *tableName;
    MYSQL_RES *res;
    int numFields;
    char *mysqlHeaders[MAXIMUM_NUMBER_OF_FIELDS];
    enum enum_field_types mysqlTypes[MAXIMUM_NUMBER_OF_FIELDS];
} LIST_DATA_MYSQL;

typedef struct {
    char *name;
    int type;
    void *listData;
} LIST_CONTAINER;
#define MAX_LIST_CONTAINER 10000
extern LIST_CONTAINER *listHolder[MAX_LIST_CONTAINER];

LIST_CONTAINER *getListByName(char *listName);
LIST_CONTAINER *listCreateMysql(char *listName, char *tableName);
LIST_CONTAINER *listCreateLinkList(char *listName);
int listAdd(LIST_CONTAINER *lp, void *data);
int listTop(LIST_CONTAINER *lp);
void *listNext(LIST_CONTAINER *lp);

#endif /* __LIST_H_INCLUDED */
