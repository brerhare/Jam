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

//-----------------------------------------------------------------
// Database

int wordDatabaseListDatabases(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    MYSQL_RES *res;
    MYSQL_ROW row;

    if (connectDBServer() != 0)
    	die(mysql_error(conn));
    mysql_query(conn,"show databases");
    res = mysql_store_result(conn);
    while (row = mysql_fetch_row(res)) {
        if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
            continue;
        sprintf(tmp, "%s <br>", row[0]);
        emit(tmp);
    }
    closeDB();

    emit(jam[ix]->trailer);
    free(tmp);
    return 0;
}

int wordDatabaseListTables(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    MYSQL_RES *res;
    MYSQL_ROW row;
    mysql_query(conn,"show tables");
    res = mysql_store_result(conn);
    while (row = mysql_fetch_row(res)) {
        if ((!strcmp(row[0], "information_schema")) || (!strcmp(row[0], "mysql")) || (!strcmp(row[0], "performance_schema")))
            continue;
        sprintf(tmp, "%s <br>", row[0]);
        emit(tmp);
    }

    emit(jam[ix]->trailer);
    free(tmp);
    return 0;
}

int wordDatabaseNewDatabase(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *dbName = (char *) calloc(1, 4096);

    getWord(dbName, args, 2, " ");
    if (!dbName)
	   die("missing database name to create");
    if (connectDBServer() != 0)
    	die(mysql_error(conn));
    char *qStr = (char *) calloc(1, 4096);
    //sprintf(qStr,"DROP DATABASE IF EXISTS %s", dbName);
    //int status = mysql_query(conn,qStr);
    sprintf(qStr,"CREATE DATABASE %s", dbName);
    if (mysql_query(conn,qStr) != 0)
        die(mysql_error(conn));
    if (openDB(dbName) != 0)
    	die(mysql_error(conn));
	emit(jam[ix]->trailer);
    free(dbName);
}

int wordDatabaseRemoveDatabase(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *dbName = (char *) calloc(1, 4096);

    getWord(dbName, args, 2, " ");
    if (!dbName)
	   die("missing database name to remove");
    if (connectDBServer() != 0)
    	die(mysql_error(conn));
    char *qStr = (char *) calloc(1, 4096);
    sprintf(qStr,"DROP DATABASE IF EXISTS %s", dbName);
    int status = mysql_query(conn,qStr);
	emit(jam[ix]->trailer);
    free(dbName);
}

int wordDatabaseDatabase(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;

	if (strlen(args) == 0)
		die("missing database name");
	if (openDB(args) != 0)
		die(mysql_error(conn));
	emit(jam[ix]->trailer);
}

int wordDatabaseDescribe(int ix, char *defaultTableName)
{
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    MYSQL_RES *res;
    MYSQL_ROW row;
    char *space = " ";
    getWord(tmp, args, 1, space);
    if (*tmp) {
        char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
        char *line = (char *) calloc(1, 4096);
        sprintf(query, "desc %s", args);
        if (mysql_query(conn,query) != 0)
    	   die(mysql_error(conn));
        res = mysql_store_result(conn);
        if (!res)
            die("Cannot describe database table - returned null resultset");
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
    free(tmp);
    emit(jam[ix]->trailer);
}

int wordDatabaseGet(int ix, char *defaultTableName) {
    char *givenTableName = (char *) calloc(1, 4096);
    MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 1);
    SQL_RESULT *rp = sqlCreateResult(givenTableName, res);
    sqlGetRow(rp);
    if (jam[ix]) {
        emit(jam[ix]->trailer);
    }
    mysql_free_result(res);
    free(givenTableName);
    //if ((!row) && (skipCode == 1)) {		/@@FIX! make function so this can be shared with database.c
        //return(0);
    //}
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
    char *tableName = (char *) calloc(1, 4096);
    char *tmp = (char *) calloc(1, 4096);

    getWord(tableName, args, 2, " \t");
    if (!tableName)
	   die("missing table name to create");

    char *qStr = (char *) calloc(1, 4096);
    char *p;
    sprintf(qStr, "CREATE TABLE IF NOT EXISTS %s ( __id INT NOT NULL AUTO_INCREMENT , ", tableName);
    int cnt = 2;
    // Each line. Building a query string similar to "CREATE TABLE Cars(Id INT NOT NULL, Name TEXT, Price INT)"
    while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
        char *fieldName = strTrim(getWordAlloc(block, 1, " \t"));
        char *fieldType = strTrim(getWordAlloc(block, 2, " \t"));
        if (!(fieldName) || (!fieldType))
            die("Missing field name or type");
        // Field name to query string
        strcat(qStr, fieldName);
        strcat(qStr, " ");
        // Field type to query string
        if ((p = getSqlType(fieldType)) == NULL) {
            sprintf(tmp, "Invalid field type '%s' in create table", p);
            die(tmp);
        }
        strcat(qStr, p);
        strcat(qStr, " ");
        // Option(s) to query string. They start from word 3
        int optionWord = 3;
        *tmp = '\0';
        while (char *fieldOption = strTrim(getWordAlloc(block, optionWord++, " \t"))) {
            if ((p = getSqlOption(fieldOption)) == NULL) {
                sprintf(tmp, "Invalid field option '%s' in create table", p);
                die(tmp);
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
    strcat(qStr, "  PRIMARY KEY (__id) ) ENGINE = InnoDB;");
    if (mysql_query(conn,qStr) != 0)
        die(mysql_error(conn));
	emit(jam[ix]->trailer);
    free(tableName);
    free(qStr);
    free(tmp);
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
    char *tableName = (char *) calloc(1, 4096);
    char *indexName = (char *) calloc(1, 4096);
    char *tmp = (char *) calloc(1, 4096);

    getWord(tableName, args, 2, " \t");
    if (!tableName)
	   die("missing table name to create index for");
    getWord(indexName, args, 3, " \t");
    if (!indexName)
	   die("missing index name to create");
    char *qStr = (char *) calloc(1, 4096);
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
    if (mysql_query(conn,qStr) != 0)
        die(mysql_error(conn));
	emit(jam[ix]->trailer);
    free(tableName);
    free(indexName);
    free(qStr);
    free(tmp);
}

int wordDatabaseRemoveTable(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tableName = (char *) calloc(1, 4096);

    getWord(tableName, args, 2, " \t");
    if (!tableName)
        die("missing table name to remove");
    char *qStr = (char *) calloc(1, 4096);
    sprintf(qStr,"DROP TABLE IF EXISTS %s", tableName);
    int status = mysql_query(conn,qStr);
	emit(jam[ix]->trailer);
    free(tableName);
}

int wordDatabaseRemoveIndex(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tableName = (char *) calloc(1, 4096);
    char *indexName = (char *) calloc(1, 4096);

    getWord(tableName, args, 2, " \t");
    if (!tableName)
        die("missing table name to remove index from");
    getWord(indexName, args, 3, " \t");
    if (!indexName)
        die("missing index name to remove");
    char *qStr = (char *) calloc(1, 4096);
    sprintf(qStr,"DROP INDEX %s ON %s", indexName, tableName);
    int status = mysql_query(conn,qStr);
	emit(jam[ix]->trailer);
    free(tableName);
    free(indexName);
}
