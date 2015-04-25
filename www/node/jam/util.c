#include "util.h"

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

int getWord(char *dest, char *src, int wordnum, char *separator)
{
    int onaword = 0;
    int inaquote = 0;
    int retcode = -1;
    char *p;
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
            /* Non-separator */
            if (onaword == 0)
            {
                onaword = 1;
                wordnum--;
            }
        }
        else
            if (!inaquote)
                /* Separator */
                onaword = 0;
        if ((!wordnum) && (onaword) && (*src != '\"'))
            if (*src != 10)
            {
                *dest++ = *src;
                retcode = 0;
            }
        if (*src == '\"')
        {
            if (inaquote)
                inaquote = 0;
            else
                inaquote = 1;
        }
        src++;
    }
    *dest = '\0';
    return(retcode);
}

/* A version of getWord that returns a malloc'd string which the caller must free */
char *getWordAlloc(char *src, int wordnum, char *separator)
{
	char *p = NULL;
	char *tmp = (char *) calloc(1, (strlen(src)+1));
	getWord(tmp, src, wordnum, separator);
	if (tmp)
		p = strdup(tmp);
	free(tmp);
	return p;
}

