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

#include "template.h"

char *readTemplate(char *fname){
	char *buf = NULL;
	std::ifstream html (fname, std::ifstream::binary);
	if (!html){
		std::cout << "readTemplate: error - cant open file " << fname << endl;
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
//	printf("\n[%d][%d]\n-->%s<--\n", jamLen, length, buf);
	return buf;
}

char *curlies2JamArray(char *tplPos) {
	char *startCurly = strstr(tplPos, startJam);
	if (!startCurly)
		return NULL;
	char *endCurly = strstr(tplPos, endJam);
	if (!endCurly)
		die("Unmatched jam token, an open must have a close");
	int wdLen = (endCurly - startCurly - strlen(startJam));
	char *wd = (char *) malloc(wdLen + 1);
	memcpy(wd, (startCurly + strlen(startJam)), wdLen);
	wd[wdLen] = 0;
	if (strstr(wd, startJam)) {
		die("there is an opening jam token within a token pair");
	}
//printf("\nlen=[%d] wd=[%s]\n", wdLen, wd);

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
//printf("USING STACK: [%s]", tableStack[i]);
				char *newBuf = (char *) calloc(1, 4096);
				sprintf(newBuf, "%s.%s", tableStack[i], buf);
				free(buf);
				buf = newBuf;
//printf(" ... storing variable [%s]\n", buf);
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
	else
        jam[jamIx]->args = strdup("");
	}
//printf("SETTING [%s]=[%s]\n", jam[jamIx]->command, jam[jamIx]->args);

	char *trailer = strdup(endCurly + strlen(endJam));
	char *c = strstr(trailer, startJam);
	if (c)
		*c = 0;
	jam[jamIx]->trailer = strdup(trailer);

	// Push the table onto the stack at every start of loop
	if ( (!strcmp(jam[jamIx]->command, "@each")) || (!strcmp(jam[jamIx]->command, "@oncall")) ) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (tableStack[i] == NULL) {
				char *p = (char *) calloc(1, 4096);
				getWord(p, jam[jamIx]->args, 1, space);
				tableStack[i] = p;
//printf("STACK: [%s]\n", tableStack[i]);
				break;
			}
		}
	}
	// Pop the table off the stack at every end of loop
	if (!(strcmp(jam[jamIx]->command, "@end"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if ((tableStack[i] == NULL) && (i > 0)) {
				i--;
//printf("POP: [%s]\n", tableStack[i]);
				if (!tableStack[i])
					die("Invalid @end tag found. I dont seem to find the tag that started this one");
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
		char *pEnd = NULL;
		if ((pEnd = strstr(pos, endJam)) == NULL)
			die("getTagContent: found mismatched tags");
		char *startContent = (tag + strlen(startJam));
		char *endContent = (pEnd - 1);
		if (endContent < startContent)
			die("getTagContent: end tag before start tag");
		content = (char *) calloc(1, (endContent - startContent + 2));
		memcpy(content, startContent, (endContent - startContent + 1));
		currTag = strTrim(getWordAlloc(content, 1, " \t\n"));
		currTagData = (content + strlen(currTag) + 1);	// point to the actual tag data, past the tag name
		if ((tagName == NULL) || (!strcmp(tagName, currTag))) {
			TAGINFO *tagInfo = (TAGINFO *) calloc(1, sizeof(TAGINFO));
			tagInfo->startCurlyPos = tag;
			tagInfo->endCurlyPos = pEnd;
			tagInfo->name = strdup(currTag);
			tagInfo->content = strdup(currTagData);
//			char *tmp = (char *) calloc(1, 4096); sprintf(tmp, "s=%d, e=%d, content=%s", (int) tagInfo->startCurlyPos, (int) tagInfo->endCurlyPos, tagInfo->content); die(tmp);
			free(currTag);
			return tagInfo;
		}
		pos = (pEnd + strlen(endJam));					// advance
	}
	return NULL;
}
