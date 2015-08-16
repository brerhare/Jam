#ifndef __DATABASE_H_INCLUDED
#define __DATABASE_H_INCLUDED


// ----------------------------------------------------------------
// Mapping of Mysql to Jam types and options. Eg DECIMAL(10,1) = number.1 and NOT NULL = required
char *getSqlType(char *jamType);
char *getSqlOption(char *jamOption);

// ----------------------------------------------------------------
// Mysql result handling

#define SQL_OK  0
#define SQL_EOF -1

#define SQL_MAX_NUMBER_OF_FIELDS    4096    // Allegedly this is mysql's maximum but I dont find it anywhere

typedef struct {
    char *tableName;
    MYSQL_RES *res;
    int numFields;
    char *mysqlHeaders[SQL_MAX_NUMBER_OF_FIELDS];
    enum enum_field_types mysqlTypes[SQL_MAX_NUMBER_OF_FIELDS];
} SQL_RESULT;

SQL_RESULT *sqlCreateResult(char *tableName, MYSQL_RES *res);
int sqlGetRow2Var(SQL_RESULT *rp);

// ----------------------------------------------------------------
// Var related
int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value);
int fieldConvertMysql2Var(enum enum_field_types mysqlType);
void updateSqlVar(char *qualifiedName, enum enum_field_types mysqlType, char *value);   // @@ Rationalise this with the non-sql 'updateVar()'

void _updateSqlFields(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP);
int _isMysqlFieldName(char *fieldName, char *tableName);

// ----------------------------------------------------------------
// Big things

MYSQL_RES *doSqlSelect(int ix, char *defaultTableName, char **givenTableName, int maxRows);
int appendSqlSelectOptions(char *query, char *args, char *currentTableName, char *givenTableName);

int connectDBServer();
int openDB(char *name);
void closeDB();

int doSqlQuery(char *qStr);
MYSQL_RES *getSqlQueryRES();
MYSQL_ROW getSqlROW(MYSQL_RES *result);

char *sqlError();

#endif /* __DATABASE_H_INCLUDED */
