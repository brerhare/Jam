#define LIST_MYSQL_TYPE     0

typedef struct {
    MYSQL_RES *res;
    MYSQL_ROW row;
} LIST_DATA_MYSQL;

typedef struct {
    char *name;
    int type;
    linkList *list;
} LIST_HOLDER;
#define MAX_LIST_HOLDER 10000
extern LIST_HOLDER *listHolder[MAX_LIST_HOLDER];

LIST_HOLDER *getListByName(char *name);
