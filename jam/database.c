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
        setFieldValues(rp->tableName, rp->mysqlHeaders, rp->mysqlTypes, rp->numFields, &row);
        return SQL_OK;
    }
    return SQL_EOF;
}

// ----------------------------------------------------------------

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
