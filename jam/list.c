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
#include "listUtil.h"
#include "list.h"

LIST_HOLDER *listHolder[MAX_LIST_HOLDER];

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

LIST *listCreateMysql(char *name) {
    LIST_HOLDER *lp = NULL;
    for (int ix = 0; ix < MAX_LIST_HOLDER; ix++) {
        if (listHolder[ix] == NULL) {
            listHolder[ix] = (LIST_HOLDER *) calloc(1, sizeof(LIST_HOLDER));
            lp = listHolder[ix];
            break;
        }
    }
    if (!lp)
        die("Run out of list holder space");
    lp->name = strdup(name);
    lp->list = listCreate();
}

int ListAddItemMysql(LIST_HOLDER *lp, LIST_MYSQL *data) {
//char *item = (char *) listAlloc(sizeof(data));
}

LIST_HOLDER *getListByName(char *name) {
    for (int ix = 0; ix < MAX_LIST_HOLDER; ix++) {
        if ((listHolder[ix] == NULL) || (!(strcmp(listHolder[ix]->name, name))))
            return listHolder[ix];
    }
    return NULL;
}
