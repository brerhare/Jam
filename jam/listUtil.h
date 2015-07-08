#ifndef	__LIST_H_INCLUDED__
#define	__LIST_H_INCLUDED__

/*
 * Wrap these definitions inside an extern "C" if we're compiling C++ code
 */
#ifdef __cplusplus
extern "C" {
#endif

typedef struct
{
	int count;
	struct linkListElement *first;
	struct linkListElement *last;
} linkList;

// linkList Protos
linkList *listCreate();
void *listAlloc(int size);
int listAddItem(linkList *list, void *data);
void *listFirst(linkList *list);
void *listNext(void *data);
int listGetItemSize(void *data);
int listDeleteItem(linkList *list, void *data);
int listCount(linkList *list);
void listRemove(linkList *list);

// NVP Protos
linkList *nvpCreate();
int nvpAdd(linkList *nvpList, char *name, char *value);
int nvpAddReplace(linkList *nvpList, char *name, char *value);
char *nvpGet(linkList *nvpList, char *name);
char *nvpFirst(linkList *nvpList);
char *nvpNext(linkList *nvpList, char *name);
void nvpRemove(linkList *nvpList);

/*--------------------------------------------------------------*/

/*
 * Types
 */
typedef struct _SMOplList *SMOplList;

typedef void (*SMOplListDestroyFunc)( void *, void * );

void *SMOplListAlloc( unsigned size );
void SMOplListFree( void *node );

SMOplList SMOplListCreate( void );
void SMOplListDestroy( SMOplList list, SMOplListDestroyFunc SMOplFree_node, void *user_data );

void SMOplListInsertAfter( SMOplList list, void *node, void *after );
void *SMOplListDeleteNext( SMOplList list, void *prev );
void SMOplListDelete( SMOplList list, void *node );

void *SMOplListFirst( SMOplList list );
void *SMOplListLast( SMOplList list );
void *SMOplListNext( void *prev );
void *SMOplListPrev( void *next );

void *SMOplListHead( SMOplList list );
void *SMOplListTail( SMOplList list );

unsigned SMOplListNumItems( SMOplList list );


/*
 * Utility macros
 */
#define SMOplListDefaultDestroy(l) SMOplListDestroy((l),(SMOplListDestroyFunc)SMOplListFree,NULL)

#define SMOplListInsertFirst(l,n)  SMOplListInsertAfter((l),(n),SMOplListHead((l)))
#define SMOplListInsertLast(l,n)   SMOplListInsertAfter((l),(n),SMOplListTail((l)))

#define SMOplListInvalid 	      ((SMOplList)0)

/*
 * Close C++ wrapper
 */
#ifdef __cplusplus
}
#endif

#endif /* __LIST_H_INCLUDED__ */

