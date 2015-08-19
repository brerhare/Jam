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
#include "log.h"
#include "stringUtil.h"

//-----------------------------------------------------------
// Var related

// Add a fully populated VAR to the official List Of Vars
int addVar(VAR *newVar) {
	for (int i = 0; i < MAX_VAR; i++) {
		if (!var[i]) {
			var[i] = newVar;
			return (0);
		}
	}
	logMsg(LOGERROR, "Cant add new var - MAX_VAR exceeded");
}

VAR *findVarLenient(char *name, char *prefix) {
	// Search using the name as supplied. Mysql fields are always stored fully qualified. Others might have no or many levels of qualifier
	VAR *variable = NULL;
	variable = findVarStrict(name);
	if ((!variable) && (prefix)) {
		// If not found, it might be a non-qualified variable. Stick the current table name (if any) in front of it and try again
		char *tmp = (char *) calloc(1, 4096);
		sprintf(tmp, "%s.%s", prefix, name);
			variable = findVarStrict(tmp);
		free(tmp);
	}
	return variable;
}

VAR *findVarStrict(char *name) {
	for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
		if (!(var[i]))
			break;
		if (!strcmp(var[i]->name, name)) {
			return var[i];
		}
	}
	return NULL;
}

void fillVarDataTypes(VAR *variable, char *value) {
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);	//@@BUG something weird here. The 'if VAR_NUMBER' branch is taken but no value. And valgrind shows a leak
	if (variable->type == VAR_DATE)
		variable->dateValue = safeValue;
	else if (variable->type == VAR_TIME)
		variable->timeValue = safeValue;
	else if (variable->type == VAR_DATETIME)
		variable->datetimeValue = safeValue;
	else if (variable->type == VAR_DECIMAL2) {
		if (safeValue)
			variable->decimal2Value = atof(safeValue);
	} else if (variable->type == VAR_NUMBER) {
		if (safeValue)
			variable->numberValue = atol(safeValue);
	} else
		variable->stringValue = safeValue;
	if (safeValue)
		variable->portableValue = strdup(safeValue);
	else
		variable->portableValue = strdup("");
}

void updateVar(char *qualifiedName, char *value, int type) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateVar\n");
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);
	VAR *seekVar = findVarStrict(qualifiedName);
	if (!seekVar) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->type = type;
		clearVarValues(newVar);
		fillVarDataTypes(newVar, safeValue);
//printf("NON-TABLE-> NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
		for (int i = 0; i < MAX_VAR; i++) {
			if (!var[i]) {
				var[i] = newVar;
				break;
			}
		}
	} else {
		for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
			if (!var[i])
				break;
			if (!strcmp(var[i]->name, qualifiedName)) {
				fillVarDataTypes(var[i], value);
				break;
			}
		}
	}
	if (safeValue)
		free(safeValue);
}

void clearVarValues(VAR *varStruct) {
	if (!varStruct)
		return;
	// Free any existing value
	if (varStruct->portableValue) free(varStruct->portableValue);
	if (varStruct->stringValue) free(varStruct->stringValue);
	if (varStruct->dateValue) free(varStruct->dateValue);
	if (varStruct->timeValue) free(varStruct->timeValue);
	if (varStruct->datetimeValue) free(varStruct->datetimeValue);
	varStruct->portableValue = NULL;
	varStruct->stringValue = NULL;
	varStruct->dateValue = NULL;
	varStruct->timeValue = NULL;
	varStruct->datetimeValue = NULL;
	varStruct->numberValue = 0;
	varStruct->decimal2Value = 0;
}

// Given a string like  [stock.id + ((stock.discount * 100) / stock_tax)+2) + 100]
// Return a string like [123 + ((5.25 * 100) / 20)+2) + 100]
// NEEDS FREEING
char *expandVarsInString(char *str, char *tableName) {
	char *p = str;
	char *newStr = (char *) calloc(1, 4096);
	char *p2 = newStr;
    char *nonWordChars = " +-*/^%()";

	while (*p) {
		if (!strchr(nonWordChars, *p)) {	// found a word
			char *wd = (char *) calloc(1, 4096);
			char *p3 = wd;
			while ((*p) && (!strchr(nonWordChars, *p)))	// isolate the word
				*p3++ = *p++;
			VAR *variable = NULL;
			variable = findVarLenient(wd, tableName);		// does it name a field?
			if (variable) {
/*
				if (char *pMinus = strchr(variable->portableValue, '-'))
					*pMinus = ' ';	//@@TODO decimals (mult by 100)
				if (char *pDot = strchr(variable->portableValue, '.'))
					*pDot = '\0';	//@@TODO decimals (mult by 100)
*/
				p3 = variable->portableValue;				// yes - replace the word with its value
			}
			else
				p3 = wd;								// no - use the word
			while (*p3)
				*p2++ = *p3++;
			free(wd);
		}
		if ((*p) && (strchr(nonWordChars, *p)))
			*p2++ = *p++;
	}
	return newStr;
}

// ----------------------------------------------------------
// General stuff

// Output some content. No sugar added
void emit(char *line) {
	printf("%s", line);
}

void die(const char *errorString) {
	//fprintf(stderr, "%s\n", errorString);
	fprintf(stdout, "%s\n", errorString);
	exit(1);
}

void jamDump(int which) {
	char *tmp = (char *) calloc(1, 4096);
	printf("<br><br><div style='font-size:11px;color:#ffffff;background-color:#1b2426'>");
	if ((which == 0) || (which == 1)) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (jam[i] == NULL)
				break;
			//printf("%02d JAMDUMP: %s >>>>>%s<<<<<\n\n\n", i, jam[i]->command, jam[i]->trailer);
			printf("%02d JAMDUMP %s : %s<br>", i, jam[i]->command, jam[i]->args);
		}
	}
	if (which == 0)
		printf("<hr>");
	if ((which == 0) || (which == 2)) {
		for (int i = 0; i < MAX_VAR; i++) {
			if (var[i] == NULL)
				break;

			printf("<span");
			if (var[i]->debugHighlight == 1) printf(" style='color:#decde3'");
			if (var[i]->debugHighlight == 2) printf(" style='color:yellow;'");
			if (var[i]->debugHighlight == 3) printf(" style='color:orange;'");
			if (var[i]->debugHighlight == 4) printf(" style='color:#a8c968;'");
			if (var[i]->debugHighlight == 5) printf(" style='color:#e28c86;'");
			if (var[i]->debugHighlight == 6) printf(" style='color:cyan;'");
			printf(">");

			*tmp = 0;
			if (var[i]->source)
				sprintf(tmp, " : source %s", var[i]->source);
			if (var[i]->type == VAR_STRING)
				printf("%02d VARDUMP %s : VAR_STRING %s %s<br>", i, var[i]->name, var[i]->stringValue, tmp);
			if (var[i]->type == VAR_NUMBER)
				printf("%02d VARDUMP %s : VAR_NUMBER %ld %s<br>", i, var[i]->name, var[i]->numberValue, tmp);
			if (var[i]->type == VAR_DECIMAL2)
				printf("%02d VARDUMP %s : VAR_DECIMAL2 %.2f %s<br>", i, var[i]->name, var[i]->decimal2Value, tmp);
			printf("</span>");
		}
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:#decde3;'>prefill </span>");
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:yellow;'>count </span>");
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:orange;'>sum </span>");
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:#a8c968;'>variable </span>");
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:#e28c86;'>mysql </span>");
		printf("<span style='margin:3px; padding:2px; color:#000; background-color:cyan;'>list </span>");
	}
	printf("<br>");
	printf("</div>");
	free(tmp);
}

//--------------------------------------------------------------------
// Calculations


int isCalculation(char *str) {
	if ((strstr(str, " + ")) || (strstr(str, " - ")) || (strstr(str, " * ")) || (strstr(str, " / "))
	||  (strstr(str, " ^ ")) || (strstr(str, " % ")) || (strchr(str, '(')) || (strchr(str, ')')))
		return 1;
/*	if ((strchr(str, '+')) || (strchr(str, '-')) || (strchr(str, '*')) || (strchr(str, '/'))
	||  (strchr(str, '^')) || (strchr(str, '%')) || (strchr(str, '(')) || (strchr(str, ')')))
		return 1; */
	return 0;
}

// Call the calculator
char *calculate(char *str) {
	// Check if date calculation
	char *space = " ";
	char *wd;
	int wdNum = 0;
	while (1) {
		wd = strTrim(getWordAlloc(str, ++wdNum, space));
		if (!wd)
			break;
		int numOfMinuses = 0;
		char *p = wd;
		while (*p) {
			if (*p == '-')
				numOfMinuses++;
			p++;
		}
		if (numOfMinuses > 1) {
		}
	}

	int scale = 2;
	FILE *fp;
	char *result = (char *) calloc(1, 4096);
	strcpy(result, "0");
	char *commandStr = (char *) calloc(1, 4096);
	sprintf(commandStr, "echo 'scale=%d; %s' | bc", scale, str);
	fp = popen(commandStr, "r");
	if (fp == NULL) {
		printf("calculator failed (1)\n");
	} else {
		if (fgets(result, 4096, fp) != NULL) {
			char *p = strchr(result, '\n');
			if (*p)
				*p = '\0';
		}
		pclose(fp);
	}
//printf("\n *** [%s][%s] *** \n", str, result);
	free(commandStr);
	return result;
}

//--------------------------------------------------------------------
// Template parse extractors
