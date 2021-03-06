#ifndef _UTIL_H_INCLUDED_
#define _UTIL_H_INCLUDED_

char *escapeJsonChars(char *src);
char *escapeSingleQuote(char *src);
char *strTrim(char *str);
int getWord(char *dest, char *src, int wordnum, char *separator);
char *getWordAlloc(char *src, int wordnum, char *separator);
extern int getWordIgnoreQuotes;
char *strReplaceAlloc(char *orig, char *rep, char *with);

char *str_replace(const char *string, const char *substr, const char *replacement);

char *strAnyChr(char *string, char *chars);

#endif /* _UTIL_H_INCLUDED_ */
