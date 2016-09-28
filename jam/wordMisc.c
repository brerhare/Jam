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
#include "database.h"
#include "stringUtil.h"
#include "list.h"
#include "log.h"


// {@postcodeextract type=areadistrict postcode='DG6 4TP'}
int wordMiscWordSplit(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);
    char *wordField = NULL;
    char *word = NULL;
    char *wordNum = NULL;
    char *split = (char *) calloc(1, 4096);

	wordField = strdup(getVarAsString("sys.control.field"));
	wordNum = strdup(getVarAsString("sys.control.segment"));
	logMsg(LOGDEBUG, "wordMiscWordSplit field required is [%s] and segment required is [%s]", wordField, wordNum);

	word = strdup(getVarAsString(wordField));

	getWord(split, word, atoi(wordNum), " ");
	emitStd(split);

	logMsg(LOGDEBUG, "wordMiscWordSplit field value is [%s] and segment value is [%s]", word, split);

	emitStd(jam[ix]->trailer);

	free(tmp);
	free(wordField);
	free(word);
	free(wordNum);
	free(split);
}

int wordMiscReplaceValue(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);
	char *values = NULL;
	char *fieldVar = NULL;
	char *fieldValue = NULL;

	char *table = NULL;
	char *field = NULL;
	char *tableFieldRawValue = NULL;

    // [Table].field
    char *p = getVarAsString("sys.control.field");
    if (!p) {
        logMsg(LOGERROR, "Misc replacevalue cant be null");
        return(-1);
    }
    tableFieldRawValue = strdup(getVarAsString("sys.control.field"));
    if (strchr(p, '.')) {       // has a named table
        table = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
        field = getWordAlloc(getVarAsString("sys.control.field"), 2, ".");
    } else {                    // its just the field name
        if (defaultTableName)
            table = strdup(defaultTableName);
        else
            table = strdup("");
        field = getWordAlloc(getVarAsString("sys.control.field"), 1, ".");
		fieldVar = strdup(getVarAsString(field));
    }

    // Value (can be multiple choices). Eg value=0:Male,1:Female
    if (isVar("sys.control.values"))
        values = strdup(getVarAsString("sys.control.values"));
    else
        values = strdup("");


	// Get the actual field value we want to replace with something else
    sprintf(tmp, "%s.%s", table, field);
    VAR *variable = findVarStrict(tmp);
    if (variable) {
        fieldValue = strdup(variable->portableValue);
	}
	if (!variable) {
        logMsg(LOGERROR, "Misc replacevalue cant get field's actual value, so cant replace");
        return(-1);
	}

	int cnt = 1;
	while (1) {
		char *opt = getWordAlloc(values, cnt++, ",");
		if ((!opt) || (!strlen(opt)))
			break;
		char *optA = strTrim(getWordAlloc(opt, 1, ":"));
		char *optB = strTrim(getWordAlloc(opt, 2, ":"));
		if ( (!optA) || (!strlen(optA)) )
			continue;
		if ( (!optB) || (!strlen(optB)) ) {
			free(opt);
			free(optA);
			continue;
		}
		if ((fieldValue) && (strlen(fieldValue))) {
			if (!strcmp(optA, fieldValue)) {
				//sprintf(tmp, "[%d] : [%s][%s]", (int) strlen(fieldValue), fieldValue, optB);
				emitStd(optB);
				break;
			}
		}
		//sprintf(tmp, "X:(%s):%s=%s", fieldValue, optA, optB);	
		//emitStd(tmp);
		free(optA);
		free(optB);
	}

	emitStd(jam[ix]->trailer);

	free(tmp);
    free(table);
    free(field);
	free(values);
	if (fieldVar) free(fieldVar);
	if (fieldValue) free(fieldValue);
    free(tableFieldRawValue);
}

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
		emitStd("<style>");
	includeFile.read (buf,length);
	buf[length] = 0;
	includeFile.close();
	if (!includeFile) {
		sprintf(tmp, "error: not the whole of %s could be read", args);
		die(tmp);
	}
	emitStd(buf);
	free(buf);
	// Emit any post-tags
	if (strstr(args, ".css"))
	emitStd("</style>");
	emitStd(jam[ix]->trailer);
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
    if (!countFieldName) {
        logMsg(LOGERROR, "Cant count a nonexistent field");
        return (-1);
    }
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

//logMsg(LOGERROR, "About to look for tableName=[%s] and countFieldName=[%s], together being [%s]", defaultTableName, countFieldName, varToCountQualifiedName);


    varToCount = findVarStrict(varToCountQualifiedName);
	if (!varToCount) {
        logMsg(LOGERROR, "variable to count doesnt exist :( Was looking for tableName=[%s] and countFieldName=[%s], together being [%s]", defaultTableName, countFieldName, varToCountQualifiedName);
        jamDump(1);
        exit(1); //return (-1);
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
    emitStd(jam[ix]->trailer);
}

int wordMiscSum(int ix, char *defaultTableName) {
    char *cmd = jam[ix]->command;
    char *args = jam[ix]->args;
    char *rawData = jam[ix]->rawData;
    char *tmp = (char *) calloc(1, 4096);

    char *space = " ";
    getWord(tmp, args, 1, space);
    char *sumFieldName = strdup(tmp);
    if (!sumFieldName) {
        logMsg(LOGERROR, "Cant sum a nonexistent field");
        return(-1);
    }
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
        logMsg(LOGERROR, "variable to sum doesnt exist :( Was looking for tableName=[%s] and sumFieldName=[%s]\n",defaultTableName, sumFieldName);
        return(-1);
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
    emitStd(jam[ix]->trailer);
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
    emitStd(jam[ix]->trailer);
}

int wordMiscDayCount(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 4096);
	char *dateFromField = (char *) calloc(1, 4096);
	char *dateToField = (char *) calloc(1, 4096);
	char *dateFrom = NULL;
	char *dateTo = NULL;
	char wd[256];

	getWord(dateFromField, args, 1, " \t");
	if (!dateFromField) {
		logMsg(LOGERROR, "missing 'date from");
		return(-1);
	}

	getWord(dateToField, args, 2, " \t");
	if (!dateToField) {
		logMsg(LOGERROR, "missing 'date to");
		return(-1);
	}

	dateFrom = strdup(getVarAsString(dateFromField));
	dateTo = strdup(getVarAsString(dateToField));

	getWord(wd, dateFrom, 3, "-");
	struct tm a = {0,0,0,0,0,0};
	a.tm_mday = atoi(wd);
	getWord(wd, dateFrom, 2, "-");
	a.tm_mon = (atoi(wd) - 1);
	getWord(wd, dateFrom, 1, "-");
	a.tm_year = (atoi(wd) - 1900);

	struct tm b = {0,0,0,0,0,0};
	getWord(wd, dateTo, 3, "-");
	b.tm_mday = atoi(wd);
	getWord(wd, dateTo, 2, "-");
	b.tm_mon = (atoi(wd) - 1);
	getWord(wd, dateTo, 1, "-");
	b.tm_year = (atoi(wd) - 1900);

	time_t x = mktime(&a);
	time_t y = mktime(&b);
	if ( x != (time_t)(-1) && y != (time_t)(-1) ) {
		if ( (a.tm_year > 0) && (a.tm_year < 200) && (b.tm_year > 0) && (b.tm_year < 200) && (a.tm_year <= b.tm_year) ) {
			double difference = difftime(y, x) / (60 * 60 * 24);
			if (difference > 0) {
				sprintf(tmp, "%ld", (long) difference);
				emitStd(tmp);
			}
		}
	}

//sprintf(tmp, "[%s %s]", dateFrom, dateTo);
//emitStd(tmp);

    free(tmp);
    free(dateFromField);
    free(dateToField);
    free(dateFrom);
    free(dateTo);
    emitStd(jam[ix]->trailer);
}

int wordMiscType(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tmp = (char *) calloc(1, 4096);
	char *fileName = (char *) calloc(1, 4096);

	getWord(fileName, args, 1, " \t");
	if (!fileName) {
		logMsg(LOGERROR, "missing 'file name' to type");
		return(-1);
	}

	FILE *fp = fopen(fileName, "r");
	if (fp == NULL) {
		logMsg(LOGERROR, "Error opening file '%s'", fileName);
		return(-1);
	}
	fseek(fp, 0, SEEK_END);
	long len = ftell(fp);
	char *buf = (char *) malloc(len);
	fseek(fp, 0, SEEK_SET);
	fread(buf, 1, len, fp);
	fclose(fp);
	emitStd(buf);

    free(tmp);
    free(fileName);
    free(buf);
    emitStd(jam[ix]->trailer);
}

int wordMiscEmail(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *expandedArgs = expandVarsInString(jam[ix]->args, defaultTableName);    // Allocates memory - needs freeing
	char *args = expandedArgs;
	char *rawData = jam[ix]->rawData;
	char *mailFrom = (char *) calloc(1, 4096);
	char *mailTo = (char *) calloc(1, 4096);
	char *mailSubject = (char *) calloc(1, 4096);
	char *mailBody = (char *) calloc(1, 20000000);	//@@TODO 20 mb max email body. And tmp
	char *tmp = (char *) calloc(1, 20000000);
	char *p = NULL;

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

	if (p = strstr(args, "action:")) {
		char action[128], *p2 = action;
		p += 7;
		while ((*p) && (*p != ' '))
			*p2++ = *p++;
		*p2 = '\0';
		logMsg(LOGDEBUG, "Email calling action [%s] for body content", action);
		// Temporarily redirect emitStd to emitScratch and run the action
		char *saveStdPos = emitStdPos;
		int saveStdRemaining = emitStdRemaining;
		emitStdPos = emitScratchPos;
		emitStdRemaining = emitScratchRemaining;
		// Copied @runaction block starts ------------------------------
		int startIx = 0;
		int aix = 0;
		while (jam[aix]) {
			if ((!strcmp(jam[aix]->command, "@action")) && (!strcmp(jam[aix]->args, action))) {
				// Set startpoint
				startIx = aix;
				break;
			}
			aix++;
		}
		if (startIx == 0)
			logMsg(LOGERROR, "Cant find action [%s] to run from within email handler", action);
		else
			logMsg(LOGINFO, "Running @action [%s] within email handler", jam[startIx]->args);
		if (jam[startIx])
			emitStd(jam[startIx]->trailer);
		if (jam[startIx])
			startIx++;

		control(startIx, NULL);
		logMsg(LOGINFO, "Finished running action [%s] within email handler", jam[startIx]->args);
		// Copied @runaction block ends --------------------------------
		// Restore emitStd
		emitStdPos = saveStdPos;
		emitStdRemaining = saveStdRemaining;
		// Create the email body
		p = emitScratchBuffer;
		char *encodedData = urlEncode(emitScratchBuffer);
		strcpy(mailBody, encodedData);
		free(encodedData);
	} else {
		int sanity = 0;
		int cnt = 4;
		strcpy(mailBody, "");
		while (1) {
			if (++sanity > 100) { emitStd("Overflow in mailbody!"); break; }
			getWord(tmp, args, cnt++, " ");
			if (strlen(tmp) == 0)
				break;
			strcat(mailBody, " ");
			strcat(mailBody, tmp);
		}
	}

	// @@TODO SEE 20 LINES BELOW !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	while (char *p = strchr(mailBody, '\\')) {
		*p = ' ';
		p++;
		if ((*p) && (*p == 'n'))
			*p = 10;
	}

	logMsg(LOGDEBUG, "Try to send via sendmail. From [%s] To [%s] Subject [%s] Body [%s]", mailFrom, mailTo, mailSubject, mailBody);
//return(0);
	FILE *mailpipe = popen("/usr/sbin/sendmail -t", "w");
	if (mailpipe == NULL) {
		logMsg(LOGERROR, "Failed to popen sendmail");
		return(-1);
	}
	logMsg(LOGDEBUG, "Mailpipe popen ok. Sending via sendmail");
	fprintf(mailpipe, "From: %s\n", mailFrom);
	fprintf(mailpipe, "To: %s\n", mailTo);

	fprintf(mailpipe, "MIME-Version: 1.0\n");
	fprintf(mailpipe, "Content-Type: text/html\n");

	fprintf(mailpipe, "Subject: %s\n\n", mailSubject);
	fprintf(mailpipe, "<!DOCTYPE HTML>");
	fprintf(mailpipe, "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' /></head><body>");
	//@@TODO convert all \n to <br> unless we're going to have a text vs html content indicator. SEE 20 lines UP!!!!
	fwrite(mailBody, 1, strlen(mailBody), mailpipe);
	fprintf(mailpipe, "</body></html>");
	fwrite(".\n", 1, 2, mailpipe);
	pclose(mailpipe);
	sleep(3);

	// Wrap up
    free(tmp);
    free(mailFrom);
    free(mailTo);
    free(mailSubject);
    free(mailBody);
    emitStd(jam[ix]->trailer);
}

