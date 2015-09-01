#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordMisc.h"
#include "common.h"
#include "stringUtil.h"
#include "list.h"
#include "log.h"

int wordMiscInclude(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

	char *buf = NULL;
	sprintf(tmp, "%s/%s", documentRoot, args);
	std::ifstream includeFile (tmp, std::ifstream::binary);
	if (!includeFile){
			char *error = (char *) calloc(1, 4096);
		sprintf(error, "@include : cant find file %s", tmp);
		die(error);
	}
	includeFile.seekg (0, includeFile.end);
	int length = includeFile.tellg();
	includeFile.seekg (0, includeFile.beg);
	buf = (char *) calloc(1, length+1);
	if (!buf) {
		sprintf(tmp, "cant calloc memory to @include %s", args);
		die(tmp);
	}
	// Emit any pre-tags
	if (strstr(args, ".css"))
		emit("<style>");
	includeFile.read (buf,length);
	buf[length] = 0;
	includeFile.close();
	if (!includeFile) {
		sprintf(tmp, "error: not the whole of %s could be read", args);
		die(tmp);
	}
	emit(buf);
	free(buf);
	// Emit any post-tags
	if (strstr(args, ".css"))
	emit("</style>");
	emit(jam[ix]->trailer);
    free(tmp);

}

int wordMiscCount(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    char *space = " ";
    getWord(tmp, args, 1, space);
    char *countFieldName = strdup(tmp);
    if (!countFieldName)
        die("Cant count a nonexistent field");
    char *countFieldAs = NULL;
    getWord(tmp, args, 2, space);
    if (!(strcmp(tmp, "as"))) {
        getWord(tmp, args, 3, space);
        countFieldAs = strdup(tmp);
    }
    // Lookup the variable we want to count. It might be qualified, but if it isnt then qualify it with the current table name
    VAR *varToCount = NULL;
    char *varToCountQualifiedName = (char *) calloc(1, 512);
    if (strchr(countFieldName, '.'))
        strcpy(varToCountQualifiedName, countFieldName);
    else
        sprintf(varToCountQualifiedName, "%s.%s", defaultTableName, countFieldName);
    varToCount = findVarStrict(varToCountQualifiedName);
    if (!varToCount) {
        sprintf(tmp, "variable to count doesnt exist :( Was looking for tableName=[%s] and countFieldName=[%s]\n",defaultTableName, countFieldName);
        die(tmp);
    }
    // Lookup the variable we want to count as (into). If it is unnamed prepend '.count' to it
    VAR *varToCountAs = NULL;
    if (!countFieldAs)
        sprintf(tmp, "count.%s", countFieldName);
    else
        sprintf(tmp, "%s", countFieldAs);
    varToCountAs = findVarStrict(tmp);
    if (!varToCountAs) {
        char *val = "0";
        updateVar(tmp, val, VAR_NUMBER);
        varToCountAs = findVarStrict(tmp);
        varToCountAs->source = strdup("count");
        varToCountAs->debugHighlight = 2;
    }
    varToCount->debugHighlight = 2;
    // Do the count	@@TODOCALC
    varToCountAs->numberValue++;
    free(varToCountAs->portableValue);
    sprintf(tmp, "%ld", varToCountAs->numberValue);
    varToCountAs->portableValue = strdup(tmp);
    // Wrap up
    free(countFieldName);
    free(countFieldAs);
    free(varToCountQualifiedName);
    free(tmp);
    emit(jam[ix]->trailer);
}

int wordMiscSum(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    char *space = " ";
    getWord(tmp, args, 1, space);
    char *sumFieldName = strdup(tmp);
    if (!sumFieldName)
        die("Cant sum a nonexistent field");
    char *sumFieldAs = NULL;
    getWord(tmp, args, 2, space);
    if (!(strcmp(tmp, "as"))) {
        getWord(tmp, args, 3, space);
        sumFieldAs = strdup(tmp);
    }
    // Lookup the variable we want to sum. It might be qualified, but if it isnt then qualify it with the current table name
    VAR *varToSum = NULL;
    char *varToSumQualifiedName = (char *) calloc(1, 512);
    if (strchr(sumFieldName, '.'))
        strcpy(varToSumQualifiedName, sumFieldName);
    else
        sprintf(varToSumQualifiedName, "%s.%s", defaultTableName, sumFieldName);
    varToSum = findVarStrict(varToSumQualifiedName);
    if (!varToSum) {
        sprintf(tmp, "variable to sum doesnt exist :( Was looking for tableName=[%s] and sumFieldName=[%s]\n",defaultTableName, sumFieldName);
        die(tmp);
    }
    // Lookup the variable we want to sum as (into). If it is unnamed prepend '.sum' to it
    VAR *varToSumAs = NULL;
    if (!sumFieldAs)
        sprintf(tmp, "sum.%s", sumFieldName);
    else
        sprintf(tmp, "%s", sumFieldAs);
    varToSumAs = findVarStrict(tmp);
    if (!varToSumAs) {
        char *val = "0";
        updateVar(tmp, val, varToSum->type);
        varToSumAs = findVarStrict(tmp);
        varToSumAs->source = strdup("sum");
        varToSumAs->debugHighlight = 3;
    }
    varToSum->debugHighlight = 3;
    // Do the sum	@@TODOCALC
    if (varToSum->type == VAR_NUMBER) {
        varToSumAs->numberValue += varToSum->numberValue;
        free(varToSumAs->portableValue);
        sprintf(tmp, "%ld", varToSumAs->numberValue);
        varToSumAs->portableValue = strdup(tmp);
    }
    else if (varToSum->type == VAR_DECIMAL2) {
        varToSumAs->decimal2Value += varToSum->decimal2Value;
        free(varToSumAs->portableValue);
        sprintf(tmp, "%.2lf", varToSumAs->decimal2Value);
        varToSumAs->portableValue = strdup(tmp);
    }
    // Wrap up
    free(sumFieldName);
    free(sumFieldAs);
    free(varToSumQualifiedName);
    emit(jam[ix]->trailer);
    free(tmp);
}

int wordMiscNewList(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *listName = (char *) calloc(1, 4096);
	char *listCommand = (char *) calloc(1, 4096);
	char *listCommandArgs = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(listName, args, 2, " \t");
	if (!listName)
		logMsg(LOGERROR, "missing list name to create");

	VAR *variable = NULL;
	variable = findVarStrict(listName);
	if (variable) {						// list exists - kill it
		listRemove(listName);
		clearVarValues(variable);
		free(variable);
	}
	// Create the list VAR with a listcount value of 0
	// NB: The VAR is fully qualified with "_list." but the LIST is NOT
	variable = (VAR *) calloc(1, sizeof(VAR));
	variable->name = strdup(listName);
	variable->type = VAR_STRING;
	clearVarValues(variable);
	fillVarDataTypes(variable, "");
	logMsg(LOGDEBUG, "Initializing list variable %s with no value", variable->name);
	variable->source = strdup("list");
	variable->debugHighlight = 6;
	if (addVar(variable) == -1) {
		logMsg(LOGFATAL, "Cant create any more vars, terminating");
		exit(1);
	}
	// Create the list
	int status = listCreate(listName);
	if (status == -1) {
		logMsg(LOGERROR, "Cant create list %s", listName);
	}
	// Is there an initialiser command?
	getWord(listCommand, args, 3, " \t");
	if (listCommand) {
		if (!strcasecmp(listCommand, "dir")) {
			getWord(listCommandArgs, args, 4, " \t");
			char *result = (char *) calloc(1, 10000000);	// 10 mb
			char *commandStr = (char *) calloc(1, 4096);
			sprintf(commandStr, "ls %s/%s", documentRoot, listCommandArgs);
			logMsg(LOGDEBUG, "Attempt to list dir [%s]", commandStr);
			FILE *fp = popen(commandStr, "r");
			if (fp == NULL) {
				logMsg(LOGERROR, "list dir failed");
			} else {
				while(fgets(result, 4096, fp) != NULL) {
					char *pos;
					if ((pos = strchr(result, '\n')) != NULL)
    					*pos = '\0';
					char *store = (char *) listAlloc(strlen(result) + 1);
					strcpy(store, result);
					listAdd(listName, store);
					logMsg(LOGMICRO, "added [%s] to list [%s]", result, listName);
				}
				pclose(fp);
				logMsg(LOGDEBUG, "new list [%s] created", listName);
			}
			free(commandStr);
			free(result);
		}
	}
	// Wrap up
    free(tmp);
    free(listName);
    free(listCommand);
    free(listCommandArgs);
    emit(jam[ix]->trailer);
}

int wordMiscEmail(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *mailFrom = (char *) calloc(1, 4096);
	char *mailTo = (char *) calloc(1, 4096);
	char *mailSubject = (char *) calloc(1, 4096);
	char *mailBody = (char *) calloc(1, 20000000);	//@@TODO 20 mb max email body. And tmp
	char *tmp = (char *) calloc(1, 20000000);

	getWord(mailFrom, args, 1, " \t");
	if (!mailFrom) {
		logMsg(LOGERROR, "missing 'from' email");
		return(-1);
	}
	getWord(mailTo, args, 2, " \t");
	if (!mailTo) {
		logMsg(LOGERROR, "missing 'to' email");
		return(-1);
	}
	getWord(mailSubject, args, 3, " \t");
	if (!mailSubject) {
		logMsg(LOGERROR, "missing email subject");
		return(-1);
	}

	int sanity = 0;
	int cnt = 4;
	strcpy(mailBody, "");
	while (1) {
		if (++sanity > 100) { printf("Overflow in mailbody!"); break; }
		getWord(tmp, args, cnt++, " ");
		if (strlen(tmp) == 0)
			break;
		strcat(mailBody, " ");
		strcat(mailBody, tmp);
	}

	char *mailExpandedBody = expandVarsInString(mailBody, defaultTableName);    // Allocates memory - needs freeing

	logMsg(LOGDEBUG, "Try to send via sendmail. From [%s] To [%s] Subject [%s]", mailFrom, mailTo, mailSubject);
	FILE *mailpipe = popen("/usr/sbin/sendmail -t", "w");
	if (mailpipe == NULL) {
		logMsg(LOGERROR, "Failed to popen sendmail");
		return(-1);
	}
	logMsg(LOGDEBUG, "Mailpipe popen ok. Sending via sendmail");
	fprintf(mailpipe, "From: %s\n", mailFrom);
	fprintf(mailpipe, "To: %s\n", mailTo);
	fprintf(mailpipe, "Subject: %s\n\n", mailSubject);
	fwrite(mailExpandedBody, 1, strlen(mailExpandedBody), mailpipe);
	fwrite(".\n", 1, 2, mailpipe);
	pclose(mailpipe);

	// Wrap up
	free(mailExpandedBody);
    free(tmp);
    free(mailFrom);
    free(mailTo);
    free(mailSubject);
    free(mailBody);
    emit(jam[ix]->trailer);
}
