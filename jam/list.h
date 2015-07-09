#ifndef __LIST_H_INCLUDED
#define __LIST_H_INCLUDED

#include "linkListUtil.h"

#define LIST_LINKLIST_TYPE  0

typedef struct {
    linkList *list;
} LIST_DATA_LINKLIST;

typedef struct {
    char *name;
    int type;
    void *listData;
} LIST_CONTAINER;
#define MAX_LIST_CONTAINER 10000
extern LIST_CONTAINER *listHolder[MAX_LIST_CONTAINER];

LIST_CONTAINER *getListByName(char *listName);
LIST_CONTAINER *listCreateLinkList(char *listName);
int listAdd(LIST_CONTAINER *lp, void *data);
int listTop(LIST_CONTAINER *lp);
void *listNext(LIST_CONTAINER *lp);

#endif /* __LIST_H_INCLUDED */
