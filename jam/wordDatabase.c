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

int wordDatabaseList(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    MYSQL_RES *res;
    MYSQL_ROW row;
    char *space = " ";
    getWord(tmp, args, 1, space);
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
    free(tmp);
    return 0;
}

int wordDatabaseDatabase(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;

	if (strlen(args) == 0) {
		printf("missing database name\n");
		exit(1);
	}
	if (openDB(args) < 0) {
		die(mysql_error(conn));
	}
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
