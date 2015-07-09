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
#include "database.h"
#include "stringUtil.h"

// ----------------------------------------------------------------
// mysql result handling

SQL_RESULT *sqlCreateResult(char *tableName, MYSQL_RES *res) {
    SQL_RESULT *rp = (SQL_RESULT *) calloc(1, sizeof(SQL_RESULT));
    rp->tableName = (char *) strdup(tableName);
    rp->res = res;
    // Set up field info
    rp->numFields = mysql_num_fields(rp->res);
    //enum_field_types rp->mysqlTypes[rp->numFields];
    MYSQL_FIELD *field;
    for (int i = 0; (field = mysql_fetch_field(rp->res)); i++) {
        rp->mysqlHeaders[i] = field->name;
        rp->mysqlTypes[i] = field->type;
    }
    return rp;
}

int sqlGetRow(SQL_RESULT *rp) {
    MYSQL_ROW row;
    row = mysql_fetch_row(rp->res);
    if (row) {
        _updateSqlFields(rp->tableName, rp->mysqlHeaders, rp->mysqlTypes, rp->numFields, &row);
        return SQL_OK;
    }
    return SQL_EOF;
}

// ----------------------------------------------------------------
// VAR related functions

int decodeMysqlType(VAR *variable, enum enum_field_types mysqlType, char *value) {
	clearVarValues(variable);
	variable->type = fieldConvertMysql2Var(mysqlType);
	fillVarDataTypes(variable, value);
	return 0;
}

int fieldConvertMysql2Var(enum enum_field_types mysqlType) {
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

void _updateSqlFields(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP) {
	MYSQL_ROW row = *rowP;
	int i = 0;
	for (i = 0; i < numFields; i++) {
		char qualifiedName[256];
		sprintf(qualifiedName, "%s.%s", qualifier, mysqlHeaders[i]);
		updateSqlVar(qualifiedName, mysqlTypes[i], row[i]);
//printf("HDR=[%s.%s]:[%s]\n", qualifier, mysqlHeaders[i], row[i]);
	}
}
void updateSqlVar(char *qualifiedName, enum enum_field_types mysqlType, char *value) {
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

// ----------------------------------------------------------------
// General stuff

int _isSqlFieldName(char *fieldName, char *tableName) {
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


// This is the common code that used to be in @get and @each - now called from control() and wordDatabaseGet()
MYSQL_RES *doSqlSelect(int ix, char *defaultTableName, char **givenTableName, int maxRows) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    // @@TODO refactor this because it shares 90% of its code with @each
    int skipCode = 0;

    // Get the given table name that we want to get
    char *ta = args;
    char *tg = *givenTableName;
    while ((*ta) && (*ta != ' ') && (*ta != '.'))	// Find ' ' or '.' which terminates the tablename;
        *tg++ = *ta++;

    char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
    sprintf(query, "select * from %s",  *givenTableName);				// set a default query
    // Is there more than just the table name?
    if (*ta) {
        ta++;
        skipCode = buildMysqlQuerySelect(query, ta, defaultTableName, *givenTableName);		// build a complex query
    }

    // Append 'limit n'
    char *s = (char *) calloc(1, 4096);
    sprintf(s, " LIMIT %d", maxRows);
    strcat(query, s);
    free(s);
    // Do the query
//printf("\n\n[%s]\n\n", query);
//die("kkk");
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
    free(query);
    return res;
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
		if ((!_isSqlFieldName(varValue, givenTableName)) && (varValue[0] != '\'') && (varValue[strlen(varValue)] != '\'')) {
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
