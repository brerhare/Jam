#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordDatabase.h"
#include "common.h"
#include "database.h"
#include "stringUtil.h"
#include "log.h"

//-----------------------------------------------------------------
// Database

int wordDatabaseListDatabases(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 64096);

	MYSQL_RES *res;
	MYSQL_ROW row;

	if (connectDBServer() != 0) {
	   logMsg(LOGERROR, (char *) mysql_error(conn));
	   return(-1);
	}
	mysql_query(conn,"show databases");
	res = mysql_store_result(conn);
	while (row = mysql_fetch_row(res)) {
		if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
			continue;
		sprintf(tmp, "%s <br>", row[0]);
		emitStd(tmp);
	}
	closeDB();

	emitStd(jam[ix]->trailer);
	free(tmp);
	return 0;
}

int wordDatabaseListTables(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 64096);

	MYSQL_RES *res;
	MYSQL_ROW row;
	mysql_query(conn,"show tables");
	res = mysql_store_result(conn);
	while (row = mysql_fetch_row(res)) {
		if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
			continue;
		sprintf(tmp, "%s <br>", row[0]);
		emitStd(tmp);
	}

	emitStd(jam[ix]->trailer);
	free(tmp);
	return 0;
}

int wordDatabaseNewDatabase(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *dbName = (char *) calloc(1, 64096);

	getWord(dbName, args, 2, " ");
	if (!dbName) {
	   logMsg(LOGERROR, "missing database name to create");
	   return(-1);
	}
	if (connectDBServer() != 0)
		return(-1);
	char *qStr = (char *) calloc(1, 64096);
	//sprintf(qStr,"DROP DATABASE IF EXISTS %s", dbName);
	//int status = mysql_query(conn,qStr);
	sprintf(qStr,"CREATE DATABASE %s", dbName);
	if (mysql_query(conn,qStr) != 0)
		return(-1);
	if (openDB(dbName) != 0)
		return(-1);
	emitStd(jam[ix]->trailer);
	free(dbName);
	return(0);
}

int wordDatabaseRemoveDatabase(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *dbName = (char *) calloc(1, 64096);

	getWord(dbName, args, 2, " ");
	if (!dbName) {
	   logMsg(LOGERROR, "missing database name to remove");
	   return(-1);
	}
	if (connectDBServer() != 0) {
	   logMsg(LOGERROR, (char *) mysql_error(conn));
	   return(-1);
	}
	char *qStr = (char *) calloc(1, 64096);
	sprintf(qStr,"DROP DATABASE IF EXISTS %s", dbName);
	int status = mysql_query(conn,qStr);
	emitStd(jam[ix]->trailer);
	free(dbName);
	return(0);
}

int wordDatabaseDatabase(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;

	if (strlen(args) == 0) {
	   logMsg(LOGERROR, "missing database name");
	   return(-1);
	}
	if (openDB(args) != 0) {
	   logMsg(LOGERROR, (char *) mysql_error(conn));
	   return(-1);
	}
	emitStd(jam[ix]->trailer);
	return(0);
}

int wordDatabaseDescribe(int ix, char *defaultTableName)
{
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 64096);

	MYSQL_RES *res;
	MYSQL_ROW row;
	char *space = " ";
	getWord(tmp, args, 1, space);
	if (*tmp) {
		char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
		char *line = (char *) calloc(1, 64096);
		sprintf(query, "desc %s", args);
		if (mysql_query(conn,query) != 0) {
		   logMsg(LOGERROR, (char *) mysql_error(conn));
		   return(-1);
		}
		res = mysql_store_result(conn);
		if (!res) {
			logMsg(LOGERROR, "Cannot describe database table - returned null resultset");
			return(-1);
		}
		int numFields = mysql_num_fields(res);
		emitStd("<table border=1 cellpadding=3 cellspacing=0 style='border: 1pt solid #abdbd7; border-Collapse: collapse'>");
		emitStd("<tr><td> Field </td><td> Type </td><td> Empty allowed? </td><td> Key </td><td> Default value </td><td> Extra </td></tr>");
		while (row = mysql_fetch_row(res)) {
			strcpy(line, "<tr>");
			for (int i = 0; i < numFields; i++) {
				sprintf(tmp, "<td> %s </td>", row[i]);
				strcat(line, tmp);
			}
			strcat(line, "</tr>");
			emitStd(line);
		}
		emitStd("</table>");
		free(query);
		free(line);
	} else {
		logMsg(LOGERROR, "describe what?");
		return(-1);
	}
	free(tmp);
	emitStd(jam[ix]->trailer);
	return(0);
}

int wordDatabaseGet(int ix, char *defaultTableName) {
	char *givenTableName = (char *) calloc(1, 64096);
	MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 1);
	SQL_RESULT *rp = sqlCreateResult(givenTableName, res);

	sqlGetRow2Vars(rp);
	logMsg(LOGDEBUG, "done sqlCreateResult");
	if (jam[ix]) {
		emitStd(jam[ix]->trailer);
	}
	mysql_free_result(res);
	free(rp->tableName);
	free(rp);
	free(givenTableName);
	return(0);
}

int wordDatabaseSql(int ix, char *defaultTableName) {
	char *args = jam[ix]->args;
	logMsg(LOGDEBUG, "Doing raw sql query [%s]", args);
	int status = doSqlQuery(args);
	if (status == -1) {
		logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
		return (-1);
	}
	logMsg(LOGDEBUG, "Getting RES for raw query");
	MYSQL_RES *res = mysql_store_result(conn);
	if (res != NULL) {	// ie the query returned row(s)
		logMsg(LOGDEBUG, "RES is non-null");
		SQL_RESULT *rp = sqlCreateResult(defaultTableName, res);
		sqlGetRow2Vars(rp);
		mysql_free_result(res);
		free(rp->tableName);
		free(rp);
	} else logMsg(LOGDEBUG, "RES is null");
	emitStd(jam[ix]->trailer);
	return(0);
}

//-----------------------------------------------------------------
// Table

/*  {@new table product
		code        string      unique required
		name  		string      required
		onhand      number
		costprice	decimal4
		sellprice	decimal2
		unuqueid	number      increment
		created		datetime
		order_date	date
		order_time	time
	} */
int wordDatabaseNewTable(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
	   logMsg(LOGERROR, "missing table name to create");
	   return(-1);
	}

	char *qStr = (char *) calloc(1, 64096);
	char *p;
	sprintf(qStr, "CREATE TABLE IF NOT EXISTS %s ( id INT NOT NULL AUTO_INCREMENT , ", tableName);
	int cnt = 2;
	// Each line. Building a query string similar to "CREATE TABLE Cars(Id INT NOT NULL, Name TEXT, Price INT)"
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		char *fieldName = strTrim(getWordAlloc(block, 1, " \t"));
		char *fieldType = strTrim(getWordAlloc(block, 2, " \t"));
		if (!(fieldName) || (!fieldType)) {
	   		logMsg(LOGERROR, "Missing field name or type");
	   		return(-1);
		}
		// Field name to query string
		strcat(qStr, fieldName);
		strcat(qStr, " ");
		// Field type to query string
		if ((p = getSqlType(fieldType)) == NULL) {
			logMsg(LOGERROR, "Invalid field type '%s' in create table", fieldType);
			return(-1);
		}
		strcat(qStr, p);
		strcat(qStr, " ");
		// Option(s) to query string. They start from word 3
		int optionWord = 3;
		*tmp = '\0';
		while (char *fieldOption = strTrim(getWordAlloc(block, optionWord++, " \t"))) {
			if ((p = getSqlOption(fieldOption)) == NULL) {
				logMsg(LOGERROR, "Invalid field option '%s' in create table", p);
				return(-1);
			}
			strcat(qStr, p);
			strcat(qStr, " ");
			free(fieldOption);
		}
		strcat(qStr, ", ");
		free(fieldName);
		free(fieldType);
		free(block);
	}
	logMsg(LOGDEBUG, "wordDatabaseNewTable creating table using this string: [%s]", qStr);
	strcat(qStr, "  PRIMARY KEY (id) ) ENGINE = InnoDB;");
	if (mysql_query(conn,qStr) != 0) {
	   logMsg(LOGERROR, (char *) mysql_error(conn));
	   return(-1);
	}
	emitStd(jam[ix]->trailer);
	free(tableName);
	free(qStr);
	free(tmp);
	return(0);
}

/*  {@new index product myindex
		name	(asc)
		code 	(desc)
		...
} */
int wordDatabaseNewIndex(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *indexName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
	   logMsg(LOGERROR, "missing table name to create index for");
	   return(-1);
	}
	getWord(indexName, args, 3, " \t");
	if (!indexName) {
	   logMsg(LOGERROR, "missing index name to create");
	   return(-1);
	}
	char *qStr = (char *) calloc(1, 64096);
	sprintf(qStr, "CREATE INDEX %s ON %s ( ", indexName, tableName);
	int cnt = 2;
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		if (cnt != 3)
			strcat(qStr, ", ");
		char *fieldName = strTrim(getWordAlloc(block, 1, " \t"));
		char *ascDesc = strTrim(getWordAlloc(block, 2, " \t"));
		if (!(fieldName))
			break;
		strcat(qStr, fieldName);
		strcat(qStr, " ");
		if (ascDesc)
			strcat(qStr, ascDesc);
		free(block);
		free(fieldName);
		free(ascDesc);
	}
	strcat(qStr, " );");
	//die(qStr);
	if (mysql_query(conn,qStr) != 0) {
	   logMsg(LOGERROR, (char *) mysql_error(conn));
	   return(-1);
	}
	emitStd(jam[ix]->trailer);
	free(tableName);
	free(indexName);
	free(qStr);
	free(tmp);
	return(0);
}

int wordDatabaseClearItem(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
	   logMsg(LOGERROR, "missing table name to clear item for");
	   return(-1);
	}

	sprintf(tmp, "select * from %s limit 1", tableName);
	logMsg(LOGDEBUG, "Doing dummy sql query [%s]", tmp);
	int status = doSqlQuery(tmp);
	if (status == -1) {
		logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
		return (-1);
	}
	logMsg(LOGDEBUG, "Getting RES for raw query");
	MYSQL_RES *res = mysql_store_result(conn);
	if (res != NULL) {	// ie the query returned row(s)
		logMsg(LOGDEBUG, "RES is non-null");
		SQL_RESULT *rp = sqlCreateResult(tableName, res);
		sqlClearRowVars(rp);
		mysql_free_result(res);
		free(rp->tableName);
		free(rp);
	} else logMsg(LOGDEBUG, "RES is null");

	emitStd(jam[ix]->trailer);
	free(tableName);
	free(tmp);
	return(0);
}

int wordDatabaseNewItem(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	char *qStr = (char *) calloc(1, 64096);
	char *nameStr = (char *) calloc(1, 64096);
	char *valueStr = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
		logMsg(LOGERROR, "New item failed - no table name supplied");
		return (-1);
	}
	sprintf(qStr, "DESC %s", tableName);
	int status = doSqlQuery(qStr);
	if (status == -1) {
		logMsg(LOGERROR, "New item failed - cant 'describe' table");
		return (-1);
	}
	MYSQL_RES *res = getSqlQueryRES();
	if (res == NULL) {
		logMsg(LOGERROR, "New item cannot describe database table - returned null resultset");
		return(-1);
	}
	int numFields = mysql_num_fields(res);
	while (MYSQL_ROW row = mysql_fetch_row(res)) {
		if (strlen(nameStr) != 0) {
			strcat(nameStr, ", ");
			strcat(valueStr, ", ");
		}
		strcat(nameStr, row[0]);
		sprintf(tmp, "%s.%s", tableName, row[0]);
		VAR *seekVar = findVarStrict(tmp);
logMsg(LOGDEBUG, "wordDatabaseNewItem setting up field: [%s]", row[0]);
		if ((!seekVar) || (!strcasecmp(row[0], "id")))
			strcat(valueStr, "NULL");
		else {
			char *quoted =  escapeSingleQuote(seekVar->portableValue);
			sprintf(tmp, "'%s'", quoted);
			strcat(valueStr, tmp);
			free(quoted);
		}
	}
	sprintf(qStr,"INSERT INTO %s (", tableName);
	strcat(qStr, nameStr);
	strcat(qStr, ") values (");
	strcat(qStr, valueStr);
	strcat(qStr, ")");
	logMsg(LOGDEBUG, "wordDatabaseNewItem creating: [%s]", qStr);
		status = doSqlQuery(qStr);
	if (status == -1) {
		logMsg(LOGERROR, "New item failed");
		return (-1);
	}

	int newId = mysql_insert_id(conn);
	sprintf(tmp, "select * from %s where id = '%d'", tableName, newId);
	status = doSqlQuery(tmp);
	if (status == -1) {
		logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
		return (-1);
	}
	logMsg(LOGDEBUG, "Getting RES for raw query");
	res = mysql_store_result(conn);
	if (res != NULL) {	// ie the query returned row(s)
		logMsg(LOGDEBUG, "RES is non-null");
		SQL_RESULT *rp = sqlCreateResult(tableName, res);
		sqlGetRow2Vars(rp);
		mysql_free_result(res);
		free(rp->tableName);
		free(rp);
	} else logMsg(LOGDEBUG, "RES is null");


/*
	sprintf(tmp, "%s.%s", tableName, "id");
	VAR *newVar = findVarStrict(tmp);
	if (newVar) {
		//delete
	}
	newVar = (VAR *) calloc(1, sizeof(VAR));
	newVar->name = strdup(tmp);
	newVar->type = VAR_STRING;	// @@FIX!!!!!!
	clearVarValues(newVar);
	sprintf(tmp, "%d", newId);
	fillVarDataTypes(newVar, tmp);
	if (addVar(newVar) == -1) {
		logMsg(LOGFATAL, "Cant create any more vars, terminating");
		exit(1);
	}
*/

	sprintf(tmp, "%s.id", tableName);
	setVarAsNumber(tmp, newId);

	logMsg(LOGDEBUG, "Created new item in table '%s' with id value '%d'", tableName, newId);
	emitStd(jam[ix]->trailer);
	free(tableName);
	free(qStr);
	free(tmp);
	return(0);
}

int wordDatabaseAmendItem(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	char *qStr = (char *) calloc(1, 64096);
	char *nvpStr = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
		logMsg(LOGERROR, "Amend item failed - no table name supplied");
		return (-1);
	}
	sprintf(qStr, "DESC %s", tableName);
	int status = doSqlQuery(qStr);
	if (status == -1) {
		logMsg(LOGERROR, "Amend item failed - cant 'describe' table");
		return (-1);
	}
	MYSQL_RES *res = getSqlQueryRES();
	if (res == NULL) {
		logMsg(LOGERROR, "Amend item cannot describe database table - returned null resultset");
		return(-1);
	}

	// Find the primary key variable (must exist)
	sprintf(tmp, "%s.%s", tableName, "id");
	VAR *idVar = findVarStrict(tmp);
	if (!idVar) {
		logMsg(LOGERROR, "Amend item requires the id variable to be set");
		return(-1);
	}

	int numFields = mysql_num_fields(res);
	while (MYSQL_ROW row = mysql_fetch_row(res)) {
		if (!strcmp(row[0], "id"))
			continue;
		sprintf(tmp, "%s.%s", tableName, row[0]);
		VAR *seekVar = findVarStrict(tmp);
		if (!seekVar)
			continue;
		if (strlen(nvpStr) != 0)
			strcat(nvpStr, ", ");
		char *quoted =  escapeSingleQuote(seekVar->portableValue);
		sprintf(tmp, "%s = '%s'", row[0], quoted);
		strcat(nvpStr, tmp);
		free(quoted);
	}
	sprintf(qStr,"UPDATE %s SET %s WHERE id = '%s'", tableName, nvpStr, idVar->portableValue);
	logMsg(LOGDEBUG, "item amend about to issue query [%s]", qStr);
	status = doSqlQuery(qStr);
	if (status == -1) {
		logMsg(LOGERROR, "Amend item failed");
		return (-1);
	}

	emitStd(jam[ix]->trailer);
	free(tableName);
	free(qStr);
	free(nvpStr);
	free(tmp);
	return(0);
}

// Create if not exist, amend if exist
int wordDatabaseUpdateItem(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	int res = 0;

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
		logMsg(LOGERROR, "Update item failed - no table name supplied");
		return (-1);
	}

	// Find the primary key. If it exists WITH A VALUE it's a new, otherwise an amend)
	sprintf(tmp, "%s.%s", tableName, "id");
	VAR *idVar = findVarStrict(tmp);
	logMsg(LOGDEBUG, "Update item looking for (strict) variable [%s]", tmp);
	if ((!idVar) || (atoi(idVar->portableValue) == 0) || (atoi(idVar->portableValue) == -1)) {
		logMsg(LOGDEBUG, "Update item resolves to New item");
		res = wordDatabaseNewItem(ix, defaultTableName);
	}
	else {
		logMsg(LOGDEBUG, "Update item resolves to Amend item");
		res = wordDatabaseAmendItem(ix, defaultTableName);
	}

	emitStd(jam[ix]->trailer);
	free(tableName);
	free(tmp);
	return(res);
}

int wordDatabaseRemoveTable(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
	   logMsg(LOGERROR, "missing table name to remove");
	   return(-1);
	}
	char *qStr = (char *) calloc(1, 64096);
	sprintf(qStr,"DROP TABLE IF EXISTS %s", tableName);
	int status = mysql_query(conn,qStr);
	emitStd(jam[ix]->trailer);
	free(tableName);
	return(0);
}

int wordDatabaseRemoveIndex(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *indexName = (char *) calloc(1, 64096);
	char *qStr = (char *) calloc(1, 64096);

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
	   logMsg(LOGERROR, "missing table name to remove index from");
	   return(-1);
	}
	getWord(indexName, args, 3, " \t");
	if (!indexName) {
	   logMsg(LOGERROR, "missing index name to remove");
	   return(-1);
	}
	sprintf(qStr,"DROP INDEX %s ON %s", indexName, tableName);
	int status = mysql_query(conn,qStr);
	emitStd(jam[ix]->trailer);
	free(tableName);
	free(indexName);
	return(0);
}

int wordDatabaseRemoveItem(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 64096);
	char *tmp = (char *) calloc(1, 64096);
	char *qStr = (char *) calloc(1, 64096);
	VAR *variable = NULL;

	getWord(tableName, args, 2, " \t");
	if (!tableName) {
		logMsg(LOGERROR, "Cant remove item, no table name given");
		emitStd("missing table name to remove item from");
		return(-1);
	}
	logMsg(LOGDEBUG, "Removing item from table %s", tableName);
	// The id variable must exist
	sprintf(tmp, "%s.id", tableName);
	variable = findVarStrict(tmp);
	if (!variable) {
		logMsg(LOGERROR, "Cant remove item, missing id variable");
		emitStd("missing id variable to remove item for");
		return(-1);
	}
	sprintf(qStr,"DELETE FROM %s WHERE id = %d", tableName, atoi(variable->portableValue));
	int status = doSqlQuery(qStr);
	if (status == -1) {
		logMsg(LOGERROR, "Remove item failed");
		return (-1);
	}
	logMsg(LOGDEBUG, "Removed item %d from %s", atoi(variable->portableValue), tableName);
	emitStd(jam[ix]->trailer);
	free(tableName);
	free(tmp);
	return(0);
}
