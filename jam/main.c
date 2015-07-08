/*
 * @@TODO Fix the priority of assignment to non-qualified vars. This worked well until I stuck in a @GET inside a @EACH loop - it still obeys the @EACH x
 */
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
#include "wordDatabase.h"
#include "wordMisc.h"
#include "wordControl.h"
#include "database.h"
#include "stringUtil.h"
#include "listUtil.h"

// Common declares start
MYSQL *conn = NULL;
char *startJam = "{{";
char *endJam = "}}";
int literal = 0;
JAM *jam[MAX_JAM];
int jamIx = 0;
char *tableStack[MAX_JAM];
VAR *var[MAX_VAR];
// Common declares end

int main(int argc, char *argv[]) {
	char *argName[MAX_ARGS];
	char *argValue[MAX_ARGS];
	for (int i = 0; i < MAX_ARGS; i++)
		argName[i] = argValue[i] = NULL;
	char *eq = "=";
	int i;
	for (i = 1; i < argc; i++) {
		argName[i-1]  = strTrim(getWordAlloc(argv[i], 1, eq));
		argValue[i-1] = strTrim(getWordAlloc(argv[i], 2, eq));
//		printf("arg [%s] has value [%s]\n", argName[i-1], argValue[i-1]);
	}

	char *tplName = NULL;
	for (i = 0; i < MAX_ARGS; i++) {
		if (!argName[i])
			break;
		if (!(strcmp(argName[i], "template")))
			tplName = strdup(argValue[i]);
		else {
			VAR *assignVar = (VAR *) calloc(1, sizeof(VAR));
			assignVar->name = strdup(argName[i]);
			assignVar->type = VAR_STRING;	// @@FIX!!!!!!
			clearVarValues(assignVar);
			fillVarDataTypes(assignVar, argValue[i]);
//printf("PREFILL-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
			assignVar->source = strdup("prefill");
			assignVar->debugHighlight = 1;
			for (int i = 0; i < MAX_VAR; i++) {
				if (!var[i]) {
					var[i] = assignVar;
					break;
				}
			}
		}
	}

	// Read in template
	char *tpl = readTemplate(tplName);

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
	control(0, NULL);

	free(tpl);
	if (conn)
		closeDB();
jamDump();
	exit(0);
}

int control(int startIx, char *defaultTableName) {
	int ix = startIx;
	char *tmp = (char *) calloc(1, 4096);
	while (jam[ix]) {
		char *cmd = jam[ix]->command;
		char *args = jam[ix]->args;
		char *rawData = jam[ix]->rawData;

		if (!strcmp(cmd, "@literal")) {
			char *space = " ";
			literal = 1;
			if (args)
				getWord(tmp, args, 1, space);
			if (*tmp) {
				if ((!strcmp(tmp, "off")) || (!strcmp(tmp, "0")))
					literal = 0;
			}
		}

		if (literal) {
			char *newStr = strReplaceAlloc(jam[ix]->trailer, "\n", "<br>\n");
			if (newStr) {
				free(jam[ix]->trailer);
				jam[ix]->trailer = newStr;
			}
			newStr = strReplaceAlloc(jam[ix]->trailer, "\t", "&nbsp&nbsp&nbsp&nbsp");
			if (newStr) {
				free(jam[ix]->trailer);
				jam[ix]->trailer = newStr;
			}
			emit(jam[ix]->trailer);
		}

		if (!(strcmp(cmd, "@!begin")))
			emit(jam[ix]->trailer);
		else if (!(strcmp(cmd, "@list")))
			wordDatabaseList(ix, defaultTableName);

//		-----------------------------------------
		else if (!(strcmp(cmd, "@describe"))) {
//		-----------------------------------------
			wordDatabaseDescribe(ix, defaultTableName);
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@database"))) {
//		-----------------------------------------
			wordDatabaseDatabase(ix, defaultTableName);
//		-------------------------------------
		} else if (!(strcmp(cmd, "@each"))) {
//		-------------------------------------
			//wordControlEach(ix, defaultTableName);



//{@each stock_customer}
//{@each stock_customer.stock_area_id = id}
//{@each stock_customer.stock_area_id = area.id}
//{@each stock_customer stock_area_id = }
//{@each stock_customer filter stock_area_id = }
			char *givenTableName = (char *) calloc(1, 4096);
			MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 100);
			MYSQL_ROW row;
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
				control((ix + 1), givenTableName);
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
			free(givenTableName);



//		------------------------------------
		} else if (!(strcmp(cmd, "@get"))) {
//		------------------------------------
    		char *givenTableName = (char *) calloc(1, 4096);
			MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 1);
			MYSQL_ROW row;
			int numFields = mysql_num_fields(res);
			char *mysqlHeaders[numFields];
			enum enum_field_types mysqlTypes[numFields];
			MYSQL_FIELD *field;
			for (int i = 0; (field = mysql_fetch_field(res)); i++) {
				mysqlHeaders[i] = field->name;
				mysqlTypes[i] = field->type;
			}
			row = mysql_fetch_row(res);
			if (row)
				setFieldValues(givenTableName, mysqlHeaders, mysqlTypes, numFields, &row);
			if (jam[ix]) {
				emit(jam[ix]->trailer);
			}
			mysql_free_result(res);
			free(givenTableName);
			//if ((!row) && (skipCode == 1)) {		/@@FIX! make function so this can be shared with database.c
				//free(tmp);
				//return(0);
			//}
//		------------------------------------
		} else if (!(strcmp(cmd, "@end"))) {
//		------------------------------------
			// Return from an each-end loop
//printf("RETURNING\n");
			free(tmp);
			return(0);
//		----------------------------------------
		} else if (!(strcmp(cmd, "@include"))) {
//		----------------------------------------
			wordMiscInclude(ix, defaultTableName);
//		--------------------------------------
		} else if (!(strcmp(cmd, "@count"))) {
//		--------------------------------------
			wordMiscCount(ix, defaultTableName);
//		------------------------------------
		} else if (!(strcmp(cmd, "@sum"))) {
//		------------------------------------
			wordMiscSum(ix, defaultTableName);
//		---------------------------
		} else if (cmd[0] != '@') {
//		---------------------------
/*			data = LHS string
			if '='
				data = RHS string
			expandFields(data)

			if findVar(data)
				result string = var value
			else if isCalculation(data)
				result string = calculate(data)
			else
				result string = expandedData

			if '='
				prepare LHS for receiving result (may not exist yet)
				LHS var = result string
			else
				emit(result string)		*/

			// data = LHS to start with
			char *data = (char *) calloc(1, 4096);
			strcpy(data, cmd);							// eg: [mem.group_count]
//printf("{AWAY:%s and %s}",cmd, data);

			char *resultString = (char *) calloc(1, 4096);
			char *fullLine = (char *) calloc(1, 4096);
			strcpy(fullLine, cmd);
//printf("(CHK:%s and %s)", fullLine, args);
			if (args)
				strcpy(fullLine, rawData);
			strTrim(fullLine);
//printf("T=[%s]\n",fullLine);
			//sprintf(fullLine, "%s%s", cmd, args);			// eg: [mem.group_count][= stock_group.count]
			strcpy(data, fullLine);

			// data = RHS if equals sign
			char *eq = "=";
			if (strchr(fullLine, '=')) {
				char *rhs = strTrim(getWordAlloc(fullLine, 2, eq));		// eg [stock_group.count]
				strcpy(data, rhs);
				free(rhs);
			}

			// Expand any fields to values
			char *expandedData = expandFieldsInString(data, defaultTableName);	// Allocates memory - needs freeing
//printf("\nSTART.... [c=%s][a=%s][f=%s][r=%s][e%s]\n", cmd, args, fullLine, data, expandedData);

			// Either retrieve the data from a field or calculate
			VAR *searchVar = findVarLenient(data, defaultTableName);
			if (searchVar)
				strcpy(resultString, searchVar->portableValue);
			else if (isCalculation(expandedData)) {
				char *calc = calculate(expandedData);
				sprintf(resultString, "%s", calc);						//@TODO: decimals artificially removed!!!!
				free(calc);
			}
			else
				strcpy(resultString, expandedData);

			// If '=' store result in LHS
			if (strchr(fullLine, '=')) {
				// Create / update LHS
				int createNew = 0;
				VAR *assignVar = findVarLenient(cmd, defaultTableName);
				if (!assignVar) {
					createNew = 1;
					assignVar = (VAR *) calloc(1, sizeof(VAR));
					assignVar->name = strdup(cmd);
					assignVar->type = VAR_STRING;	// @@FIX!!!!!!
				}
				clearVarValues(assignVar);
				fillVarDataTypes(assignVar, resultString);
				if (createNew) {
//printf("NEW-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
					assignVar->debugHighlight = 4;
					for (int i = 0; i < MAX_VAR; i++) {
						if (!var[i]) {
							var[i] = assignVar;
							break;
						}
					}
				}
			} else {		// Not an assignment - just emit variable
				emit(resultString);
				// Clear if necessary
				VAR *variable = findVarLenient(cmd, defaultTableName);
				if (variable) {
//printf("RETR-->%s<--\n", variable->name);
//char xx[256]; sprintf(xx, "R[%s]:%s", resultString, variable->portableValue);
//emit(xx);
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
			}
			emit(jam[ix]->trailer);
			free(data);
			free(expandedData);
			free(resultString);
			free(fullLine);
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

VAR *findVarLenient(char *name, char *prefix) {
	// Search using the name as supplied. Mysql fields are always stored fully qualified. Others might have no or many levels of qualifier
	VAR *variable = NULL;
	variable = findVarStrict(name);
	if ((!variable) && (prefix)) {
		// If not found, it might be a non-qualified variable. Stick the current table name (if any) in front of it and try again
		char *tmp = (char *) calloc(1, 4096);
		sprintf(tmp, "%s.%s", prefix, name);
			variable = findVarStrict(tmp);
		free(tmp);
	}
	return variable;
}

VAR *findVarStrict(char *name) {
	for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
		if (!(var[i]))
			break;
		if (!strcmp(var[i]->name, name)) {
			return var[i];
		}
	}
	return NULL;
}
void fillVarDataTypes(VAR *variable, char *value) {
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);	//@@BUG something weird here. The 'if VAR_NUMBER' branch is taken but no value. And valgrind shows a leak
	if (variable->type == VAR_DATE)
		variable->dateValue = safeValue;
	else if (variable->type == VAR_TIME)
		variable->timeValue = safeValue;
	else if (variable->type == VAR_DATETIME)
		variable->datetimeValue = safeValue;
	else if (variable->type == VAR_DECIMAL2) {
		if (safeValue)
			variable->decimal2Value = atof(safeValue);
	} else if (variable->type == VAR_NUMBER) {
		if (safeValue)
			variable->numberValue = atol(safeValue);
	} else
		variable->stringValue = safeValue;
	if (safeValue)
		variable->portableValue = strdup(safeValue);
	else
		variable->portableValue = strdup("");
}

void updateNonTableVar(char *qualifiedName, char *value, int type) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateNonTableVar\n");
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);
	VAR *seekVar = findVarStrict(qualifiedName);
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
				break;
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
	if (safeValue)
		free(safeValue);
}

void updateTableVar(char *qualifiedName, enum enum_field_types mysqlType, char *value) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateTableVar\n");
	VAR *seekVar = findVarStrict(qualifiedName);
	if (!seekVar) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->source = strdup("mysql");
		newVar->debugHighlight = 5;
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

int decodeMysqlType(VAR *variable, enum enum_field_types mysqlType, char *value) {
	clearVarValues(variable);
	variable->type = fieldConvertMysql2Jam(mysqlType);
	fillVarDataTypes(variable, value);
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

int buildMysqlQuerySelect(char *query, char *args, char *currentTableName, char *givenTableName) {
	#define MAX_SUBARGS 1024
	int retval = 0;
	char *selectorField = NULL;
	char *operand = NULL;
	char *externalFieldOrValue = NULL;
	char *tmp = (char *) calloc(1, 4096);
	char *queryBuilder = (char *) calloc(1, 4096);
	char *space = " ";
	int wdNum = 0;
	int firstArg = 1;

	char *subArg[1024];							// array to store all the comma-separated nvp substrings. Eg "product.id = someid"
	for (int i = 0; i < MAX_SUBARGS; i++)
		subArg[i] = NULL;
	// Split the args-by-commas into an nvp array
	for (int i = 0; i < MAX_SUBARGS; i++) {
		char *comma = ",";
		subArg[i] = strTrim(getWordAlloc(args, (i + 1), comma));
		if (!subArg[i])
			break;
	}

	// Deal with each "<filter> a = b" phrase
	for (int i = 0; i < MAX_SUBARGS; i++) {
		if (subArg[i] == NULL) {
			if (firstArg)
				die("No arguments provided to @each/@get");
			break;
		}
		wdNum = 0;

		selectorField = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// try for the field selector (LHS)
		if (!selectorField)
			die("table name given for mysql lookup but no field selector");
		if (!strcmp(selectorField, "filter")) {
			free(selectorField);
			selectorField = strTrim(getWordAlloc(args, ++wdNum, space));
			if (!selectorField)
				die("table name given for mysql lookup but no field selector after 'filter'");
			if (char *p = strchr(selectorField, '.')) {	// remove any irrelevant stuff before the '.'
				free(selectorField);
				selectorField = strdup(p);
			}
		}
		if (!strcmp(selectorField, "order")) {
			sprintf(tmp, " %s", selectorField);
			free(selectorField);
			while (selectorField = strTrim(getWordAlloc(subArg[i], ++wdNum, space))) {
				strcat(tmp, " ");
				strcat(tmp, selectorField);
				free(selectorField);
			}
			strcat(queryBuilder, tmp);
			break;
		}

		if (!strcmp(selectorField, "skip")) {
			retval = 1;	// We will notify the caller of this
			break;
		}

		operand = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// try for the operand '=' '>' '<' 'is' 'is not' etc
		if (!operand)
			die("no operator given for lookup");
		if (!strcasecmp(operand, "is")) {
			free(operand);
			operand = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// we got 'is', the next is either 'not' or out of bounds to us
			if (!strcasecmp(operand, "not")) {
				free(operand);
				operand = strdup("is not");
			}
			else {
				free(operand);
				operand = strdup("is");
				wdNum--;
			}
		}

//@@TODO fix quotes in util.c - until fixed we can only use single quotes here
		getWordIgnoreQuotes = 1;
		externalFieldOrValue = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// try for the external field, containing the value to look for
//printf("\n\n[[[%s]]]\n\n", externalFieldOrValue);
		getWordIgnoreQuotes = 0;
		if (!externalFieldOrValue)
			die("no external field given for lookup");

//sprintf(tmp, "i=%d [%s][%s][%s] fullargs=[%s] and currenttable=[%s]\n", i, selectorField, operand, externalFieldOrValue, args, currentTableName); /*die(tmp);*/
		VAR *variable = NULL;
		char *varValue = NULL;
		variable = findVarLenient(externalFieldOrValue, currentTableName);
//printf("\n[%s][%s]\n", externalFieldOrValue, currentTableName);
		if (variable)
			varValue = strdup(variable->portableValue);
		else
			varValue = strdup(externalFieldOrValue);
		// Quote it if necessary (contains non-numeric chars and isnt already)
		if ((!isMysqlFieldName(varValue, givenTableName)) && (varValue[0] != '\'') && (varValue[strlen(varValue)] != '\'')) {
			int isNum = 1;
			if (strlen(varValue) == 0)
				isNum = 0;
			int numOfMinuses = 0;
			char *p = varValue;
			while (*p) {
				if (*p == '-')
					numOfMinuses++;
				else if ((*p < '0') || (*p > '9'))
					isNum = 0;
				p++;
			}
			if ((!isNum) || (numOfMinuses > 1)) {
				char *newValue = (char *) calloc(1, strlen(varValue) + 3);
				strcpy(newValue, "'");
				strcat(newValue, varValue);
				strcat(newValue, "'");
				free(varValue);
				varValue = newValue;
			}
		}

		sprintf(tmp, " %s %s %s", selectorField, operand, varValue);		// eg "a = b"
		free(varValue);
		if (firstArg)
			strcpy(queryBuilder, " WHERE ");
		else
			strcat(queryBuilder, " AND ");
		firstArg = 0;
		strcat(queryBuilder, tmp);
		free(selectorField); selectorField = NULL;
		free(operand); operand = NULL;
		free(externalFieldOrValue); externalFieldOrValue = NULL;
	}
	strcat(query, queryBuilder);
//die(query);
	free(queryBuilder);
	free(tmp);
	for (int i = 0; i < MAX_SUBARGS; i++)
		free(subArg[i]);
	return retval;
}

int isMysqlFieldName(char *fieldName, char *tableName) {
	char *query = (char *) calloc(1, 4096);
	sprintf(query, "SELECT * FROM %s LIMIT 1", tableName);
//die(query);
	MYSQL_RES *res;
	if (mysql_query(conn, query)) {
		die(mysql_error(conn));
	}
	res = mysql_store_result(conn);
	if (!res) {
		char *tmp = (char *) calloc(1, 4096);
		sprintf(tmp, "Couldn't get results set: %s\n", mysql_error(conn));
		die(tmp);
	}
	int numFields = mysql_num_fields(res);
	MYSQL_FIELD *field;
	for (int i = 0; (field = mysql_fetch_field(res)); i++) {
		if (!strcmp(field->name, fieldName))
			return 1;
	}
	return 0;
}

char *curlies2JamArray(char *tplPos) {
	char *startCurly = strstr(tplPos, startJam);
	if (!startCurly)
		return NULL;
	char *endCurly = strstr(tplPos, endJam);
	if (!endCurly)
		die("Unmatched jam token, an open must have a close");
	int wdLen = (endCurly - startCurly - strlen(startJam));
	char *wd = (char *) malloc(wdLen + 1);
	memcpy(wd, (startCurly + strlen(startJam)), wdLen);
	wd[wdLen] = 0;
	if (strstr(wd, startJam)) {
		die("there is an opening jam token within a token pair");
	}
//printf("\nlen=[%d] wd=[%s]\n", wdLen, wd);

	jam[jamIx] = (JAM *) calloc(1, sizeof(JAM));
	jam[jamIx]->rawData = strdup(wd);

	char *buf = (char *) calloc(1, strlen(wd)+1);
	char *space = " ";
	getWord(buf, wd, 1, space);

/*
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
*/

	for (char *p = buf; *p; ++p) *p = tolower(*p);
	jam[jamIx]->command = buf;

	if (char *p = strchr(wd, ' ')) {
     if (*(p+1))
	 	jam[jamIx]->args = strdup(p+1);
	else
        jam[jamIx]->args = strdup("");
	}
//printf("SETTING [%s]=[%s]\n", jam[jamIx]->command, jam[jamIx]->args);

	char *trailer = strdup(endCurly + strlen(endJam));
	char *c = strstr(trailer, startJam);
	if (c)
		*c = 0;
	jam[jamIx]->trailer = strdup(trailer);

	// Push the table onto the stack at every start of loop
	if (!(strcmp(jam[jamIx]->command, "@each"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (tableStack[i] == NULL) {
				char *p = (char *) calloc(1, 4096);
				getWord(p, jam[jamIx]->args, 1, space);
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
				if (!tableStack[i])
					die("Invalid @end tag found. I dont seem to find an @each for this one");
				jam[jamIx]->args = strdup(tableStack[i]);
				free(tableStack[i]);
				tableStack[i] = NULL;
				break;
			}
		}
	}
	free(trailer);
	free(wd);
	return (endCurly + strlen(endJam));
}

char *readTemplate(char *fname){
	char *buf = NULL;
	std::ifstream html (fname, std::ifstream::binary);
	if (!html){
		std::cout << "error: cant open file " << fname << endl;
		die("");
	}
	html.seekg (0, html.end);
	int length = html.tellg();
	html.seekg (0, html.beg);

	int jamLen = (strlen(startJam) + strlen(endJam));
	char *fakeWord = "@!begin";

	buf = (char *) calloc(1, jamLen + strlen(fakeWord) + length + 1);
	if (!buf) {
		std::cout << "error: cant calloc memory " << fname << endl;
		die("");
	}
	strcpy(buf, startJam);
	strcat(buf, fakeWord);
	strcat(buf, endJam);
	int bufLen = strlen(buf);
	html.read ((buf + bufLen), length);
	buf[bufLen+length] = 0;
	html.close();
	if (!html) {
		std::cout << "error: only " << html.gcount() << " could be read" << endl;
		die("");
	}
//	printf("\n[%d][%d]\n-->%s<--\n", jamLen, length, buf);
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

int isCalculation(char *str) {
	if ((strstr(str, " + ")) || (strstr(str, " - ")) || (strstr(str, " * ")) || (strstr(str, " / "))
	||  (strstr(str, " ^ ")) || (strstr(str, " % ")) || (strchr(str, '(')) || (strchr(str, ')')))
		return 1;
/*	if ((strchr(str, '+')) || (strchr(str, '-')) || (strchr(str, '*')) || (strchr(str, '/'))
	||  (strchr(str, '^')) || (strchr(str, '%')) || (strchr(str, '(')) || (strchr(str, ')')))
		return 1; */
	return 0;
}

// Call the calculator
char *calculate(char *str) {
	// Check if date calculation
	char *space = " ";
	char *wd;
	int wdNum = 0;
	while (1) {
		wd = strTrim(getWordAlloc(str, ++wdNum, space));
		if (!wd)
			break;
		int numOfMinuses = 0;
		char *p = wd;
		while (*p) {
			if (*p == '-')
				numOfMinuses++;
			p++;
		}
		if (numOfMinuses > 1) {
		}
	}

	int scale = 2;
	FILE *fp;
	char *result = (char *) calloc(1, 4096);
	strcpy(result, "0");
	char commandStr[4096];
	sprintf(commandStr, "echo 'scale=%d; %s' | bc", scale, str);
	fp = popen(commandStr, "r");
	if (fp == NULL) {
		printf("calculator failed (1)\n");
	} else {
		if (fgets(result, 4096, fp) != NULL) {
			char *p = strchr(result, '\n');
			if (*p)
				*p = '\0';
		}
		pclose(fp);
	}
//printf("\n *** [%s][%s] *** \n", str, result);
	return result;
}

// Given a string like  [stock.id + ((stock.discount * 100) / stock_tax)+2) + 100]
// Return a string like [123 + ((5.25 * 100) / 20)+2) + 100]
// NEEDS FREEING
char *expandFieldsInString(char *str, char *tableName) {
	char *p = str;
	char *newStr = (char *) calloc(1, 4096);
	char *p2 = newStr;
    char *nonWordChars = " +-*/^%()";

	while (*p) {
		if (!strchr(nonWordChars, *p)) {	// found a word
			char *wd = (char *) calloc(1, 4096);
			char *p3 = wd;
			while ((*p) && (!strchr(nonWordChars, *p)))	// isolate the word
				*p3++ = *p++;
			VAR *variable = NULL;
			variable = findVarLenient(wd, tableName);		// does it name a field?
			if (variable) {
/*
				if (char *pMinus = strchr(variable->portableValue, '-'))
					*pMinus = ' ';	//@@TODO decimals (mult by 100)
				if (char *pDot = strchr(variable->portableValue, '.'))
					*pDot = '\0';	//@@TODO decimals (mult by 100)
*/
				p3 = variable->portableValue;				// yes - replace the word with its value
			}
			else
				p3 = wd;								// no - use the word
			while (*p3)
				*p2++ = *p3++;
			free(wd);
		}
		if ((*p) && (strchr(nonWordChars, *p)))
			*p2++ = *p++;
	}
	return newStr;
}

// Output some content. No sugar added
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
		if (var[i]->debugHighlight == 1) printf(" style='color:#decde3'");
		if (var[i]->debugHighlight == 2) printf(" style='color:yellow;'");
		if (var[i]->debugHighlight == 3) printf(" style='color:orange;'");
		if (var[i]->debugHighlight == 4) printf(" style='color:#a8c968;'");
		if (var[i]->debugHighlight == 5) printf(" style='color:#e28c86;'");
		if (var[i]->debugHighlight == 6) printf(" style='color:cyan;'");
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
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:#decde3;'>prefill </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:yellow;'>count </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:orange;'>sum </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:#a8c968;'>variable </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:#e28c86;'>mysql </span>");
	printf("<span style='margin:3px; padding:2px; color:#000; background-color:cyan;'>unused </span>");
	printf("<br>");
	printf("<br>");
	printf("</div>");
	free(tmp);
}
