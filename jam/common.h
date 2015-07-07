using namespace std;

extern char *startJam;
extern char *endJam;

extern int literal;

#define round(x) ((x)>=0?(long)((x)+0.5):(long)((x)-0.5))

#define MAX_ARGS 4096
#define MAX_SQL_QUERY_LEN 1024

extern MYSQL *conn;

typedef struct {
	char *rawData;
	char *command;
	char *args;
	char *trailer;
} JAM;
#define MAX_JAM 10000
extern JAM *jam[MAX_JAM];
extern int jamIx;

extern char *tableStack[MAX_JAM];

#define VAR_STRING     0
#define VAR_NUMBER     1
#define VAR_INCREMENT  2
#define VAR_DECIMAL2   3
#define VAR_DATE       4
#define VAR_TIME       5
#define VAR_DATETIME   6

typedef struct {
	char *name;
	int type;
	char *source;	// mysql, count, sum etc
	char *portableValue;
	char *stringValue;
	long numberValue;
	double decimal2Value;
	char *dateValue;
	char *timeValue;
	char *datetimeValue;
	int debugHighlight;
} VAR;
#define MAX_VAR 10000
extern VAR *var[MAX_VAR];

char *readTemplate(char *fname);
char *curlies2JamArray(char *tplPos);
JAM *initJam();
int control(int startIx, char *tableName);
void emit(char *line);
void die(const char *errorString);
int openDB(char *name);
void closeDB();
void setFieldValues(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP);
VAR *findVarStrict(char *qualifiedName);
VAR *findVarLenient(char *name, char *prefix);
void fillVarDataTypes(VAR *var, char *value);
void updateTableVar(char *qualifiedName, enum enum_field_types mysqlType, char *value);
void updateNonTableVar(char *qualifiedName, char *value, int type);
int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value);
int fieldConvertMysql2Jam(enum enum_field_types mysqlType);
void clearVarValues(VAR *varStruct);
int buildMysqlQuerySelect(char *query, char *args, char *currentTableName, char *givenTableName);
int isMysqlFieldName(char *fieldName, char *tableName);
int isCalculation(char *str);
char *calculate(char *str);
char *expandFieldsInString(char *str, char *tableName);
void jamDump();
