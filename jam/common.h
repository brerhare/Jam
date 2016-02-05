#ifndef __COMMON_H_INCLUDED
#define __COMMON_H_INCLUDED

#include </usr/include/mysql/mysql.h>
#include <curl/curl.h>

using namespace std;

extern char *startJam;
extern char *endJam;

extern int literal;

extern CURL *curl;

#define MAX_INCLUDE 512

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

typedef struct {
	int stream;
	char *templateStr;
} JAMBUILDER;
#define STREAMOUTPUT_STD 1
#define STREAMOUTPUT_JS  2
extern int outputStream;	// global copy of the active JAMBUILDER stream (eg for emitStd)

extern char *tableStack[MAX_JAM];

#define VAR_STRING     0
#define VAR_NUMBER     1
#define VAR_INCREMENT  2
#define VAR_DECIMAL2   3
#define VAR_DECIMAL4   4
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
extern int LAST_VAR;
extern VAR *var[MAX_VAR];

#define NOTIFY_OK   0x01
#define NOTIFY_FAIL 0x02
#define NOTIFY_INFO 0x04
#define NOTIFY_WARN 0x08
extern unsigned int notify;
extern int notifyStatus;

int control(int startIx, char *defaultTableName);

// Common.c functions
int emitHeader(char *str, ...);
int endHeader();
int emitStd(char *str, ...);
int endStd(int urlEncodingRequired);
int emitJs(char *str, ...);
int endJs(int urlEncodingRequired);
extern char *emitStdPos;
extern int emitStdRemaining;
extern char *emitJsPos;
extern int emitJsRemaining;
extern char *emitScratchPos;
extern int emitScratchRemaining;
extern char *emitScratchBuffer;

int addVar(VAR *newVar);
void deleteVar(VAR *var);
void setVarAsString(char *name, char *value);
void setVarAsNumber(char *name, long value);
void unsetVar(char *name);
char *getVarAsString(char *name);
int isVar(char *name);

VAR *findVarStrict(char *qualifiedName);
VAR *findVarLenient(char *name, char *prefix);void emit(char *line);
void die(const char *errorString);
void jamDump(int which);
void fillVarDataTypes(VAR *var, char *value);
void updateVar(char *qualifiedName, char *value, int type);
void clearVarValues(VAR *varStruct);
char *expandVarsInString(char *str, char *tableName);
int isCalculation(char *str);
char *calculate(char *str);
int scratchJs(char *str, ...);	// @@TODO also need a includeJs() (includes but no dups)
char *urlEncode(char *str);

extern int oobDataRequested;
int oobJamData();

int jamBuilder(char *jamName, char *jEntrypoint, JAMBUILDER *jb);		// process a jam file from within

extern int urlEncodeRequired;

extern char *documentRoot;
extern FILE *scratchJsStream;

extern int cmdSeqnum;	// every @jamcommand has a unique sequence number. Can be used for unique field names in grids

#endif /* __COMMON_H_INCLUDED */
