#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordCustom.h"
#include "common.h"
#include "database.h"
#include "stringUtil.h"
#include "list.h"
#include "log.h"

char *customDbName = NULL;	// used to create a <input type=hidden> field. Populated by wordDatabase.c

// Use for anything - internally used too. eg for html gen hidden dbname element

int wordCustomHtml(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *customName = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(customName, args, 2, " \t");
	if (!customName) {
		logMsg(LOGERROR, "custom html: missing the name");
		return(-1);
	}
	logMsg(LOGDEBUG, "custom html %s activated", customName);

	// Called from sys/html/footer.html
	if (!strcmp(customName, "endOfHtml")) {
		// Embed the db name in the html for any @action calls
		if (connDbName == NULL)
			connDbName = strdup("");
		printf("<input type='hidden' id='_dbname' name='_dbname' value='%s'>", connDbName);
		logMsg(LOGDEBUG, "");
	}

	// Called from sys/jam/autocomplete.jam
	if (!strcmp(customName, "autocomplete")) {
	// Autocomplete <input type=filter> cant produce json (yet) or db handle '%like%' (needs embedded curlies to work) so we have this custom operation temporarily - a @@TODO
		char *q = (char *) calloc(1, 4096);
		char *customField = (char *) calloc(1, 4096);
		char *customValue = (char *) calloc(1, 4096);
		char *tableName = (char *) calloc(1, 4096);
		char *fieldName = (char *) calloc(1, 4096);
		getWord(customField, args, 3, " \t");
		if (!customField) {
			logMsg(LOGERROR, "missing field '_filterfield'");
			return(-1);
		}
		getWord(customValue, args, 4, " \t");
		if (!customValue) {
			logMsg(LOGERROR, "missing value '_filtervalue'");
			return(-1);
		}
		VAR *variableField = findVarStrict(customField);
		if (!variableField) {
			logMsg(LOGERROR, "missing custom variable for '_filterfield'. Looking (strict) for '%s'", customField);
			return(-1);	
		}
		VAR *variableValue = findVarStrict(customValue);
		if (!variableValue) {
			logMsg(LOGERROR, "missing custom variable for '_filtervalue'. Looking (strict) for '%s'", customValue);
			return(-1);	
		}
		getWord(tableName, variableField->portableValue, 1, ".");
		getWord(fieldName, variableField->portableValue, 2, ".");
		// Kludge to handle old 'id' vs '_id' field in tables
		char idField[512];
		sprintf(idField, "_id");
		if ( (!strcmp(tableName, "stock_customer"))
		||   (!strcmp(tableName, "stock_product")) )
			sprintf(idField, "id");
		sprintf(q, "select %s, %s from %s where %s like '%%%s%%'", idField, fieldName, tableName, fieldName, variableValue->portableValue);
		logMsg(LOGDEBUG, "Autocomplete custom prepared query [%s]", q);
		int status = doSqlQuery(q);
		if (status == -1) {
			logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
			return (-1);
		}
		logMsg(LOGDEBUG, "Getting RES for raw query");
		MYSQL_RES *res = mysql_store_result(conn);
		if (res != NULL) {	// ie the query returned row(s)
			logMsg(LOGDEBUG, "RES is non-null");
			SQL_RESULT *rp = sqlCreateResult(tableName, res);
			int first = 1;
			printf("[");
			while (sqlGetRow2Var(rp) != SQL_EOF) {
				VAR *v = findVarStrict(variableField->portableValue);
				sprintf(tmp, "%s.%s", tableName, idField);
				VAR *_id = findVarStrict(tmp);
				if ((!v) || (!_id)) {
					logMsg(LOGERROR, "internal error - either cant retrieve row jam variable or its _id");
					return(-1);	
				}
				if (first)
					first = 0;
				else
					printf(", ");
				// Avoid single quotes - the formal JSON spec doesnt allow them
				printf("{\"value\":\"%s\", \"id\":\"%d\"}", v->portableValue,  atoi(_id->portableValue));
			}
			printf("]");
		} else
			logMsg(LOGDEBUG, "RES is null");
		free(q);
		free(customField);
		free(customValue);
		free(tableName);
		free(fieldName);
	}

	// Wrap up
	free(tmp);
	free(customName);
    emit(jam[ix]->trailer);
}
