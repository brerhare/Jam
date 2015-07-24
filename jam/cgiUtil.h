#ifndef __CGIUTIL_H_INCLUDED
#define __CGIUTIL_H_INCLUDED

/** Convert a two-char hex string into the char it represents. **/
char x2c(char *what);

/** Reduce any %xx escape sequences to the characters they represent. **/
void unescape_url(char *url);

/** Read the CGI input and place all name/val pairs into list.        **/
/** Returns list containing name1, value1, name2, value2, ... , NULL  **/
char **getcgivars();

#endif /* __CGIUTIL_H_INCLUDED */

