#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordHtml.h"
#include "common.h"
#include "stringUtil.h"
#include "log.h"

//-----------------------------------------------------------------
// HTML

int wordHtmlGridtable(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(tableName, args, 2, " ");
	if (!tableName)
	   die("missing table name to gridify");

	emit(jam[ix]->trailer);
	free(tableName);
	free(tmp);
}

