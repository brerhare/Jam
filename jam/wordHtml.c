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

int wordHtmlInput(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldType = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
	char *fieldSize2 = (char *) calloc(1, 4096);
	char *fieldVar = (char *) calloc(1, 4096);
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);
	char *fieldPlaceholder = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	VAR *variable = NULL;

	getWord(fieldType, args, 2, " \t");
	if (!fieldType) {
	   logMsg(LOGERROR, "missing input type for html input");
	   return(-1);
	}
	getWord(fieldVar, args, 3, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html input");
	   return(-1);
	}
	getWord(fieldSize, args, 4, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html input");
	   return(-1);
	}
	if (!strcasecmp(fieldType, "textarea")) {
		getWord(fieldSize2, fieldSize, 2, "x");	// rows
		getWord(fieldSize, fieldSize, 1, "x");	// cols
	}

	if ((!strchr(fieldVar, '.')) && (defaultTableName)) {
		strcat(fieldVar, ".");
		strcat(fieldVar, defaultTableName);
	}
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 5, " \t");
	getWord(fieldPlaceholder, args, 6, " \t");

//              type var                 size  prompt          placeholder
//{{@html input text stock_supplier.code small "Supplier Code" code}}
	printf("<div class='uk-form-row'>\n");
	printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	printf("		<div class='uk-form-controls'>\n");
	if (!strcasecmp(fieldType, "textarea"))
		printf("		<textarea name='%s' id='%s' cols='%s' rows='%s'>%s</textarea>", fieldType, fieldVar, fieldSize, fieldSize2, fieldVarValue);
	else
		printf("		<input type='%s' name='%s' id='%s' value='%s' placeholder='%s' class='uk-form-width-%s'>\n", fieldType, fieldVar, fieldVar, fieldVarValue, fieldPlaceholder, fieldSize);
	printf("	</div>\n");
	printf("</div>\n");

	emit(jam[ix]->trailer);
	free(fieldName);
	free(fieldType);
	free(fieldSize);
	free(fieldSize2);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(tmp);
}
