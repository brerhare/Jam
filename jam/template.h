#ifndef _TEMPLATE_H_INCLUDED_
#define _TEMPLATE_H_INCLUDED_

char *readTemplate(char *fname);
char *curlies2JamArray(char *tplPos);

typedef struct {
	char *startCurlyPos;
	char *endCurlyPos;
	char *name;
	char *content;
} TAGINFO;
TAGINFO *getTagInfo(char *text, char *tagName);

#endif /* _TEMPLATE_H_INCLUDED_ */
