#ifndef __LIST_H_INCLUDED
#define __LIST_H_INCLUDED

#include "linkListUtil.h"

#define LIST_TYPE_UNSTRUCTURED  0

int listCreate(char *listName);
void *listAlloc(int size);
int listAdd(char *listName, void *data);
void *listFirst(char *listName);
void *listNext(char *listName);
void listRemove(char *listName);

#endif /* __LIST_H_INCLUDED */
