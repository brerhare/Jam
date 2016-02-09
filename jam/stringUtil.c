#include "stringUtil.h"
#include <stdio.h>

#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#include "log.h"

// Escape some chars that JSON doesnt like
char *escapeJsonChars(char *src)
{
int fnd = 0;
	char *ret = (char *) calloc(2, (strlen(src) + 1));
	char *p = src;
	char *p2 = ret;
	char *sp = NULL;
	while (*p) {
		if      (*p == '\"') { *p2++ = '\\'; *p2++ = '\"'; p++; }
		else if (*p == '\\') { *p2++ = '\\'; *p2++ = '\\'; p++; }
		else if (*p == '/')  { *p2++ = '\\'; *p2++ = '/';  p++; }
		else if (*p == '\b') { *p2++ = '\\'; *p2++ = 'b';  p++; }
		else if (*p == '\f') { *p2++ = '\\'; *p2++ = 'f';  p++; }
		else if (*p == '\n') { *p2++ = '\\'; *p2++ = 'n';  p++; }
		else if (*p == '\r') { *p2++ = '\\'; *p2++ = 'r';  p++; }
		else if (*p == '\t') { *p2++ = '\\'; *p2++ = 't';  p++; }
		else
			*p2++ = *p++;
	}
	return(ret);
}

/* Trims a sting in place. No memory adjustment is done */
char *strTrim(char *str)
{
	size_t len = 0;
	char *frontp = str;
	char *endp = NULL;
	if (str == NULL)
		return NULL;
	if (str[0] == '\0')
		return str;
	len = strlen(str);
	endp = str + len;
	/* Move the front and back pointers to address the first non-whitespace characters from each end */
	while (isspace(*frontp))
		 ++frontp;
	if (endp != frontp)  {
		while (isspace(*(--endp)) && endp != frontp)
		;
	}
	if (str + len - 1 != endp)
		*(endp + 1) = '\0';
	else if (frontp != str &&  endp == frontp)
		*str = '\0';
	/* Shift the string so that it starts at str so that if it's dynamically allocated, we can still free it on the returned pointer */
	endp = str;
	if (frontp != str) {
		while (*frontp)  {
			*endp++ = *frontp++;
		}
		*endp = '\0';
	}
	return str;
}

// Returns position of word or -1
// Dest can be null
int getWord(char *dest, char *src, int wordnum, char *separator)
{
    int onaword = 0;
    int inaquote = 0;
    char *startPos = src;
    char *wordPos = NULL;
    char *p;

	char quoteChar = '\'';
	if (!strchr(separator, ' '))
		quoteChar = 255;

    while (*src)
    {
        /*if ((*src == 10) || (*src == 13))
            break;*/
        for (p = separator; *p; p++)
        {
            if (*src == *p)
                break;
        }
        if (!(*p))
        {
            if (onaword == 0) /* Non-separator */
            {
                onaword = 1;
                wordnum--;
            }
        }
        else if (!inaquote) /* Separator */
            onaword = 0;

        if ((!wordnum) && (onaword))
		{
			if (*src != quoteChar)
			{
            	if (*src != 10)
				{
					if (dest)
                		*dest++ = *src;
					if (wordPos == NULL)
						wordPos = src;
				}
			}
		}

        if (*src == quoteChar)
        {
            if (inaquote)
                inaquote = 0;
            else
               	inaquote = 1;
        }
        src++;
    }
	if (dest)
    	*dest = '\0';
	return wordPos == NULL ? -1 : (int) (wordPos - startPos);
}

/* A wrapper for getWord to return a malloc'd string which the caller must free */
char *getWordAlloc(char *src, int wordnum, char *separator)
{
	char *p = NULL;
	if (src == NULL)
		return NULL;
	char *tmp = (char *) calloc(1, (strlen(src)+1));
	getWord(tmp, src, wordnum, separator);
	if (*tmp)
		p = strdup(tmp);
	free(tmp);
	return p;
}

// You must free the result if result is non-NULL
char *strReplaceAlloc(char *orig, char *rep, char *with) {
	char *result; // the return string
	char *ins;    // the next insert point
	char *tmp;    // varies
	int len_rep;  // length of rep
	int len_with; // length of with
	int len_front; // distance between rep and end of last rep
	int count;    // number of replacements

	if (!orig)
		return NULL;
	if (!rep)
		rep = "";
	len_rep = strlen(rep);
	if (!with)
		with = "";
	len_with = strlen(with);

	ins = orig;
	for (count = 0; tmp = strstr(ins, rep); ++count) {
		ins = tmp + len_rep;
	}

	// first time through the loop, all the variable are set correctly from here on,
	//    tmp points to the end of the result string
	//    ins points to the next occurrence of rep in orig
	//    orig points to the remainder of orig after "end of rep"
	tmp = result = (char *) malloc(strlen(orig) + (len_with - len_rep) * count + 1);

	if (!result)
		return NULL;

	while (count--) {
		ins = strstr(orig, rep);
		len_front = ins - orig;
		tmp = strncpy(tmp, orig, len_front) + len_front;
		tmp = strcpy(tmp, with) + len_with;
		orig += len_front + len_rep; // move to next "end of rep"
	}
	strcpy(tmp, orig);
	return result;
}

//--------------------------------------------------------------------------------------------------------

// UNCHECKED! UNTESTED! Replace all occurrences of 'substr' with 'replacement' in 'string'
char *str_replace (const char *string, const char *substr, const char *replacement) {
	char *tok = NULL;
	char *newstr = NULL;
	char *oldstr = NULL;
	char *head = NULL;
 
	// If either substr or replacement is NULL, just duplicate the string
	if (substr == NULL || replacement == NULL)
		return strdup(string);
	newstr = strdup(string);
	head = newstr;
	while ((tok = strstr(head, substr))) {
		oldstr = newstr;
		newstr = (char *) malloc (strlen(oldstr) - strlen(substr) + strlen (replacement) + 1);
		// Failed to alloc mem, free old string and return NULL
		if (newstr == NULL) {
			free(oldstr);
			return NULL;
		}
		memcpy (newstr, oldstr, tok - oldstr);
		memcpy (newstr + (tok - oldstr), replacement, strlen (replacement));
		memcpy (newstr + (tok - oldstr) + strlen(replacement), tok + strlen(substr), strlen(oldstr) - strlen(substr) - (tok - oldstr));
		memset (newstr + strlen (oldstr) - strlen(substr) + strlen(replacement) , 0, 1 );
		// Move back head right after the last replacement
		head = newstr + (tok - oldstr) + strlen(replacement);
		free(oldstr);
	}
	return newstr;
}

// Do a strchr() looking for the first of _any_ of some supplied chars
char *strAnyChr(char *string, char *chars) {
	while (string) {
		if (strchr(chars, *string))
			return string;
		string++;
	}
	return 0;
}

/*
char *strchr(const char *s, int c)
{
    const char ch = c;

    for ( ; *s != ch; s++)
        if (*s == '\0')
            return 0;
    return (char *)s;
}
*/
