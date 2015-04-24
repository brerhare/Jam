/*
 * @@TODO Fix the priority of assignment to non-qualified vars. This worked well until I stuck in a @GET inside a @EACH loop - it obeys the @EACH x
 */

#include <stdio.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "util.h"

using namespace std;

#define MAX_SQL_QUERY_LEN 1024
MYSQL *conn = NULL;

typedef struct {
	char *command;
	char *args;
	char *trailer;
} JAM;
#define MAX_JAM 10000
JAM *jam[MAX_JAM];
int jamIx = 0;

char *tableStack[MAX_JAM];

#define VAR_STRING    0
#define VAR_NUMBER    1
#define VAR_DECIMAL2  2
#define VAR_DATE      3
#define VAR_TIME      4
#define VAR_DATETIME  5

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
VAR *var[MAX_VAR];

char *readTemplate(string fname);
char *curlies2JamArray(char *tplPos);
JAM *initJam();
int genHtml(int startIx, MYSQL_ROW *row, char *tableName);
void emit(char *line);
void die(const char *errorString);
int openDB(char *name);
void closeDB();
void setFieldValues(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP);
VAR *findVar(char *qualifiedName);
VAR *findVarTryBothWays(char *name);
void fillVarDataTypes(VAR *var, char *value);
void updateTableVar(char *qualifiedName, enum enum_field_types mysqlType, char *value);
void updateNonTableVar(char *qualifiedName, char *value, int type);
int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value);
int fieldConvertMysql2Jam(enum enum_field_types mysqlType);
void clearVarValues(VAR *varStruct);
int buildMysqlQuerySelect(char *query, char *args, char *currentTableName);
void jamDump();

int main(int argc, char *argv[]) {
	if (argc < 2) {
		die("no template file given to process");
	}
	string fname = argv[1];

	// Read in template
	//string fname = templatePath + "ex1.html";
	//string fname = templatePath + "ex_customer_area.html";
	char *tpl = readTemplate(fname);

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
	genHtml(0, NULL, NULL);

	free(tpl);
	if (conn)
		closeDB();
jamDump();
	exit(0);
}

int genHtml(int startIx, MYSQL_ROW *row, char *tableName) {
	int ix = startIx;
	char *tmp = (char *) calloc(1, 4096);
	while (jam[ix]) {
		char *cmd = jam[ix]->command;
		char *args = jam[ix]->args;
//		--------------------------------
		if (!(strcmp(cmd, "@!begin"))) {
//		--------------------------------
			emit(jam[ix]->trailer);
//		--------------------------------
		} else if (!(strcmp(cmd, "@list"))) {
//		--------------------------------
			MYSQL_RES *res;
			MYSQL_ROW row;
			char sep = ' ';
			getWord(tmp, args, 1, &sep);
			if (*tmp) {
				char *listWhat = strdup(tmp);
				if (!strcmp(listWhat, "databases")) {
					char *dbName = strdup("");
					if (openDB(dbName) < 0) {
						die(mysql_error(conn));
					}
					mysql_query(conn,"show databases");
					res = mysql_store_result(conn);
					while (row = mysql_fetch_row(res)) {
						if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
							continue;
						sprintf(tmp, "%s <br>", row[0]);
						emit(tmp);
					}
					closeDB();
				} else if (!strcmp(listWhat, "tables")) {
					mysql_query(conn,"show tables");
					res = mysql_store_result(conn);
					while (row = mysql_fetch_row(res)) {
						if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
							continue;
						sprintf(tmp, "%s <br>", row[0]);
						emit(tmp);
					}
				}
			} else {
				die("list what?");
			}
			emit(jam[ix]->trailer);
//		--------------------------------
		} else if (!(strcmp(cmd, "@describe"))) {
//		--------------------------------
			MYSQL_RES *res;
			MYSQL_ROW row;
			char sep = ' ';
			getWord(tmp, args, 1, &sep);
			if (*tmp) {
				char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
				char *line = (char *) calloc(1, 4096);
				sprintf(query, "desc %s", args);
				mysql_query(conn,query);
				res = mysql_store_result(conn);
				int numFields = mysql_num_fields(res);
				emit("<table border=1 cellpadding=3 cellspacing=0 style='border: 1pt solid #abdbd7; border-Collapse: collapse'>");
				emit("<tr><td> Field </td><td> Type </td><td> Empty allowed? </td><td> Key </td><td> Default value </td><td> Extra </td></tr>");
				while (row = mysql_fetch_row(res)) {
					strcpy(line, "<tr>");
					for (int i = 0; i < numFields; i++) {
						sprintf(tmp, "<td> %s </td>", row[i]);
						strcat(line, tmp);
					}
					strcat(line, "</tr>");
					emit(line);
				}
				emit("</table>");
				free(query);
				free(line);
			} else {
				die("describe what?");
			}
			emit(jam[ix]->trailer);
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@database"))) {
//		-----------------------------------------
			if (strlen(args) == 0) {
				printf("missing database name\n");
				exit(1);
			}
			if (openDB(args) < 0) {
				die(mysql_error(conn));
			}
			emit(jam[ix]->trailer);
//		-------------------------------------
		} else if (!(strcmp(cmd, "@each"))) {
//		-------------------------------------
//{@each stock_customer}
//{@each stock_customer.stock_area_id = id}
//{@each stock_customer.stock_area_id = area.id}
//{@each stock_customer stock_area_id = }
//{@each stock_customer whose stock_area_id = }
			char *givenTableName = (char *) calloc(1, 4096);
			char *givenFieldName = NULL;
			char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
			// Get the given table name that we want each of
			char *ta = args;
			char *tg = givenTableName;
			while ((*ta) && (*ta != ' ') && (*ta != '.'))	// Find ' ' or '.' which terminates the tablename;
				*tg++ = *ta++;
			sprintf(query, "select * from %s",  givenTableName);				// set a default query
			// Is there more than just the table name?
			if (*ta) {
				ta++;
				buildMysqlQuerySelect(query, ta, tableName);		// build a complex wuery
			}
strcat(query, " LIMIT 100");
			// Do the query
			MYSQL_RES *res;
			MYSQL_ROW row;
			if (mysql_query(conn, query)) {
				die(mysql_error(conn));
			}
			res = mysql_store_result(conn);
  			if (!res) {
				sprintf(tmp, "Couldn't get results set: %s\n", mysql_error(conn));
				die(tmp);
			}
			int numFields = mysql_num_fields(res);
			char *mysqlHeaders[numFields];
			enum enum_field_types mysqlTypes[numFields];
			MYSQL_FIELD *field;
			for (int i = 0; (field = mysql_fetch_field(res)); i++) {
				mysqlHeaders[i] = field->name;
				mysqlTypes[i] = field->type;
			}
			while ((row = mysql_fetch_row(res)) != NULL) {
				// Recurse - start an each-end loop
				emit(jam[ix]->trailer);		
				setFieldValues(givenTableName, mysqlHeaders, mysqlTypes, numFields, &row);
//printf("GOING with tablename=[%s]<br>\n", givenTableName);
				genHtml((ix + 1), &row, givenTableName);
//printf("BACK\n");
			}
			// Finished. Now emit the loops' trailer and make it current, so we will immediately advance past it
			while (jam[ix] && (strcmp(jam[ix]->command, "@end") || (strcmp(jam[ix]->args, givenTableName)))) {
				ix++;
			}
			if (jam[ix]) {
				emit(jam[ix]->trailer);
			}
			mysql_free_result(res);
			free(query);
//		------------------------------------
		} else if (!(strcmp(cmd, "@end"))) {
//		------------------------------------
			// Return from an each-end loop
//printf("RETURNING\n");
			return(0);
//		----------------------------------------
		} else if (!(strcmp(cmd, "@include"))) {
//		----------------------------------------
			char *buf = NULL;
			std::ifstream includeFile (args, std::ifstream::binary);
			if (!includeFile){
				sprintf(tmp, "cant @include %s", args);
				die(tmp);
			}
			includeFile.seekg (0, includeFile.end);
			int length = includeFile.tellg();
			includeFile.seekg (0, includeFile.beg);
			buf = (char *) calloc(1, length+1);
			if (!buf) {
				sprintf(tmp, "cant calloc memory to @include %s", args);
				die(tmp);
			}
			// Emit any pre-tags
			if (strstr(args, ".css"))
				emit("<style>");
			includeFile.read (buf,length);
			buf[length] = 0;
			includeFile.close();
			if (!includeFile) {
				sprintf(tmp, "error: not the whole of %s could be read", args);
				die(tmp);
			}
			emit(buf);
			// Emit any post-tags
			if (strstr(args, ".css"))
			emit("</style>");
			emit(jam[ix]->trailer);
//		--------------------------------------
		} else if (!(strcmp(cmd, "@count"))) {
//		--------------------------------------
			char sep = ' ';
			getWord(tmp, args, 1, &sep);
			char *countFieldName = strdup(tmp);
			if (!countFieldName)
				die("Cant count a nonexistent field");
			char *countFieldAs = NULL;
			getWord(tmp, args, 2, &sep);
			if (!(strcmp(tmp, "as"))) {
				getWord(tmp, args, 3, &sep);
				countFieldAs = strdup(tmp);
			}
			// Lookup the variable we want to count. It might be qualified, but if it isnt then qualify it with the current table name
			VAR *varToCount = NULL;
			char *varToCountQualifiedName = (char *) calloc(1, 512);
			if (strchr(countFieldName, '.'))
				strcpy(varToCountQualifiedName, countFieldName);
			else
				sprintf(varToCountQualifiedName, "%s.%s", tableName, countFieldName);
			varToCount = findVar(varToCountQualifiedName);
			if (!varToCount) {
				sprintf(tmp, "variable to count doesnt exist :( Was looking for tableName=[%s] and countFieldName=[%s]\n",tableName, countFieldName);
				die(tmp);
			}
			// Lookup the variable we want to count as (into). If it is unnamed prepend '.count' to it
			VAR *varToCountAs = NULL;
			if (!countFieldAs)
				sprintf(tmp, "count.%s", countFieldName);
			else
				sprintf(tmp, "%s", countFieldAs);
			varToCountAs = findVar(tmp);
			if (!varToCountAs) {
				char *val = "0";
				updateNonTableVar(tmp, val, VAR_NUMBER);
				varToCountAs = findVar(tmp);
				varToCountAs->source = strdup("count");
				varToCountAs->debugHighlight = 2;
			}
			varToCount->debugHighlight = 2;
			// Do the count	@@TODOCALC
			varToCountAs->numberValue++;
			free(varToCountAs->portableValue);
			sprintf(tmp, "%ld", varToCountAs->numberValue);
			varToCountAs->portableValue = strdup(tmp);
			// Wrap up
			free(countFieldName);
			free(countFieldAs);
			free(varToCountQualifiedName);
			emit(jam[ix]->trailer);
//		------------------------------------
		} else if (!(strcmp(cmd, "@sum"))) {
//		------------------------------------
			char sep = ' ';
			getWord(tmp, args, 1, &sep);
			char *sumFieldName = strdup(tmp);
			if (!sumFieldName)
				die("Cant sum a nonexistent field");
			char *sumFieldAs = NULL;
			getWord(tmp, args, 2, &sep);
			if (!(strcmp(tmp, "as"))) {
				getWord(tmp, args, 3, &sep);
				sumFieldAs = strdup(tmp);
			}
			// Lookup the variable we want to sum. It might be qualified, but if it isnt then qualify it with the current table name
			VAR *varToSum = NULL;
			char *varToSumQualifiedName = (char *) calloc(1, 512);
			if (strchr(sumFieldName, '.'))
				strcpy(varToSumQualifiedName, sumFieldName);
			else
				sprintf(varToSumQualifiedName, "%s.%s", tableName, sumFieldName);
			varToSum = findVar(varToSumQualifiedName);
			if (!varToSum) {
				sprintf(tmp, "variable to sum doesnt exist :( Was looking for tableName=[%s] and sumFieldName=[%s]\n",tableName, sumFieldName);
				die(tmp);
			}
			// Lookup the variable we want to sum as (into). If it is unnamed prepend '.sum' to it
			VAR *varToSumAs = NULL;
			if (!sumFieldAs)
				sprintf(tmp, "sum.%s", sumFieldName);
			else
				sprintf(tmp, "%s", sumFieldAs);
			varToSumAs = findVar(tmp);
			if (!varToSumAs) {
				char *val = "0";
				updateNonTableVar(tmp, val, varToSum->type);
				varToSumAs = findVar(tmp);
				varToSumAs->source = strdup("sum");
				varToSumAs->debugHighlight = 3;
			}
			varToSum->debugHighlight = 3;
			// Do the sum	@@TODOCALC
			if (varToSum->type == VAR_NUMBER) {
				varToSumAs->numberValue += varToSum->numberValue;
				free(varToSumAs->portableValue);
				sprintf(tmp, "%ld", varToSumAs->numberValue);
				varToSumAs->portableValue = strdup(tmp);
			}
			else if (varToSum->type == VAR_DECIMAL2) {
				varToSumAs->decimal2Value += varToSum->decimal2Value;
				free(varToSumAs->portableValue);
				sprintf(tmp, "%.2lf", varToSumAs->decimal2Value);
				varToSumAs->portableValue = strdup(tmp);
			}
			// Wrap up
			free(sumFieldName);
			free(sumFieldAs);
			free(varToSumQualifiedName);
			emit(jam[ix]->trailer);
//		---------------------------
		} else if (cmd[0] != '@') {
//		---------------------------
			VAR *variable = NULL;
			variable = findVarTryBothWays(cmd);
			if (variable) {
				emit(variable->portableValue);
				// Clear if 'count.'
				if ((variable->source) && (!strcmp(variable->source, "count"))) {
					variable->numberValue = 0;
					free(variable->portableValue);
					variable->portableValue = strdup("0");
				}
				// Clear if 'sum.'
				if ((variable->source) && (!strcmp(variable->source, "sum"))) {
					if (variable->type == VAR_NUMBER) {
						variable->numberValue = 0;
						free(variable->portableValue);
						variable->portableValue = strdup("0");
					} else if (variable->type == VAR_DECIMAL2) {
						variable->decimal2Value = 0;
						free(variable->portableValue);
						variable->portableValue = strdup("0.00");
					}
				}
			}
			else {
				sprintf(tmp, "[%s]", cmd);
				emit(tmp);
			}
			emit(jam[ix]->trailer);		
//		--------
		} else {
//		--------
			emit(jam[ix]->trailer);
		}

		// Next
		ix++;
	}
	free(tmp);
}

VAR *findVarTryBothWays(char *name) {
	// Get the stored value. Mysql fields are always stored fully qualified. Others might have no or many levels of qualifier
	VAR *variable = NULL;
	variable = findVar(name);
	if (!variable) {
		// If not found, it might be a non-qualified variable in the form of '(null).something' (no qualifier)
		char *p = strchr(name, '.');
		if ((p) && (*(++p)))
			variable = findVar(p);
	}
	return variable;
}

VAR *findVar(char *name) {
	for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
		if (!(var[i]))
			break;	
		if (!strcmp(var[i]->name, name)) {
			return var[i];
		}
	}
	return NULL;
}
void fillVarDataTypes(VAR *var, char *value) {
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);
	if (var->type == VAR_DATE)
		var->dateValue = safeValue;
	else if (var->type == VAR_TIME)
		var->timeValue = safeValue;
	else if (var->type == VAR_DATETIME)
		var->datetimeValue = safeValue;
	else if (var->type == VAR_DECIMAL2) {
		if (safeValue)
			var->decimal2Value = atof(safeValue);
	} else if (var->type == VAR_NUMBER) {
		if (safeValue)
			var->numberValue = atol(safeValue);
	} else
		var->stringValue = safeValue;
	if (safeValue)
		var->portableValue = strdup(safeValue);
}

void updateNonTableVar(char *qualifiedName, char *value, int type) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateNonTableVar\n");
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);
	VAR *seekVar = findVar(qualifiedName);
	if (!seekVar) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->type = type;
		clearVarValues(newVar);
		fillVarDataTypes(newVar, safeValue);
//printf("NON-TABLE-> NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
		for (int i = 0; i < MAX_VAR; i++) {
			if (!var[i]) {
				var[i] = newVar;
				return;
			}
		}
	} else {
		for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
			if (!var[i])
				break;	
			if (!strcmp(var[i]->name, qualifiedName)) {
				fillVarDataTypes(var[i], value);
				break;
			}
		}
	}
}

void updateTableVar(char *qualifiedName, enum enum_field_types mysqlType, char *value) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateTableVar\n");
	VAR *seekVar = findVar(qualifiedName);
	if (!seekVar) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->source = strdup("mysql");
		int ret = decodeMysqlType(newVar, mysqlType, value);
//printf("TABLE-> NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
		for (int i = 0; i < MAX_VAR; i++) {
			if (!var[i]) {
				var[i] = newVar;
				return;
			}
		}
	} else {
		for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
			if (!var[i])
				break;	
			if (!strcmp(var[i]->name, qualifiedName)) {
				int ret = decodeMysqlType(var[i], mysqlType, value);
				break;
			}
		}
	}
}

void clearVarValues(VAR *varStruct) {
	if (!varStruct)
		return;
	// Free any existing value
	if (varStruct->portableValue) free(varStruct->portableValue);
	if (varStruct->stringValue) free(varStruct->stringValue);
	if (varStruct->dateValue) free(varStruct->dateValue);
	if (varStruct->timeValue) free(varStruct->timeValue);
	if (varStruct->datetimeValue) free(varStruct->datetimeValue);
	varStruct->portableValue = NULL;
	varStruct->stringValue = NULL;
	varStruct->dateValue = NULL;
	varStruct->timeValue = NULL;
	varStruct->datetimeValue = NULL;
	varStruct->numberValue = 0;
	varStruct->decimal2Value = 0;
}

int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value) {
	clearVarValues(var);
	var->type = fieldConvertMysql2Jam(mysqlType);
	fillVarDataTypes(var, value);
	return 0;
}

int fieldConvertMysql2Jam(enum enum_field_types mysqlType) {
	int type = -1;
	switch (mysqlType) {
		case MYSQL_TYPE_DATE:
			type = VAR_DATE;
			break;
		case MYSQL_TYPE_TIME:
			type = VAR_TIME;
		case MYSQL_TYPE_DATETIME:
		case MYSQL_TYPE_TIMESTAMP:
			type = VAR_DATETIME;
			break;
		case MYSQL_TYPE_DECIMAL:
		case MYSQL_TYPE_NEWDECIMAL:
			type = VAR_DECIMAL2;
			break;
	}
	if (type == -1) {
		if (IS_NUM(mysqlType)) {
			type = VAR_NUMBER;
		} else {
			type = VAR_STRING;
		}
	}
	return type;
}

void setFieldValues(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP) {
	MYSQL_ROW row = *rowP;
	int i = 0;
	for (i = 0; i < numFields; i++) {
		char qualifiedName[256];
		sprintf(qualifiedName, "%s.%s", qualifier, mysqlHeaders[i]);
		updateTableVar(qualifiedName, mysqlTypes[i], row[i]);
//printf("HDR=[%s.%s]:[%s]\n", qualifier, mysqlHeaders[i], row[i]);
	}
}

int buildMysqlQuerySelect(char *query, char *args, char *currentTableName) {
	char *selectorField = NULL;
	char *operand = NULL;
	char *externalField = NULL;
	char *tmp = (char *) calloc(1, 4096);
	char sep = ' ';
	int wdNum = 1;
	selectorField = strTrim(getWordAlloc(args, wdNum, &sep));	// try for the field selector
	if (!selectorField)
		die("table name given for @each but no field selector");
	if (!strcmp(selectorField, "whose")) {
		wdNum++;
		free(selectorField);
		selectorField = strTrim(getWordAlloc(args, wdNum, &sep));
		if (!selectorField)
			die("table name given for @each but no field selector after 'whose'");
	}
	operand = strTrim(getWordAlloc(args, ++wdNum, &sep));	// try for the operand '=' '>' '<' etc
	if (!operand)
		die("no operand given for @each");
	externalField = strTrim(getWordAlloc(args, ++wdNum, &sep));	// try for the external field, containing the value to look for
	if (!externalField)
		die("no external field given for @each");
//sprintf(tmp, "[%s][%s][%s] fullargs=[%s] and currenttable=[%s]", selectorField, operand, externalField, args, currentTableName); die(tmp);
	sprintf(tmp, "%s.%s", currentTableName, externalField);
	VAR *variable = NULL;
	variable = findVarTryBothWays(tmp);
	// Finally build the query string
	sprintf(tmp, " where %s %s %s", selectorField, operand, variable->portableValue);
	strcat(query, tmp);
	free(selectorField);
	free(operand);
	free(externalField);
	free(tmp);
//sprintf(tmp, "wd=[%s]", wd); die(tmp);
	return 0;
}

char *curlies2JamArray(char *tplPos) {
	char *startCurly = strchr(tplPos, '{');
	if (startCurly == NULL)
		return NULL;
	char *endCurly = strchr(tplPos, '}');
	if (endCurly == NULL) {
		printf("an opening '{' has a missing closing '}'\n");
		exit(1);
	}
	char *wd = (char *) malloc(endCurly - startCurly);
	int wdLen = (endCurly - startCurly - 1);
	memcpy(wd, (startCurly+1), wdLen);
	wd[wdLen] = 0;
	if (strchr(wd, '{')) {
		printf("there is an opening '{' within a '{}'\n");
		exit(1);
	}
//printf("startCurly=%p, endCurly=%p, wdLen=%d, wd=%s\n", startCurly, endCurly, wdLen, wd);

	jam[jamIx] = (JAM *) calloc(1, sizeof(JAM));

	char *buf = (char *) calloc(1, strlen(wd)+1);
	char sep = ' ';
	getWord(buf, wd, 1, &sep);

	// Get the current table from the top of stack for unqualified variables
	if ((buf[0] != '@') && (!(strchr(buf, '.')))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if ((tableStack[i] == NULL) && (i > 0)) {
				i--;
//printf("USING STACK: [%s]", tableStack[i]);
				char *newBuf = (char *) calloc(1, 4096);
				sprintf(newBuf, "%s.%s", tableStack[i], buf);
				free(buf);
				buf = newBuf;
//printf(" ... storing variable [%s]\n", buf);
				break;
			}
		}
	}
	for (char *p = buf; *p; ++p) *p = tolower(*p);
	jam[jamIx]->command = buf;

	buf = (char *) calloc(1, strlen(wd)+1);
	if (char *p = strchr(wd, ' ')) {
	 jam[jamIx]->args = strdup(p+1);
	}
//printf("SETTING [%s]=[%s]\n", jam[jamIx]->command, jam[jamIx]->args);

	char *trailer = strdup(endCurly + 1);
	char *c = strchr(trailer, '{');
	if (c)
		*c = 0;
	jam[jamIx]->trailer = strdup(trailer);

	// Push the table onto the stack at every start of loop
	if (!(strcmp(jam[jamIx]->command, "@each"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (tableStack[i] == NULL) {
				char *p = (char *) calloc(1, 4096);
				getWord(p, jam[jamIx]->args, 1, &sep);
				tableStack[i] = p;
//printf("STACK: [%s]\n", tableStack[i]);
				break;
			}
		}
	}
	// Pop the table off the stack at every end of loop
	if (!(strcmp(jam[jamIx]->command, "@end"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if ((tableStack[i] == NULL) && (i > 0)) {
				i--;
//printf("POP: [%s]\n", tableStack[i]);
				jam[jamIx]->args = strdup(tableStack[i]);
				free(tableStack[i]);
				tableStack[i] = NULL;
				break;
			}
		}
	}
	free(trailer);
	return (endCurly+1);
}

char *readTemplate(string fname){
	char *buf = NULL;
	std::ifstream html (fname.c_str(), std::ifstream::binary);
	if (!html){
		std::cout << "error: cant open file " << fname << endl;
		die("");
	}
	html.seekg (0, html.end);
	int length = html.tellg();
	html.seekg (0, html.beg);

	buf = (char *) calloc(1, 9 +length+1);
	if (!buf) {
		std::cout << "error: cant calloc memory " << fname << endl;
		exit(1);
	}
	strcpy(buf, "{@!begin}");
	html.read (buf+9,length);
	buf[9+length] = 0;
	html.close();
	if (!html) {
		std::cout << "error: only " << html.gcount() << " could be read" << endl;
		exit(1);
	}
//	printf("-->%s<--\n", buf);
	return buf;
}

int openDB(char *name) {
	char *server = "localhost";
	char *user = "root";
	char *password = "Wole9anic-"; /* set me first */
	char *database = name;
	conn = mysql_init(NULL);
	if (!mysql_real_connect(conn, server, user, password, database, 0, NULL, 0)) {
		return -1;
	}
	return 0;
}

void closeDB() {
	mysql_close(conn);
}

int main2() {
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_ROW row;
	char *server = "localhost";
	char *user = "root";
	char *password = "Wole9anic-"; /* set me first */
	char *database = "mysql";
	conn = mysql_init(NULL);
	if (!mysql_real_connect(conn, server, user, password, database, 0, NULL, 0)) {
		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);
	}
	/* send SQL query */
	if (mysql_query(conn, "show tables")) {
	fprintf(stderr, "%s\n", mysql_error(conn));
	exit(1);
	}
	res = mysql_use_result(conn);
	/* output table name */
	printf("MySQL Tables in mysql database:\n");
	while ((row = mysql_fetch_row(res)) != NULL)
	printf("%s \n", row[0]);
	/* close connection */
	mysql_free_result(res);
	mysql_close(conn);
}


void emit(char *line) {
	printf("%s", line);
}

void die(const char *errorString) {
	//fprintf(stderr, "%s\n", errorString);
	fprintf(stdout, "%s\n", errorString);
	exit(1);
}

void jamDump() {
	char *tmp = (char *) calloc(1, 4096);
	printf("<br><br><div style='font-size:11px;color:#ffffff;background-color:#1b2426'>");
	for (int i = 0; i < MAX_JAM; i++) {
		if (jam[i] == NULL)
			break;
		//printf("%02d JAMDUMP: %s >>>>>%s<<<<<\n\n\n", i, jam[i]->command, jam[i]->trailer);
		printf("%02d JAMDUMP %s : %s<br>", i, jam[i]->command, jam[i]->args);
	}
	printf("<hr>");
	for (int i = 0; i < MAX_VAR; i++) {
		if (var[i] == NULL)
			break;

		printf("<span");
		if (var[i]->debugHighlight == 1) printf(" style='color:#e18d88'");
		if (var[i]->debugHighlight == 2) printf(" style='color:yellow;'");
		if (var[i]->debugHighlight == 3) printf(" style='color:orange;'");
		printf(">");

		*tmp = 0;
		if (var[i]->source)
			sprintf(tmp, " : source %s", var[i]->source);
		if (var[i]->type == VAR_STRING)
			printf("%02d VARDUMP %s : VAR_STRING %s %s<br>", i, var[i]->name, var[i]->stringValue, tmp);
		if (var[i]->type == VAR_NUMBER)
			printf("%02d VARDUMP %s : VAR_NUMBER %ld %s<br>", i, var[i]->name, var[i]->numberValue, tmp);
		if (var[i]->type == VAR_DECIMAL2)
			printf("%02d VARDUMP %s : VAR_DECIMAL2 %.2f %s<br>", i, var[i]->name, var[i]->decimal2Value, tmp);
		printf("</span>");
	}
	printf("<br>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:yellow;'>count </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:orange;'>sum </span>");
	printf("<br>");
	printf("<br>");
	printf("</div>");
	free(tmp);
}

