#include <stdio.h>
#include <stdlib.h>
#include <libgen.h>
#include <unistd.h>
#include <sys/stat.h>
#include <limits.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include "common.h"
#include "stringUtil.h"
#include "log.h"

#include "jam.h"

char *readJam(char *fname){
	char *buf = NULL;
	std::ifstream html (fname, std::ifstream::binary);
	if (!html){
		std::cout << "readJam: error - cant open file " << fname << endl;
		die("");
	}
	html.seekg (0, html.end);
	int length = html.tellg();
	html.seekg (0, html.beg);

	int jamLen = (strlen(startJam) + strlen(endJam));
	char *fakeWord = "@!begin";

	buf = (char *) calloc(1, jamLen + strlen(fakeWord) + length + 1);
	if (!buf) {
		std::cout << "error: cant calloc memory " << fname << endl;
		die("");
	}
	strcpy(buf, startJam);
	strcat(buf, fakeWord);
	strcat(buf, endJam);
	int bufLen = strlen(buf);
	html.read ((buf + bufLen), length);
	buf[bufLen+length] = 0;
	html.close();
	if (!html) {
		std::cout << "error: only " << html.gcount() << " could be read" << endl;
		die("");
	}
//	emitStd("\n[%d][%d]\n-->%s<--\n", jamLen, length, buf);
	return buf;
}

char *curlies2JamArray(char *jamPos) {

	char *startCurly = strstr(jamPos, startJam);
	if (!startCurly)
		return NULL;

	char *endCurly = NULL;

	// Find the matching endCurly, skipping over any embedded curlies @@TODO duplicated in getTagInfo() and one other
	int depth = 1;	// ie the start curly we just found
	char *inCurlyPos = (startCurly + strlen(startJam));
	int sanity = 0;
	while (depth > 0) {
		if (++sanity > 100) { emitStd("Overflow in curlies2JamArray!"); break; }
		char *sCurly = strstr(inCurlyPos, startJam);
		char *eCurly = strstr(inCurlyPos, endJam);
		if ((!sCurly) || (eCurly < sCurly)) {	// ie we found our match
			if (--depth == 0) {
				endCurly = eCurly;
				break;
			}
		} else {
			//depth++;
			inCurlyPos = (eCurly +strlen(endJam));
		}
	}
	if (!endCurly) {
		logMsg(LOGERROR, "Unmatched jam token. An open must have a close");
		return(NULL);
	}

	int wdLen = (endCurly - startCurly - strlen(startJam));
	char *wd = (char *) malloc(wdLen + 1);
	memcpy(wd, (startCurly + strlen(startJam)), wdLen);
	wd[wdLen] = 0;
	//if (strstr(wd, startJam)) {
		//die("there is an opening jam token within a token pair");
	//}
//emitStd("\nlen=[%d] wd=[%s]\n", wdLen, wd);

	jam[jamIx] = (JAM *) calloc(1, sizeof(JAM));
	jam[jamIx]->rawData = strdup(wd);

	char *buf = (char *) calloc(1, strlen(wd)+1);
	char *space = " ";
	getWord(buf, wd, 1, space);

/*
	// Get the current table from the top of stack for unqualified variables
	if ((buf[0] != '@') && (!(strchr(buf, '.')))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if ((tableStack[i] == NULL) && (i > 0)) {
				i--;
//emitStd("USING STACK: [%s]", tableStack[i]);
				char *newBuf = (char *) calloc(1, 4096);
				sprintf(newBuf, "%s.%s", tableStack[i], buf);
				free(buf);
				buf = newBuf;
//emitStd(" ... storing variable [%s]\n", buf);
				break;
			}
		}
	}
*/

	for (char *p = buf; *p; ++p) *p = tolower(*p);
	jam[jamIx]->command = buf;

	if (char *p = strchr(wd, ' ')) {
		if (*(p+1))
		 	jam[jamIx]->args = strdup(p+1);
	}
	else
		jam[jamIx]->args = strdup("");
//emitStd("SETTING [%s]=[%s]\n", jam[jamIx]->command, jam[jamIx]->args);

	char *trailer = strdup(endCurly + strlen(endJam));
	char *c = strstr(trailer, startJam);
	if (c)
		*c = 0;
	jam[jamIx]->trailer = strdup(trailer);

	// Push the table onto the stack at every start of loop
	if ( (!strcmp(jam[jamIx]->command, "@each")) || (!strcmp(jam[jamIx]->command, "@action")) ) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (tableStack[i] == NULL) {
				char *p = (char *) calloc(10, 4096);
				getWord(p, jam[jamIx]->args, 1, space);
				tableStack[i] = p;
//emitStd("STACK: [%s]\n", tableStack[i]);
				break;
			}
		}
	}
	// Pop the table off the stack at every end of loop
	if (!(strcmp(jam[jamIx]->command, "@end"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if ((tableStack[i] == NULL) && (i > 0)) {
				i--;
//emitStd("POP: [%s]\n", tableStack[i]);
				if (!tableStack[i]) {
					logMsg(LOGERROR, "Invalid @end tag found. I dont seem to find the tag that started this one");
					return(NULL);
				}
				jam[jamIx]->args = strdup(tableStack[i]);
				free(tableStack[i]);
				tableStack[i] = NULL;
				break;
			}
		}
	}
	free(trailer);
	free(wd);
	return (endCurly + strlen(endJam));
}

// Retrieves info about a tag embedded in text
// Leave tagName blank to get the first tag
// Result must be freed by caller
TAGINFO *getTagInfo(char *text, char *tagName) {
	char *currTag, *currTagData;
	char *content = NULL;
	char *pos     = text;
	char *tag     = NULL;
	while (tag = strstr(pos, startJam)) {
		// Find the matching endCurly, skipping over any embedded curlies  @@TODO duplicated in curlies2JamArray() and one other
		int depth = 1;	// ie the start curly we just found
		char *inCurlyPos = (tag + strlen(startJam));
		char *endCurly = NULL;
		int sanity = 0;
		while (depth > 0) {
			if (++sanity > 100) { emitStd("Overflow in getTagContent!"); break; }
			char *sCurly = strstr(inCurlyPos, startJam);
			char *eCurly = strstr(inCurlyPos, endJam);
			if ((!sCurly) || (eCurly < sCurly)) {	// ie we found our match
				if (--depth == 0) {
					endCurly = eCurly;
					break;
				}
			} else {
				//depth++;
				inCurlyPos = (eCurly +strlen(endJam));
			}
		}
		if (!endCurly) {
			logMsg(LOGERROR, "Unmatched jam token, an open must have a close");
			return(NULL);
		}

		char *startContent = (tag + strlen(startJam));
		char *endContent = (endCurly - 1);
		if (endContent < startContent) {
			logMsg(LOGERROR, "getTagContent: end tag before start tag");
			return(NULL);
		}
		content = (char *) calloc(1, (endContent - startContent + 2));
		memcpy(content, startContent, (endContent - startContent + 1));
		currTag = strTrim(getWordAlloc(content, 1, " \t\n"));
		currTagData = (content + strlen(currTag) + 1);	// point to the actual tag data, past the tag name
		if ((tagName == NULL) || (!strcmp(tagName, currTag))) {
			TAGINFO *tagInfo = (TAGINFO *) calloc(1, sizeof(TAGINFO));
			tagInfo->startCurlyPos = tag;
			tagInfo->endCurlyPos = endCurly;
			tagInfo->name = strdup(currTag);
			tagInfo->content = strdup(currTagData);
			logMsg(LOGDEBUG, "TAGINFO created. name=[%s], content=[%s]", tagInfo->name, tagInfo->content);
//			char *tmp = (char *) calloc(1, 4096); sprintf(tmp, "s=%d, e=%d, content=%s", (int) tagInfo->startCurlyPos, (int) tagInfo->endCurlyPos, tagInfo->content); die(tmp);
			free(currTag);
			return tagInfo;
		}
		pos = (endCurly + strlen(endJam));					// advance
	}
	return NULL;
}

// Take a string with embedded curlies in it, and return a string with the curlies evaluated (NOT RECURSIVE)
// eg "My name is {{name}}." could expand to "My name is John."
// Caller frees
char *expandCurliesInString(char *originalStr, char *defaultTableName) {
	if (originalStr == NULL)
		return(NULL);

	char *str = strdup(originalStr);
	char *startCurly = strstr(str, startJam);
	if (startCurly == NULL)
		return (str);

	logMsg(LOGDEBUG, "expandCurliesInString - started. original string is [%s]", originalStr);

int sanity = 0;
	while (1) {
if (++sanity > 200) { emitStd("Overflow in expandCurliesInString!"); break; }
		startCurly = strstr(str, startJam);
		if (startCurly == NULL)
			break;

		char *endCurly = NULL;
		// Find the matching endCurly, skipping over any embedded curlies @@TODO duplicated in 2 other places
		int depth = 1;	// ie the start curly we just found
		char *inCurlyPos = (startCurly + strlen(startJam));
		while (depth > 0) {
			if (++sanity > 200) { emitStd("Overflow2 in expandCurliesInString!"); break; }
			char *sCurly = strstr(inCurlyPos, startJam);
			char *eCurly = strstr(inCurlyPos, endJam);
			if ((!sCurly) || (eCurly < sCurly)) {	// ie we found our match
				if (--depth == 0) {
					endCurly = eCurly;
					break;
				}
			} else {
				//depth++;
				inCurlyPos = (eCurly +strlen(endJam));
			}
		}
		if (!endCurly) {
			logMsg(LOGERROR, "Unmatched jam token, an open must have a close");
			return(NULL);
		}

		// Extract the variable name
		int wdLen = (endCurly - startCurly - strlen(startJam));
		char *wd = (char *) malloc(wdLen + 1);
		memcpy(wd, (startCurly + strlen(startJam)), wdLen);
		wd[wdLen] = 0;
		// Point to its value (or itself it none exists)
		char *pWd = wd;
		VAR *pVar = findVarLenient(pWd, defaultTableName);
		if ((pVar) && (pVar->portableValue))
			pWd = pVar->portableValue;
		logMsg(LOGMICRO, "expandCurliesInString - substituting [%s] with value [%s]", wd, pWd);

		// Include the variable
		char *newStr = (char *) calloc(1, (strlen(str) + strlen(pWd) + 1));
		int num = (int) (startCurly - str);
		memcpy(newStr, str, num);
		newStr[num] = '\0';
		strcat(newStr, pWd);
		strcat(newStr, (endCurly + strlen(endJam)));
		logMsg(LOGDEBUG, "Splicing replaced {{variable}} with variable->content. 1stpart=%d, variabledata=%d, 2ndpart=%d<br>", (int)strlen(str), (int)strlen(wd), (int)strlen((endCurly + strlen(endJam))));
		free(str);
		str = newStr;
	}
	logMsg(LOGDEBUG, "expandCurliesInString - expanded string is [%s]", str);
	return (str);
}

// The control loop creates nvp's from the jamword arguments (type=dropdown, pickfield=product.name etc)
// Clear all of the old ones before creating
void clearControlVars() {
	char *tmp = (char *) calloc(1, 4096);
	for (int i = 0; (i <= LAST_VAR) && var[i]; i++) {
		if (!(var[i]))
			continue;
		if ((var[i]->name) && (strlen(var[i]->name) > 12)) {
			memcpy(tmp, var[i]->name, 12);
			if (!strcmp(tmp, "sys.control.")) {
				logMsg(LOGMICRO, "Control var clear %s", var[i]->name);
				deleteVar(var[i]);
			}
		}
	}
	free(tmp);
}

// Create/update vars from the jamword arguments args (type=dropdown, pickfield=product.name etc)
int jamArgs2ControlVars(int ix, char *args) {
	char *tmp = (char *) calloc(1, 4096);
	char *arg = NULL;
	int wdNum = 1;
	while (arg = getWordAlloc(args, wdNum++, " \t\n")) {	// eg 'a=b' or 'c = d'
		char *n = getWordAlloc(arg, 1, "=");
		char *v = getWordAlloc(arg, 2, "=");
		if (!n)
			continue;
		if (!v)
			v = strdup("");
		sprintf(tmp, "sys.control.%s", n);
		VAR *var = findVarStrict(tmp);
		if (var) {
			if (var->portableValue)
				free(var->portableValue);
			var->portableValue = strdup(v);
		} else {
			var = (VAR *) calloc(1, sizeof(VAR));
			var->name = strdup(tmp);
			var->type = VAR_STRING;
			var->source = strdup("arg");
			var->debugHighlight = 7;
			clearVarValues(var);
			fillVarDataTypes(var, v);
			if (addVar(var) == -1) {
				logMsg(LOGFATAL, "Cant create any more vars, terminating");
				exit(1);
			}
		}
		free(n);
		free(v);
		logMsg(LOGMICRO, "Control var set %s = %s", var->name, var->portableValue);
	}
	free(tmp);
}
