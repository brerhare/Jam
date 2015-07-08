#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordControl.h"
#include "common.h"
#include "stringUtil.h"

int wordControlEach(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

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


    emit(jam[ix]->trailer);
    free(tmp);
    return 0;
}
