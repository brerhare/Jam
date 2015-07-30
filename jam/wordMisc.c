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
