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
#include "log.h"

// Mysql server credentials
char *dbServer = "localhost";
char *dbUser = "root";
char *dbPassword = "Wole9anic-";

MYSQL *conn = NULL;
char *connDbName = NULL;

// ----------------------------------------------------------------
// Utility

int isMysqlTable(char *tableName) {
	int ret = 0;
	char *query = (char *) calloc(5, 64096);
	sprintf(query, "SHOW TABLES LIKE '%s'", tableName);
	MYSQL_RES *res;
	if (mysql_query(conn, query) != 0) {
		logMsg(LOGERROR, "isMysqlTable() query error - %s. Your query was [%s]", mysql_error(conn), query);
		return(-1);
	}
	res = mysql_store_result(conn);
	if (!res) {
		char *tmp = (char *) calloc(5, 64096);
		logMsg(LOGERROR, "isMysqlTable() - couldn't get results set: %s\n", mysql_error(conn));
		mysql_free_result(res);
		free(query);
		return(-1);
	}
	if (mysql_num_rows(res) != 0)
		ret = 1;
	mysql_free_result(res);
	free(query);
	return ret;
}

int isMysqlField(char *fieldName, char *tableName) {
	char *query = (char *) calloc(5, 64096);
	sprintf(query, "SELECT * FROM %s LIMIT 1", tableName);
//die(query);
	MYSQL_RES *res;
	if (mysql_query(conn, query) != 0) {
		logMsg(LOGERROR, "isMysqlField() query error - %s. Your query was [%s]", mysql_error(conn), query);
		return(-1);
	}
	res = mysql_store_result(conn);
	if (!res) {
		char *tmp = (char *) calloc(5, 64096);
		logMsg(LOGERROR, "isMysqlField() - couldn't get results set: %s\n", mysql_error(conn));
		mysql_free_result(res);
		free(query);
		return(-1);
	}
	int numFields = mysql_num_fields(res);
	MYSQL_FIELD *field;
	for (int i = 0; (field = mysql_fetch_field(res)); i++) {
		if (!strcmp(field->name, fieldName)) {
			mysql_free_result(res);
			free(query);
			return 1;
		}
	}
	mysql_free_result(res);
	free(query);
	return 0;
}

// ----------------------------------------------------------------
// Mapping

struct SQLMAP {
	char *jam;
	char *sql;
};
SQLMAP sqlTypeMap[] = {
    "string",       "VARCHAR(255)",
    "text",			"TEXT",
	"number", 	    "INT DEFAULT 0",
	"number.0",	    "INT DEFAULT 0",
    "number.1",     "DECIMAL(10,1) DEFAULT 0",
    "number.2",     "DECIMAL(10,2) DEFAULT 0",
    "number.3",     "DECIMAL(10,3) DEFAULT 0",
    "number.4",     "DECIMAL(10,4) DEFAULT 0",
    "date",         "DATE",
    "time",         "TIME",
    "datetime",     "DATETIME",
    NULL,           NULL
};
SQLMAP sqlOptionMap[] = {
    "required",     "NOT NULL",
    "unique",       "UNIQUE",
    NULL,           NULL
};

char *getSqlType(char *jamType) {
    for (int i = 0; sqlTypeMap[i].jam; i++) {
        if (!strcmp(jamType, sqlTypeMap[i].jam))
            return sqlTypeMap[i].sql;
    }
    return NULL;
}

char *getSqlOption(char *jamOption) {
    for (int i = 0; sqlOptionMap[i].jam; i++) {
        if (!strcmp(jamOption, sqlOptionMap[i].jam))
            return sqlOptionMap[i].sql;
    }
    return NULL;
}

// ----------------------------------------------------------------
// mysql result handling

SQL_RESULT *sqlCreateResult(char *tableName, MYSQL_RES *res) {
	if (tableName == NULL) {
		logMsg(LOGERROR, "sqlCreateResult: tableName param is NULL");
		return NULL;
	}
	if (res == NULL) {
		logMsg(LOGERROR, "sqlCreateResult: res param is NULL");
		return NULL;
	}
    SQL_RESULT *rp = (SQL_RESULT *) calloc(1, sizeof(SQL_RESULT));
	if (tableName)
    	rp->tableName = (char *) strdup(tableName);
    else
    	rp->tableName = strdup("");
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

int sqlGetRow2Vars(SQL_RESULT *rp) {
	if (rp == NULL) {
		logMsg(LOGERROR, "sqlGetRow2Vars: param rp is NULL");
		return(-1);
	}
	MYSQL_ROW row = mysql_fetch_row(rp->res);
	if (row) {
		logMsg(LOGMICRO, "sqlGetRow2Vars() found a sql row, copying values to jam format");
		_updateSqlFields(rp->tableName, rp->mysqlHeaders, rp->mysqlTypes, rp->numFields, &row);
		return SQL_OK;
	}
	logMsg(LOGMICRO, "sqlGetRow2Vars() didnt find a sql row, initialising NULL jam format values");

	sqlClearRowVars(rp); // I replaced the next line with this, so now a 'fail' sets id to -1
	//_nullifySqlFields(rp->tableName, rp->mysqlHeaders, rp->mysqlTypes, rp->numFields);

	return SQL_EOF;
}

// Zero all vars applicable to a sql record and set id to -1
int sqlClearRowVars(SQL_RESULT *rp) {
// @@FIX why do we need SQL_RESULT? Look at mysql_list_fields() (see http://ropas.snu.ac.kr/n/lib/manual074.html)
	char *tmp = (char *) calloc(5, 64096);
	_nullifySqlFields(rp->tableName, rp->mysqlHeaders, rp->mysqlTypes, rp->numFields);
	sprintf(tmp, "%s.id", rp->tableName);
	VAR *seekVar = findVarStrict(tmp);
	if (!seekVar) {
		logMsg(LOGERROR, "Cant clear item '%s' because no id var exists", rp->tableName);
		return (-1);
	}
	if (seekVar->portableValue)
		free(seekVar->portableValue);
//	seekVar->portableValue = strdup("-1");
	seekVar->type = VAR_NUMBER;
	fillVarDataTypes(seekVar, "-1");
	free(tmp);
	return 0;
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

logMsg(LOGMICRO, "fillVarDataTypes START");
/*****
unsigned char *p = (unsigned char *) row[i];
if (row[i]) {
while (*p) {
    if ((*p == 145) || (*p == 146) || (*p == 148) || (*p == 151) || (*p == 39) || (*p == 180) || (*p > 127) || (*p == '(') || (*p == ')') || (*p == '%') || (*p < 31) || (*p == '!') || (*p != 'a') )  {
        logMsg(LOGMICRO, "fillVarDataTypes found a WORD char [%c] ...", *p);
        *p = ' ';
    }
    p++;
}
}
*****/
logMsg(LOGMICRO, "fillVarDataTypes END");

logMsg(LOGDEBUG, "HDR=[%s.%s]:[%s]\n", qualifier, mysqlHeaders[i], row[i]);
	}
}
void _nullifySqlFields(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields) {
	int i = 0;
	for (i = 0; i < numFields; i++) {
		char qualifiedName[256];
		sprintf(qualifiedName, "%s.%s", qualifier, mysqlHeaders[i]);
		updateSqlVar(qualifiedName, mysqlTypes[i], NULL);
		//xxkimxx
	}
}
void updateSqlVar(char *qualifiedName, enum enum_field_types mysqlType, char *value) {
	if (!qualifiedName)
		emitStd("NULL 'qualifiedName' passed to updateTableVar\n");
	VAR *seekVar = findVarStrict(qualifiedName);
	if (!seekVar) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->source = strdup("mysql");
		newVar->debugHighlight = 5;
		int ret = decodeMysqlType(newVar, mysqlType, value);
//emitStd("TABLE-> NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
		addVar(newVar);
	} else {
		for (int i = 0; (i <= LAST_VAR); i++) {
			if (!var[i])
				continue;
			if (!strcmp(var[i]->name, qualifiedName)) {
				int ret = decodeMysqlType(var[i], mysqlType, value);
				break;
			}
		}
	}
}

// ----------------------------------------------------------------
// General stuff

// This is the common code that used to be in @get and @each<> - now called from control() and wordDatabaseGet()
MYSQL_RES *doSqlSelect(int ix, char *defaultTableName, char **givenTableName, int maxRows) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(5, 64096);

    // @@TODO refactor this because it shares 90% of its code with @each<>
    int skipCode = 0;

    // Get the given table name that we want to get
    char *ta = strTrim(args);
    char *tg = *givenTableName;

	logMsg(LOGDEBUG, "doSqlSelect:  cmd='%s' ta='%s', ", cmd, ta);

    while ((*ta) && (*ta != ' ') && (*ta != '.'))	// Find ' ' or '.' which terminates the tablename;
        *tg++ = *ta++;
    char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
    sprintf(query, "select * from %s",  *givenTableName);				// set a default query


	// Is this a @eachsql command?
	if (!strcmp(cmd, "@eachsql")) {
		if (*ta) {
			char *p = ta;
			while ((*p) && (*p != ' '))
				*p;
			if (*p == ' ') {
				p++;
				if (*p) {
					strcpy(query, p);
				}
			}
		}
	} else if (*ta) {	// Is there more than just the table name?
        ta++;
        skipCode = appendSqlSelectOptions(query, ta, defaultTableName, *givenTableName);		// build a complex query
    }

    // Append 'limit n'
    char *s = (char *) calloc(5, 64096);
    sprintf(s, " LIMIT %d", maxRows);
    strcat(query, s);
    free(s);
    // Do the query
//emitStd("\n\n[%s]\n\n", query);
//die("kkk");
    MYSQL_RES *res;
    MYSQL_ROW row;
    if (mysql_query(conn, query) != 0) {
        logMsg(LOGERROR, "doSqlSelect: mysql_query failed with '%s', ", mysql_error(conn));
        return NULL;
    }
    res = mysql_store_result(conn);
      if (!res) {
        logMsg(LOGERROR, "Couldn't get results set: %s\n", mysql_error(conn));
        return(NULL);
    }
    free(query);
	free(tmp);
    return res;
}

int appendSqlSelectOptions(char *query, char *args, char *currentTableName, char *givenTableName) {
	#define MAX_SUBARGS 1024
	int retval = 0;
	char *selectorField = NULL;
	char *operand = NULL;
	char *externalFieldOrValue = NULL;
	char *tmp = (char *) calloc(5, 64096);
	char *queryBuilder = (char *) calloc(5, 64096);
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
			if (firstArg) {
				logMsg(LOGERROR, "No arguments provided to @each<>/@get");
				return(-1);
			}
			break;
		}
		wdNum = 0;

		selectorField = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// try for the field selector (LHS)
		if (!selectorField) {
			logMsg(LOGERROR, "table name given for mysql lookup but no field selector");
			return(-1);
		}
		if (!strcmp(selectorField, "filter")) {
			free(selectorField);
			selectorField = strTrim(getWordAlloc(args, ++wdNum, space));
			if (!selectorField) {
				logMsg(LOGERROR, "table name given for mysql lookup but no field selector after 'filter'");
				return(-1);
			}
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
		if (!operand) {
			logMsg(LOGERROR, "no operator given for lookup");
			return(-1);
		}
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

		externalFieldOrValue = strTrim(getWordAlloc(subArg[i], ++wdNum, space));	// try for the external field, containing the value to look for
//logMsg(LOGDEBUG, "[%s]", externalFieldOrValue);

		if (!externalFieldOrValue) {
			externalFieldOrValue = strdup("0");		// cover the the possibility (new db inserts perhaps? where this might be empty)
		}

//sprintf(tmp, "i=%d [%s][%s][%s] fullargs=[%s] and currenttable=[%s]\n", i, selectorField, operand, externalFieldOrValue, args, currentTableName); /*die(tmp);*/
		VAR *variable = NULL;
		char *varValue = NULL;
		variable = findVarLenient(externalFieldOrValue, currentTableName);
//emitStd("\n[%s][%s]\n", externalFieldOrValue, currentTableName);
		if (variable)
			varValue = strdup(variable->portableValue);
		else
			varValue = strdup(externalFieldOrValue);

		// Quote it if necessary (contains non-numeric chars and isnt already)
		if ((!isMysqlField(varValue, givenTableName)) && (varValue[0] != '\'') && (varValue[strlen(varValue)] != '\'')) {

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

			if ( (1==1) || (!isNum) || (numOfMinuses > 1) ) {	// @@TODO @@FIX!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
				if (!strchr(varValue, '\'')) {
					char *newValue = (char *) calloc(5, strlen(varValue) + 3);
					strcpy(newValue, "'");
					strcat(newValue, varValue);
					strcat(newValue, "'");
					free(varValue);
					varValue = newValue;
				}
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
	logMsg(LOGDEBUG, "appendSqlSelectOptions() built query [%s]", query);
	free(queryBuilder);
	free(tmp);
	for (int i = 0; i < MAX_SUBARGS; i++)
		free(subArg[i]);
	return retval;
}

// ----------------------------------------------------------------
// Primitive MYSQL functions

int openDB(char *name) {
	if (!name) {
		logMsg(LOGERROR, "openDB requires a name of db to open");
		return(-1);
	}
	if (connDbName)
		free(connDbName);
	connDbName = strdup(name);
	if (conn)
		closeDB();
	logMsg(LOGMICRO, "Opening db %s", name);
	if (!conn)
		conn = mysql_init(NULL);
	if (!mysql_real_connect(conn, dbServer, dbUser, dbPassword, name, 0, NULL, 0)) {
        mysql_close(conn);
        logMsg(LOGERROR, "Open db %s failed. %s", name, sqlError());
		return -1;
	}
	logMsg(LOGMICRO, "openDB succeeded opening %s", name);
	return 0;
}

void closeDB() {
	if (!conn){
		logMsg(LOGMICRO, "db connection isnt open, not going to try close it");
	} else {
		logMsg(LOGMICRO, "Closing connection to db server");
		mysql_close(conn);
		conn = NULL;
		logMsg(LOGMICRO, "Connection closed");
	}
}

int connectDBServer() {
	logMsg(LOGMICRO, "Connecting to db server");
	if (!conn)
		conn = mysql_init(NULL);
    if (!mysql_real_connect(conn, dbServer, dbUser, dbPassword, NULL, 0, NULL, 0)) {
        mysql_close(conn);
        logMsg(LOGERROR, "Connection to db server failed. %s", sqlError());
        return -1;
    }
    logMsg(LOGMICRO, "Connected OK");
    return 0;
}

int doSqlQuery(char *qStr) {
	if (!conn) {
		logMsg(LOGERROR, "Cant do sql query - no db is open");
        return -1;
	}
	logMsg(LOGMICRO, "Executing sql query [%s]", qStr);
	int status = mysql_query(conn, qStr);
	if (status != 0)
		logMsg(LOGERROR, "Sql query error %s", sqlError());
	logMsg(LOGMICRO, "Executed sql query");
	return(status);
}

// Get the result RES of the last query
MYSQL_RES *getSqlQueryRES() {
	MYSQL_RES *result = mysql_store_result(conn);
	if (result == NULL) 
		logMsg(LOGERROR, "Sql query RES is NULL. Error %s", sqlError());
	return result;
}

MYSQL_ROW getSqlROW(MYSQL_RES *result) {
	return(mysql_fetch_row(result));
}

char *sqlError() {		// see http://www.databaseskill.com/3643013/ for more on errors
	return (char *) (mysql_error(conn));
}
