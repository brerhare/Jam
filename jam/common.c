#include <stdio.h>
#include <stdlib.h>
#include <stdarg.h>
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

CURL *curl = NULL;

#define MAX_EMITHEADER_LEN 409600
char *emitHeaderBuffer = (char *) calloc(1, MAX_EMITHEADER_LEN);
char *emitHeaderPos = emitHeaderBuffer;
int emitHeaderRemaining = MAX_EMITHEADER_LEN;

#define MAX_EMITDATA_LEN 40960000
char *emitStdBuffer = (char *) calloc(1, MAX_EMITDATA_LEN);
char *emitStdPos = emitStdBuffer;
int emitStdRemaining = MAX_EMITDATA_LEN;

#define MAX_EMITJS_LEN 40960000
char *emitJsBuffer = (char *) calloc(1, MAX_EMITJS_LEN);
char *emitJsPos = emitJsBuffer;
int emitJsRemaining = MAX_EMITJS_LEN;

// Scratch is used as a temp buffer for email body
#define MAX_EMITSCRATCH_LEN 40960000
char *emitScratchBuffer = (char *) calloc(1, MAX_EMITSCRATCH_LEN);
char *emitScratchPos = emitScratchBuffer;
int emitScratchRemaining = MAX_EMITSCRATCH_LEN;

FILE *emitStream = stdout;

int outputStream = 0;			// copy of the JAMBUILDER stream (eg for emitStd)

int oobDataRequested = 0;		// Some ajax calls will ask for this

int urlEncodeRequired = 0;

int cmdSeqnum = 0;				// every @jamcommand has a unique sequence number or id. Can be used for unique field names in grids

char *oobFileName = "/tmp/oobData.tmp";

int LAST_VAR = -1;				// used globally to indicate the current top

//-----------------------------------------------------------
// Var related

// Add a fully populated VAR to the official List Of Vars
int addVar(VAR *newVar) {
	for (int i = 0; i < MAX_VAR; i++) {
		if (!var[i]) {
			var[i] = newVar;
			if (i > LAST_VAR)
				LAST_VAR = i;
			return (0);
		}
	}
	logMsg(LOGFATAL, "Cant add new var - MAX_VAR exceeded");
	return(-1);
}

void deleteVar(VAR *delVar) {
	if (delVar == NULL) {
		logMsg(LOGERROR, "deleteVar() asked to delete NULL var");
	}
	for (int i = 0; i <= LAST_VAR; i++) {
		if (var[i] == delVar) {
			if (var[i]->source)			free(var[i]->source);
			if (var[i]->portableValue)	free(var[i]->portableValue);
			if (var[i]->stringValue)	free(var[i]->stringValue);
			if (var[i]->dateValue)		free(var[i]->dateValue);
			if (var[i]->timeValue)		free(var[i]->timeValue);
			if (var[i]->datetimeValue)	free(var[i]->datetimeValue);
			free(var[i]);
			var[i] = NULL;
			//logMsg(LOGMICRO, "deleteVar() deleted var");
			return;
		}
	}
	logMsg(LOGERROR, "deleteVar() didnt find requested var to delete");
}

// Quick create a string var
void setVarAsString(char *name, char *value) {
	updateVar(name, value, VAR_STRING);
}

// Quick create a number var
void setVarAsNumber(char *name, long value) {
	char num[16];
	sprintf(num, "%ld", value);
	updateVar(name, num, VAR_NUMBER);
}

void unsetVar(char *name) {
	VAR *variable = NULL;
	variable = findVarStrict(name);
	if (variable)
		deleteVar(variable);
}

// Return the string value (portable value) of the named var
// Try strict first, then non-strict
char *getVarAsString(char *name) {
	VAR *variable = NULL;
	variable = findVarStrict(name);
	if (variable)
		return variable->portableValue;
	return NULL;
}

int isVar(char *name) {
	VAR *variable = NULL;
	variable = findVarStrict(name);
	if (variable)
		return 1;
	return 0;
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
	for (int i = 0; i <= LAST_VAR; i++) {
		if (!(var[i]))
			continue;
//logMsg(LOGMICRO, "findVarStrict() checking [%s]=[%s]", var[i]->name, name);
		if (!strcasecmp(var[i]->name, name)) {
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
		emitStd("NULL 'qualifiedName' passed to updateVar\n");
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
//emitStd("NON-TABLE-> NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
		addVar(newVar);
	} else {
		for (int i = 0; (i <= LAST_VAR); i++) {
			if (!var[i])
				continue;
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
//			logMsg(LOGMICRO, "expandVarsInString looking to expand word [%s] ...", wd);
			variable = findVarLenient(wd, tableName);		// does it name a field?
			if (variable) {
//				logMsg(LOGMICRO, " ... expandVarsInString expanded word [%s] to [%s]", wd, variable->portableValue);

/*
				if (char *pMinus = strchr(variable->portableValue, '-'))
					*pMinus = ' ';	//@@TODO decimals (mult by 100)
				if (char *pDot = strchr(variable->portableValue, '.'))
					*pDot = '\0';	//@@TODO decimals (mult by 100)
*/

				p3 = variable->portableValue;				// yes - replace the word with its value
			}
			else {
//				logMsg(LOGMICRO, "... expandVarsInString did not expand word [%s]", wd);
				p3 = wd;								// no - use the word
			}
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

void die(const char *errorString) {
	//fprintf(stderr, "%s\n", errorString);
	fprintf(stdout, "%s\n", errorString);
	exit(1);
}

void jamDump(int which) {
	char *tmp = (char *) calloc(1, 4096);
	emitStd("<br><br><div style='font-size:11px;color:#ffffff;background-color:#1b2426'>");
	if ((which == 2) || (which == 3)) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (jam[i] == NULL)
				break;
			emitStd("%02d JAMDUMP %s : %s<br>", i, jam[i]->command, jam[i]->args);
		}
	}
	if (which == 3)
		emitStd("<hr>");
	if ((which == 1) || (which == 3)) {
		for (int i = 0; i <= LAST_VAR; i++) {
			if (var[i] == NULL)
				continue;

			emitStd("<span");
			if (var[i]->debugHighlight == 1) emitStd(" style='color:#decde3'");
			if (var[i]->debugHighlight == 2) emitStd(" style='color:yellow;'");
			if (var[i]->debugHighlight == 3) emitStd(" style='color:orange;'");
			if (var[i]->debugHighlight == 4) emitStd(" style='color:#a8c968;'");
			if (var[i]->debugHighlight == 5) emitStd(" style='color:#e28c86;'");
			if (var[i]->debugHighlight == 6) emitStd(" style='color:cyan;'");
			if (var[i]->debugHighlight == 7) emitStd(" style='color:white;'");
			emitStd(">");

			*tmp = 0;
			if (var[i]->source)
				sprintf(tmp, " : source %s", var[i]->source);
			if (var[i]->type == VAR_STRING)
				emitStd("%02d VARDUMP %s : VAR_STRING %s %s<br>", i, var[i]->name, var[i]->stringValue, tmp);
			if (var[i]->type == VAR_NUMBER)
				emitStd("%02d VARDUMP %s : VAR_NUMBER %ld %s<br>", i, var[i]->name, var[i]->numberValue, tmp);
			if (var[i]->type == VAR_DECIMAL2)
				emitStd("%02d VARDUMP %s : VAR_DECIMAL2 %.2f %s<br>", i, var[i]->name, var[i]->decimal2Value, tmp);
			emitStd("</span>");

			*tmp = 0;
			if (var[i]->source)
				sprintf(tmp, " : source %s", var[i]->source);
			if (var[i]->type == VAR_STRING)
				logMsg(LOGDEBUG, "%02d VARDUMP %s : VAR_STRING %s %s", i, var[i]->name, var[i]->stringValue, tmp);
			if (var[i]->type == VAR_NUMBER)
				logMsg(LOGDEBUG, "%02d VARDUMP %s : VAR_NUMBER %ld %s", i, var[i]->name, var[i]->numberValue, tmp);
			if (var[i]->type == VAR_DECIMAL2)
				logMsg(LOGDEBUG, "%02d VARDUMP %s : VAR_DECIMAL2 %.2f %s", i, var[i]->name, var[i]->decimal2Value, tmp);
		}
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:#decde3;'>prefill </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:yellow;'>count </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:orange;'>sum </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:#a8c968;'>variable </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:#e28c86;'>mysql </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:cyan;'>list </span>");
		emitStd("<span style='margin:3px; padding:2px; color:#000; background-color:white;'>arg </span>");
	}
	emitStd("<br>");
	emitStd("</div>");
	free(tmp);
}

int emitHeader(char *str, ...) {
	va_list ap;
	va_start(ap, str);
// @@BUG Overflow needs checked. See http://stackoverflow.com/questions/7215921/possible-buffer-overflow-vulnerability-for-va-list-in-c and http://linux.die.net/man/3/vsnprintf for ideas
	unsigned long len = vsnprintf(emitHeaderPos, emitHeaderRemaining, str, ap);
	emitHeaderPos += len;
	*emitHeaderPos = '\0';
	emitHeaderRemaining -= len;

	sprintf(emitHeaderPos, "\r\n");
	emitHeaderPos += 2;
	emitHeaderRemaining -= 2;
	va_end(ap);
}

int emitStd(char *str, ...) {
	va_list ap;
	va_start(ap, str);

	if (outputStream == STREAMOUTPUT_JS) {	//@@TODO This whole stream handling is messy for jamBuilder...
		logMsg(LOGDEBUG, "jamBuilder 'STREAMOUTPUT_JS' is on so emitStd is redirected to emitJs");
		unsigned long len = vsnprintf(emitJsPos, emitJsRemaining, str, ap);
		emitJsPos += len;
		*emitJsPos = '\0';
		emitJsRemaining -= len;
		va_end(ap);
		return(0);
	}

// @@BUG Overflow needs checked. See http://stackoverflow.com/questions/7215921/possible-buffer-overflow-vulnerability-for-va-list-in-c and http://linux.die.net/man/3/vsnprintf for ideas
	unsigned long len = vsnprintf(emitStdPos, emitStdRemaining, str, ap);
	emitStdPos += len;
	*emitStdPos = '\0';
	emitStdRemaining -= len;
	va_end(ap);
}

int emitJs(char *str, ...) {
	va_list ap;
	va_start(ap, str);
// @@BUG Overflow needs checked. See http://stackoverflow.com/questions/7215921/possible-buffer-overflow-vulnerability-for-va-list-in-c and http://linux.die.net/man/3/vsnprintf for ideas
	unsigned long len = vsnprintf(emitJsPos, emitJsRemaining, str, ap);
	emitJsPos += len;
	*emitJsPos = '\0';
	emitJsRemaining -= len;
	va_end(ap);
}

int endHeader() {
	char *p = emitHeaderBuffer;
	while (*p) {
		putchar(*p++);
	}
	printf("\r\n");
//logMsg(LOGDEBUG, "ENDHEADER=[%s]", emitHeaderBuffer);
}

int endStd(int urlEncodeRequired) {
	char *p = emitStdBuffer;
	char *encodedData = NULL;
	if (urlEncodeRequired) {
		encodedData = urlEncode(emitStdBuffer);
		p = encodedData;
	}
	while (*p)
		putchar(*p++);
	if (encodedData)
		free(encodedData);
logMsg(LOGMICRO, "ENDDATA=[%s]", emitStdBuffer);
}

int endJs(int urlEncodeRequired) {
//return(0);
	char *p = NULL;
	if (strlen(emitJsBuffer)) {
		p = emitJsBuffer;

		char *encodedJs = NULL;
		if (urlEncodeRequired) {
			encodedJs = urlEncode(p);
			p = encodedJs;
		}
	// @@KIM <script> tag
		emitStd("\n\n<script id='endJs'>\n");
		emitStd(p);
		logMsg(LOGDEBUG, "endJs generated <script> tags enclosing : [%s]", p);
	// @@KIM <script> tag
		emitStd("</script>\n");
		if (encodedJs)
			free(encodedJs);
	} else {
		logMsg(LOGDEBUG, "endJs had nothing to output");
	}
}

char *urlEncode(char *str) {		// needs freeing
char *p = strdup(str);
return p;
	char *encodedData = NULL;
	curl = curl_easy_init();
	if (!curl)
		logMsg(LOGERROR, "Cant easy_init curl! Wont even try to urlencode");
	else
		encodedData = curl_easy_escape(curl, emitStdBuffer, 0);
	if (!encodedData) {
		logMsg(LOGERROR, "Cant easy_escape curl! Handing back original unencoded string");
		encodedData = strdup(str);
	}
	curl_easy_cleanup(curl);
	return (encodedData);
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
		emitStd("calculator failed (1)\n");
	} else {
		if (fgets(result, 4096, fp) != NULL) {
			char *p = strchr(result, '\n');
			if (*p)
				*p = '\0';
		}
		pclose(fp);
	}
//emitStd("\n *** [%s][%s] *** \n", str, result);
	free(commandStr);
	return result;
}

// ----------------------------------------------------------
// Out of band vars

// @action - out of band var 'dump' to a <div>
int oobJamData() {
//return 0; //@@KIM
//	FILE *fp = fopen(oobFileName, "w");
//	if (fp == NULL) {
//		logMsg(LOGFATAL, "Cant open oob file '%s'", oobFileName);
//		return(-1);
//	}
	int first = 1;
	logMsg(LOGDEBUG, "Emitting oob jamData");
	emitStd("{oobData}");
	emitStd("[");
	for (int i = 0; i < MAX_VAR; i++) {
		if (var[i] == NULL)
			break;
		if (first)
			first = 0;
		else
			emitStd(", ");

		char *nameJSON = escapeJsonChars(var[i]->name);
		char *valueJSON = escapeJsonChars(var[i]->portableValue);
		// Avoid single quotes - the formal JSON spec doesnt allow them
		emitStd("{\"name\":\"%s\", \"value\":\"%s\"}", nameJSON,  valueJSON);
		free(nameJSON);
		free(valueJSON);
	}
	emitStd("]");
//	fclose(fp);
	logMsg(LOGDEBUG, "Finished emitting oob jamData");
	//fflush(stdout);

}

