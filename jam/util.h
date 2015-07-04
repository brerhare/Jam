#ifndef _UTIL_H_INCLUDED_
#define _UTIL_H_INCLUDED_

#include <stdlib.h>
#include <string.h>
#include <ctype.h>

char *strTrim(char *str);
int getWord(char *dest, char *src, int wordnum, char *separator);
char *getWordAlloc(char *src, int wordnum, char *separator);
extern int getWordIgnoreQuotes;
char *strReplaceAlloc(char *orig, char *rep, char *with);

#endif /* _UTIL_H_INCLUDED_ */
