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
#include "log.h"

typedef struct {
    linkList *list;
} LIST_DATA_LINKLIST;

typedef struct {
    char *name;
    int type;
    linkList *listData;
    void *lastData;			// holds the last returned data in the list, which gives us the position for 'next'
} LIST_CONTAINER;
#define MAX_LIST_CONTAINER 10000

LIST_CONTAINER *listContainer[MAX_LIST_CONTAINER];

LIST_CONTAINER *_newListSlot() {
    for (int i = 0; i < MAX_LIST_CONTAINER; i++) {
        if (listContainer[i] == NULL) {
            listContainer[i] = (LIST_CONTAINER *) calloc(1, sizeof(LIST_CONTAINER));
            return listContainer[i];
        }
    }
    return NULL;
}

LIST_CONTAINER *_getListSlot(char *listName) {
    LIST_CONTAINER *newContainer = NULL;
    for (int i = 0; i < MAX_LIST_CONTAINER; i++) {
        if (listContainer[i] == NULL)
            break;
        if (!strcmp(listName, listContainer[i]->name))
            return (listContainer[i]);
    }
    return NULL;
}

int listCreate(char *listName) {
    if (listName == NULL) {
        logMsg(LOGERROR, "listcreate - No list name supplied to create");
        return(-1);
    }
    LIST_CONTAINER *lp = _newListSlot();
    if (lp == NULL) {
        logMsg(LOGERROR, "listcreate - Run out of slots for list container");
        return (-1);
    }
    lp->name = strdup(listName);
    lp->type = LIST_TYPE_UNSTRUCTURED;
    lp->listData = linkListCreate();
}

// You could allocate the size of just a pointer if you want to store a blob thats managed externally
void *listAlloc(int size) {
    return linkListAlloc(size);
}

int listAdd(char *listName, void *data) {
    if (listName == NULL) {
        logMsg(LOGERROR, "listadd - No list name supplied to add to");
        return(-1);
    }
    LIST_CONTAINER *lp = _getListSlot(listName);
    if (lp == NULL) {
        logMsg(LOGERROR, "listadd - Cant find slot %s in list container", listName);
        return (-1);
    }
    if (lp->type == LIST_TYPE_UNSTRUCTURED) {                          // data is a blob
        //LIST_DATA_LINKLIST *data = (LIST_DATA_LINKLIST *) lp->listData;
        //void *storeData = linkListAlloc(sizeof(data)); // allocate space for a pointer
        //storeData = data;                              // we own the data now. Caller should not free it
        linkListAddItem(lp->listData, data);
        return(0);
    }
    return(-1);
}

void listRemove(char *listName) {
    if (listName == NULL)
        return;
    LIST_CONTAINER *lp = _getListSlot(listName);
    if (lp == NULL)
        return;
    linkListRemove(lp->listData);
    free(lp->name);
}





void *listFirst(char *listName) {
	if (listName == NULL) {
		logMsg(LOGERROR, "No list name supplied to list-first from");
		return NULL;
	}
	LIST_CONTAINER *lp = _getListSlot(listName);
	if (lp == NULL) {
		logMsg(LOGERROR, "list-first cant find slot %s in list container", listName);
		return NULL;
	}
	if (lp->type == LIST_TYPE_UNSTRUCTURED) {
		return (lp->lastData = linkListFirst(lp->listData));
	}
	lp->lastData = NULL;
	return NULL;
}

void *listNext(char *listName) {
    if (listName == NULL) {
        logMsg(LOGERROR, "No list name supplied to list-next from");
        return NULL;
    }
    LIST_CONTAINER *lp = _getListSlot(listName);
    if (lp == NULL) {
        logMsg(LOGERROR, "list-next cant find slot %s in list container", listName);
        return NULL;
    }
    if (lp->type == LIST_TYPE_UNSTRUCTURED) {
        return (lp->lastData = linkListNext(lp->lastData));
    }
    return NULL;
}

// ----------------------------------------------------------------------------------------------------------

void unitTestList() {
	int status = listCreate("test");
	if (status == -1) {
		logMsg(LOGERROR, "Cant create list");
	}
//	// Store a string
	char s1[512]; strcpy(s1, "STRING DATA 1");
	char *store1 = (char *) listAlloc(strlen(s1)+1); 
	strcpy(store1, s1);
	listAdd("test", store1);
//	// Store a string
	char *s2 = strdup("STRING DATA 2");
	char *store2 = (char *) listAlloc(strlen(s2)+1);
	strcpy(store2, s2);
	listAdd("test", store2);
//	// Store a string
	char *store3 = (char *) listAlloc(100);
	strcpy(store3, "STRING DATA 3");
	listAdd("test", store3);
	// retrieve them
	char *p = (char *) listFirst("test");
	while (p) {
		printf("item=[%s]\n", p);
		p = (char *) listNext("test");
	}
	listRemove("test");
}
