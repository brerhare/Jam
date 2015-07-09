#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "common.h"
#include "stringUtil.h"
#include "linkListUtil.h"
#include "list.h"

LIST_CONTAINER *listContainer[MAX_LIST_CONTAINER];

/*
linkList *l = listCreate();
char *s1 = strdup("Item1");
char *s2 = strdup("Item2");
char *item = (char *) listAlloc(sizeof(s1));
strcpy(item, s1);
listAddItem(l, item);
item = (char *) listAlloc(sizeof(s2));
strcpy(item, s2);
listAddItem(l, item);
char *p = (char *) listFirst(l);
printf("1st=[%s]\n", p);
p = (char *) listNext(p);
printf("2nd=[%s]\n", p);
listRemove(l);
*/

/* #define LIST_MYSQL_TYPE     0
   #define LIST_LINKLIST_TYPE  1

typedef struct {
    linkList *list;
} LIST_DATA_LINKLIST;

typedef struct {
    char *tableName;
    MYSQL_RES *res;
    int numFields = mysql_num_fields(listData->res);
    char *mysqlHeaders[numFields];
    enum enum_field_types mysqlTypes[numFields];
} LIST_DATA_MYSQL;

typedef struct {
    char *name;
    int type;
    void *listData;     // could be any of the predefined types
} LIST_CONTAINER; */

LIST_CONTAINER *getNewListSlot();

LIST_CONTAINER *listCreateMysql(char *listName, char *tableName) {
    LIST_CONTAINER *lp = getNewListSlot();
    lp->name = strdup(listName);
    lp->type = LIST_MYSQL_TYPE;
    lp->listData = (LIST_DATA_MYSQL *) calloc(1, sizeof(LIST_DATA_MYSQL));
    LIST_DATA_MYSQL *data = (LIST_DATA_MYSQL *) lp->listData;
    data->tableName = (char *) strdup(tableName);
    return lp;
}

LIST_CONTAINER *listCreateLinkList(char *listName) {
    LIST_CONTAINER *lp = getNewListSlot();
    lp->name = strdup(listName);
    lp->type = LIST_LINKLIST_TYPE;
    lp->listData = (LIST_DATA_LINKLIST *) calloc(1, sizeof(LIST_DATA_LINKLIST));
    LIST_DATA_LINKLIST *data = (LIST_DATA_LINKLIST *) lp->listData;
    data->list = linkListCreate();
}

int listAdd(LIST_CONTAINER *lp, void *data) {
    if (lp->type == LIST_MYSQL_TYPE) {                                  // data is a MYSQL_RES object. Only ever one add
        LIST_DATA_MYSQL *listData = (LIST_DATA_MYSQL *) lp->listData;
        listData->res = (MYSQL_RES *) data;
        // Set up field info
        listData->numFields = mysql_num_fields(listData->res);
        listData->mysqlHeaders[listData->numFields];
        //enum_field_types data->mysqlTypes[data->numFields];
        MYSQL_FIELD *field;
        for (int i = 0; (field = mysql_fetch_field(listData->res)); i++) {
            listData->mysqlHeaders[i] = field->name;
            listData->mysqlTypes[i] = field->type;
        }
    }
    else if (lp->type == LIST_LINKLIST_TYPE) {                          // data is a blob
        LIST_DATA_LINKLIST *data = (LIST_DATA_LINKLIST *) lp->listData;
        void *blob = linkListAlloc(sizeof(blob)); // allocate space for a pointer
        blob = data;                              // we own the data now. Caller should not free it
        linkListAddItem(data->list, blob);
    }
    return 0;
}

int listTop(LIST_CONTAINER *lp) {
    if (lp->type == LIST_LINKLIST_TYPE) {
        // @@TODO
    }
    return 0;
}

void *listNext(LIST_CONTAINER *lp) {
    if (lp->type == LIST_LINKLIST_TYPE) {
        // @@TODO
    }
    return NULL;
}

LIST_CONTAINER *getListByName(char *listName) {
    for (int ix = 0; ix < MAX_LIST_CONTAINER; ix++) {
        if ((listContainer[ix]) && (listContainer[ix]->name) && (!(strcmp(listContainer[ix]->name, listName))) )
            return listContainer[ix];
    }
    return NULL;
}

LIST_CONTAINER *getNewListSlot() {
    for (int ix = 0; ix < MAX_LIST_CONTAINER; ix++) {
        if (listContainer[ix] == NULL) {
            listContainer[ix] = (LIST_CONTAINER *) calloc(1, sizeof(LIST_CONTAINER));
            return listContainer[ix];
        }
    }
    die("Run out of list container items");
}
