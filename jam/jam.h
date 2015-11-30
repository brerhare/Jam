#ifndef _JAM_H_INCLUDED_
#define _JAM_H_INCLUDED_

char *readJam(char *fname);
char *curlies2JamArray(char *jamPos);
char *expandCurliesInString(char *str, char *defaultTableName);
int jamArgs2Vars(int ix, char *args);

typedef struct {
	char *startCurlyPos;
	char *endCurlyPos;
	char *name;
	char *content;
} TAGINFO;
TAGINFO *getTagInfo(char *text, char *tagName);

#endif /* _JAM_H_INCLUDED_ */
