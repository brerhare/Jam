#include <stdio.h>
#include <string.h>
#include <assert.h>
#include <stdlib.h>
#include <malloc.h>

#include "listUtil.h"

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

struct linkListElement
{
	char idString[8];
	int size;
	struct linkListElement *next;
	struct linkListElement *prev;
	linkList *list;	// Each element has a pointer to its list header
};

linkList *listCreate()
{
	linkList *listPtr;
	listPtr = (linkList *) calloc(1, sizeof (linkList));
	listPtr->count = 0;
	return listPtr;
}

void *listAlloc(int size)
{
	struct linkListElement *ptr;
	ptr = (struct linkListElement *) calloc(1, (sizeof(struct linkListElement) + size));
	strcpy(ptr->idString, "listHdr");
	ptr->size = size;
	ptr++;	// Fwd to data
	return(ptr);
}

int listAddItem(linkList *list, void *data)
{
	linkListElement *tmpPtr, *elemPtr;

	elemPtr = (linkListElement *) data;
	elemPtr--;

	if (strcmp(elemPtr->idString, "listHdr"))
	{
		//logMsg(LOGERROR, "listAddItem: element header not recognised as pre-allocated space");
		return(-1);
	}

	list->count++;
	elemPtr->list = list;	// Point back to the list header
	if (!(list->first))
	{
		// First item in list
		elemPtr->prev = elemPtr->next = NULL;
		list->last = list->first = elemPtr;
	}
	else
	{
		// Add to end of list
		tmpPtr = list->last;
		tmpPtr->next = elemPtr;
		elemPtr->prev = tmpPtr;
		elemPtr->next = NULL;
		list->last = elemPtr;
	}
	return(0);
}

void *listFirst(linkList *list)
{
	void *retData;
	linkListElement *elemPtr;

	if (list)
	{
		if (list->first)
		{
			elemPtr = list->first;
			elemPtr++;
			retData = (void *) elemPtr;
			return(retData);
		}
	}
	return(NULL);
}

void *listNext(void *data)
{
	void *retData;
	linkListElement *elemPtr;

	elemPtr = (linkListElement *) data;
	elemPtr--;

	if (elemPtr->next)
	{
		elemPtr = elemPtr->next;
		elemPtr++;
		retData = (void *) elemPtr;
		return (retData);
	}
	else
	{
		return(NULL);
	}
}

int listGetItemSize(void *data)
{
	struct linkListElement *ptr = (struct linkListElement *) data;
	ptr--;
	if (strcmp(ptr->idString, "listHdr"))
	{
		//logMsg(LOGERROR, "listGetItemSize: this data doesnt point to a valid list item");
		return(-1);
	}
	return(ptr->size);
}

int listDeleteItem(linkList *list, void *data)
{
	linkListElement *elemPtr, *otherElemPtr;
	elemPtr = (linkListElement *) data;
	elemPtr--;

	if (elemPtr->prev)
	{
		otherElemPtr = elemPtr->prev;
		otherElemPtr->next = elemPtr->next;
	}
	else
		list->first = elemPtr->next;

	if (elemPtr->next)
	{
		otherElemPtr = elemPtr->next;
		otherElemPtr->prev = elemPtr->prev;
	}
	else
		list->last = elemPtr->prev;
	list->count--;
	free(elemPtr);
	return(0);
}

int listCount(linkList *list)
{
	return(list->count);
}

void listRemove(linkList *list)
{
	linkListElement *delPtr, *tmpPtr;
	if (!(list))
		//logMsg(LOGERROR, "listDelete: no list to delete");
		;
	else
	{
		// Cleanup - go thru the list freeing any elements we find
		tmpPtr = list->first;
		while (tmpPtr)
		{
			delPtr = tmpPtr;
			tmpPtr = tmpPtr->next;
			free(delPtr);
		}
		// Free the list itself
		free(list);
	}
}

/*--------------------------------------------------------------*/


struct nvpItem
{
	char *name;
	char *value;
};

linkList *nvpCreate()
{
	linkList *nvpList = listCreate();
	return nvpList;
}

int nvpAdd(linkList *nvpList, char *name, char *value)
{
	struct nvpItem *nvp;
	nvp = (nvpItem *) listAlloc(sizeof(struct nvpItem));
	nvp->name = strdup(name);
	nvp->value = strdup(value);
	return (listAddItem(nvpList, nvp));
}

int nvpAddReplace(linkList *nvpList, char *name, char *value)
{
	struct nvpItem *nvp;
	nvp = (struct nvpItem *) listFirst(nvpList);
	while (nvp)
	{
		if (!(strcmp(name, nvp->name)))
		{
			free(nvp->value);
			nvp->value = strdup(value);
			return(0);
		}
		else
			nvp = (struct nvpItem *) listNext(nvp);
	}
	return(nvpAdd(nvpList, name, value));
}

char *nvpGet(linkList *nvpList, char *name)
{
	struct nvpItem *nvp;
	nvp = (struct nvpItem *) listFirst(nvpList);
	while (nvp)
	{
		if (!(strcmp(name, nvp->name)))
			return(nvp->value);
		else
			nvp = (struct nvpItem *) listNext(nvp);
	}
	return(NULL);
}

// Nvp walking returns only 'name'. Do a nvpGet afterward to retrieve the related value
// Retrieves the 1st name in the nvp list
char *nvpFirst(linkList *nvpList)
{
	struct nvpItem *nvp;
	nvp = (struct nvpItem *) listFirst(nvpList);
	if (nvp)
		return(nvp->name);
	else
		return(NULL);
}

// Nvp walking returns only 'name' (As above). Do a nvpGet afterward to retrieve the related value
// Retrieves the name in the nvp list that follows after the supplied name
char *nvpNext(linkList *nvpList, char *name)
{
	struct nvpItem *nvp;
	nvp = (struct nvpItem *) listFirst(nvpList);
	while (nvp)
	{
		if (!(strcmp(name, nvp->name)))
		{
			nvp = (struct nvpItem *) listNext(nvp);
			if (nvp)
				return(nvp->name);
			else
				return(NULL);
		}
		else
			nvp = (struct nvpItem *) listNext(nvp);
	}
	return(NULL);
}

void nvpRemove(linkList *nvpList)
{
	struct nvpItem *nvp;

	// Go thru list freeing nvp ptrs
	nvp = (struct nvpItem *) listFirst(nvpList);
	while (nvp)
	{
		free(nvp->name);
		free(nvp->value);
		nvp = (struct nvpItem *) listNext(nvp);
	}
	listRemove(nvpList);
}


/*--------------------------------------------------------------*/

/* Legacy stuff follows.....*/

/*
 * Node bucket
 */
typedef struct SMOplListBucket_s
{
	struct SMOplListBucket_s *next;
	struct SMOplListBucket_s *prev;
} SMOplListBucket_t, *SMOplListBucket_p;

/*
 * Linked list structure
 */
struct _SMOplList
{
	unsigned	count;	/* Number of elements currently in list	 */
	SMOplListBucket_p head;	/* Pointer to head element of list	 */
	SMOplListBucket_p z;	/* Pointer to last node of list		 */
	SMOplListBucket_t	headtail[2];	/* Space for head and tail nodes */
};

/*
 * Return a pointer to the user space given the address of the header of
 * a node.
 */
#define	_SMOplListUserSpace(h)	((void*)((SMOplListBucket_p)(h) + 1))

/*
 * Return a pointer to the header of a node, given the address of the
 * user space.
 */
#define	_SMOplListHeader(n)	((SMOplListBucket_p)(n) - 1)


/*
 * Allocate space for a SMOplList node
 *
 * Allocates the memory required for a node, adding a small
 * header at the start. The returned pointer can be used
 * exactly as if it had been allocated via SMOplMalloc(3) and
 * should be cast to the correct pointer type.
 *
 * Returns a pointer to the allocated block or NULL on error
 */
void *SMOplListAlloc(
	unsigned size /* Size of the block to allocate */
	)
{
	SMOplListBucket_p node;

	/*
         * Allocate the required memory plus enough space for
         * a SMOplListBucket_t
         */
	node = (SMOplListBucket_p) malloc( size + sizeof( SMOplListBucket_t ) );
	if( !node )
	{
		return( NULL );
	}

	return( _SMOplListUserSpace(node) ); /* Return pointer to user space */
}

/*
 * Free a SMOplList node
 *
 * Frees the memory allocated to SMOplList node. This memory *MUST*
 * have been allocated using SMOplListAlloc(3). This function does
 * NOT remove the node from the list. This should be done prior
 * to calling SMOplListFree(3) using the SMOplListDeleteNext(3) function.
 */
void SMOplListFree(
	void *node /* Pointer to node to SMOplFree */
	)
{
	assert(node);
	free( _SMOplListHeader(node) );
}

/*
 * Create a new SMOplList
 *
 * This function create a new SMOplList. Initially, the list contains
 * no nodes.
 *
 * Returns a SMOplList or SMOplListInvalid if the creation fails.
 */
SMOplList SMOplListCreate( void )
{
	SMOplList list;

	/*
	 * Allocate space
	 */
	list = (SMOplList) malloc( sizeof( struct _SMOplList ) );
	if( !list )
	{
		return( NULL );
	}

	/*
	 * Fill in structure
	 */
	list->count = 0;
	list->head = &(list->headtail[0]);
	list->z = &(list->headtail[1]);
	list->head->next = list->z->next = list->z;
	list->z->prev = list->head->prev = list->head;

	/*
	 * Return completed structure
	 */
	return( list );
}

/*
 * Destroy a SMOplList
 *
 * This function is used to destroy a SMOplList. The SMOplFree_node
 * argument function is called for each node that remains
 * in the list. This function should SMOplFree any allocated
 * memory in the node structure and then call SMOplListFree(3)
 * If the node structure needs no additional processing
 * pass the address of SMOplListFree(3) or use the SMOplListDefaultDestroy
 * macro
 */
void SMOplListDestroy(
	SMOplList list                 /* SMOplList to destroy */,
	SMOplListDestroyFunc SMOplFree_node /* Destroy function to be called for each node remaining in the SMOplList */,
	void *user_data 	  /* User data to be passed to SMOplFree_node() */
	)
{
	SMOplListBucket_p n, p;

	assert(list && SMOplFree_node); /* user_data maybe NULL */

	/*
	 * Free remaining items
	 */
	n = list->head->next;
	while( n != list->z )
	{
		p = n;
		n = n->next;
		(*SMOplFree_node)( _SMOplListUserSpace(p), user_data );
	}

	/*
	 * Free the list itself
	 */
	free( (char *)list );
}

/*
 * Insert a node in the SMOplList
 *
 * This function inserts a node into the list after the given
 * node. The node should have been allocated using SMOplListAlloc(3).
 * To insert at the head of the list use the SMOplListHead(3) function
 * to obtain a pointer to the head of the list and pass it as the
 * after parameter. Alternatively, use the SMOplListInsertFirst macro.
 */
void SMOplListInsertAfter(
	SMOplList list	/* SMOplList to insert into */,
	void *node  	/* Node to insert */,
	void *after  	/* Positioning node */
	)
{
	SMOplListBucket_p n = _SMOplListHeader(node);
	SMOplListBucket_p a = _SMOplListHeader(after);

	assert(list && node && after);

	/*
	 * Adjust pointers
	 */
	n->next = a->next;
	a->next = n;
	n->prev = a;
	n->next->prev = n;

	/*
	 * Increase count of number of nodes in list
	 */
	list->count++;
}

/*
 * Delete the next node from the SMOplList
 *
 * This function deletes the next node form the SMOplList. Any
 * memory allocated to the node is NOT SMOplFree'd however. If
 * the node is no longer required, the memory allocated to
 * it can be SMOplFree'd by calling SMOplListFree(3)
 *
 * Returns the deleted node
 */
void *SMOplListDeleteNext(
	SMOplList list 	/* The SMOplList to delete from */,
	void *prev	/* Positioning node */
	)
{
	SMOplListBucket_p n = _SMOplListHeader(prev);
	void *node;

	assert(list && prev);

	/*
         * Adjust pointers
         */
	node = _SMOplListUserSpace(n->next);
	n->next->next->prev = n;
	n->next = n->next->next;

	/*
         * Decrease count of number of nodes in list
         */
	list->count--;

	/*
	 * Return node
	 */
	return( node );
}

/*
 * Delete the the given node from the SMOplList
 *
 * This function deletes the given node form the SMOplList. Any
 * memory allocated to the node is NOT SMOplFree'd however. If
 * the node is no longer required, the memory allocated to
 * it can be SMOplFree'd by calling SMOplListFree(3)
 *
 * Returns nothing
 */
void SMOplListDelete(
	SMOplList list	/* The SMOplList to delete from */,
	void *node	/* Node to delete  */
	)
{
	SMOplListBucket_p n = _SMOplListHeader(node);
	SMOplListBucket_p next = n->next;
	SMOplListBucket_p prev = n->prev;

	assert(list && node);

	/*
	 * Adjust pointers
	 */
	prev->next = next;
	next->prev = prev;

	/*
         * Decrease count of number of nodes in list
         */
	list->count--;
}

/*
 * Return the first node in the SMOplList
 *
 * This function returns the first node in the SMOplList
 *
 * Returns the first node in the SMOplList or NULL if no such
 * node exists
 */
void *SMOplListFirst(
	SMOplList list /* The SMOplList */
	)
{
	SMOplListBucket_p n;

	assert(list);

	n = list->head->next;
	return( ((n == list->z) ? NULL : _SMOplListUserSpace(n) ) );
}

/*
 * Return the last node in the SMOplList
 *
 * This function returns the last node in the SMOplList
 *
 * Returns the last node in the SMOplList or NULL if no such
 * node exists
 */
void *SMOplListLast(
	SMOplList list /* The SMOplList */
	)
{
	SMOplListBucket_p n;

	assert(list);

	n = list->z->prev;
	return( ((n == list->head) ? NULL : _SMOplListUserSpace(n)) );
}

/*
 * Return the next node in the SMOplList
 *
 * This function returns the next node in the SMOplList
 *
 * Returns the next node in the SMOplList or NULL if no such
 * node exists
 */
void *SMOplListNext(
	void *node /* The node */
	)
{
	SMOplListBucket_p n = _SMOplListHeader(node);

	assert(node);

	n = n->next;
	return( ((n == n->next) ? NULL : _SMOplListUserSpace(n)) );
}

/*
 * Return the previous node in the SMOplList
 *
 * This function returns the previous node in the SMOplList
 *
 * Returns the previous node in the SMOplList or NULL if no such
 * node exists
 */
void *SMOplListPrev(
	void *node /* The node */
	)
{
	SMOplListBucket_p n = _SMOplListHeader(node);

	assert(node);

	n = n->prev;
	return( ((n == n->prev) ? NULL : _SMOplListUserSpace(n)) );
}


/*
 * Obtain a pointer to the head of the SMOplList
 *
 * This function returns the head of the SMOplList. This is NOT
 * the first item in the SMOplList. To get the first item in the
 * SMOplList use SMOplListFirst(3). This function is only useful in
 * conjunction with SMOplListInsertAfter(3) in order to insert
 * a node at the start of the SMOplList. This can also be accomplished
 * using the SMOplListInsertFirst macro
 *
 * Returns a pointer to the head of the SMOplList (this never fails)
 */
void *SMOplListHead(
	SMOplList list /* The SMOplList */
	)
{
	assert(list);
        return( _SMOplListUserSpace(list->head) );
}

/*
 * Obtain a pointer to the tail of the SMOplList
 *
 * This function returns the head of the SMOplList. This is NOT
 * the last item in the SMOplList. To get the last item in the
 * SMOplList use SMOplListLast(3). This function is only useful in
 * conjunction with SMOplListInsertAfter(3) in order to insert
 * a node at the end of the SMOplList. This can also be accomplished
 * using the SMOplListInsertLast macro
 *
 * Returns a pointer to the tail of the SMOplList (this never fails)
 */
void *SMOplListTail(
	SMOplList list /* The SMOplList */
	)
{
	assert(list);
        return( _SMOplListUserSpace(list->z->prev) );
}

/*
 * Determine the number of items in the SMOplList
 *
 * This function can be used to obtain the number of items in the SMOplList
 *
 * Returns the number of items
 */
unsigned SMOplListNumItems(
	SMOplList list /* The SMOplList */
	)
{
	assert(list);
        return( list->count );
}
