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

//	{{@html input hidden stock_supplier._id}}
//	{{@html input text stock_supplier.payment_terms mini "Payment terms" days}}

int wordHtmlInput(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldType = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
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

	if ((!strchr(fieldVar, '.')) && (defaultTableName)) {
		strcat(fieldVar, ".");
		strcat(fieldVar, defaultTableName);
	}
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 5, " \t");
	getWord(fieldPlaceholder, args, 6, " \t");

	printf("<div class='uk-form-row'>\n");
	printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	printf("		<div class='uk-form-controls'>\n");
	printf("		<input type='%s' name='%s' id='%s' value='%s' placeholder='%s' class='uk-form-width-%s'>\n", fieldType, fieldVar, fieldVar, fieldVarValue, fieldPlaceholder, fieldSize);
	printf("	</div>\n");
	printf("</div>\n");

	emit(jam[ix]->trailer);
	free(fieldName);
	free(fieldType);
	free(fieldSize);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(tmp);
}

//	{{@html textarea stock_supplier.notes 60x5 Notes}}

int wordHtmlTextarea(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
	char *fieldSize2 = (char *) calloc(1, 4096);
	char *fieldVar = (char *) calloc(1, 4096);
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);
	char *fieldPlaceholder = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	VAR *variable = NULL;

	getWord(fieldVar, args, 2, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html textarea");
	   return(-1);
	}
	getWord(fieldSize, args, 3, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html textarea");
	   return(-1);
	}
	getWord(fieldSize2, fieldSize, 2, "x");	// rows
	getWord(fieldSize, fieldSize, 1, "x");	// cols

	if ((!strchr(fieldVar, '.')) && (defaultTableName)) {
		strcat(fieldVar, ".");
		strcat(fieldVar, defaultTableName);
	}
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 4, " \t");
	getWord(fieldPlaceholder, args, 5, " \t");

	printf("<div class='uk-form-row'>\n");
	printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	printf("		<div class='uk-form-controls'>\n");
	printf("		<textarea name='%s' id='%s' cols='%s' rows='%s'>%s</textarea>", fieldVar, fieldVar, fieldSize, fieldSize2, fieldVarValue);
	printf("	</div>\n");
	printf("</div>\n");

	emit(jam[ix]->trailer);
	free(fieldName);
	free(fieldSize);
	free(fieldSize2);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(tmp);
}

/*	{{@html button Save primary small
		alert('ok')     // or any js
		runTemplate
		runTemplate supplierMaint.tpl
		runAction updateSupplier supplierForm outputResult backButton
		runAction updateSupplier supplierForm outputResult runTemplate supplierMaint.tpl    // where supplierMaint.tpl is the arg to runTemplate
}} */

int wordHtmlButton(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *buttonText = (char *) calloc(1, 4096);
	char *buttonType = (char *) calloc(1, 4096);
	char *buttonSize = (char *) calloc(1, 4096);
	char *buttonJS = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	int buttonId = rand() % 9999999;			// @@TODO fix

	getWord(buttonText, args, 2, " \t");
	if (!buttonText) {
	   logMsg(LOGERROR, "missing button text");
	   return(-1);
	}
	getWord(buttonType, args, 3, " \t");
	if (!buttonType) {
	   logMsg(LOGERROR, "missing button type");
	   return(-1);
	}
	getWord(buttonSize, args, 4, " \t");
	if (!buttonSize) {
	   logMsg(LOGERROR, "missing button size");
	   return(-1);
	}

	printf("<button type='button' onClick='buttonClick%d()' class='uk-button uk-button-%s uk-button-%s'>%s</button>\n", buttonId, buttonSize, buttonType, buttonText);
	sprintf(buttonJS, "<script>\nfunction buttonClick%d() {\n", buttonId);
	int cnt = 2;
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		char *command = strTrim(getWordAlloc(block, 1, " \t"));
		if (!command)
			break;
		if (!strcasecmp(command, "runTemplate")) {		// Parenthesize argument if any
			char *templateName = strTrim(getWordAlloc(block, 2, " \t"));
			if (templateName)
				sprintf(tmp, "%s('%s');\n", command, templateName);
			else
				sprintf(tmp, "%s();\n", command);
			strcat(buttonJS, tmp);
			free(command);
			free(templateName);
		} else if (!strcasecmp(command, "runAction")) {	// Look for AJAX command, we need to wait for return there
			char *pBlock = strchr(block, ' ');
			sprintf(tmp, "%s(", command);
			strcat(buttonJS, tmp);
			int ix = 1;
//printf("<br> \n----->%s <br>\n", pBlock);
			while(char *runActionArg = strTrim(getWordAlloc(pBlock, ix, " "))) {
				if (!runActionArg)
					break;
//printf("-->%s <br>\n", runActionArg);
				if (ix != 1)
					strcat(buttonJS, ", ");
				if (ix < 4) {
					if ((!strchr(runActionArg, '"')) && (!strchr(runActionArg, '\''))) {
						sprintf(tmp, "'%s'", runActionArg);
						strcat(buttonJS, tmp);
					} else
						strcat(buttonJS, runActionArg);
				}
				else
					strcat(buttonJS, runActionArg);
					//strcat(buttonJS, "@@TODOCALLBACK");
				free(runActionArg);
				ix++;
			}
			strcat(buttonJS, ");\n");
		} else {
			sprintf(tmp, "%s\n", block);
			strcat(buttonJS, tmp);
		}
//printf("-----> <br>\n");
		free(block);
	}
	strcat(buttonJS, "}\n</script>\n");
	printf("%s", buttonJS);
	emit(jam[ix]->trailer);
	free(buttonText);
	free(buttonType);
	free(buttonSize);
	free(buttonJS);
	free(tmp);
}