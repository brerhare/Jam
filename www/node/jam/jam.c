#include <stdio.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

using namespace std;

string templatePath = "./public/";

#define MAX_SQL_QUERY_LEN 1024
MYSQL *conn = NULL;

typedef struct {
	char *command;
	char *args;
	char *name;
	char *value;
	char *trailer;
} JAM;
#define MAX_JAM 10000
JAM *jam[MAX_JAM];
int jamIx = 0;

char *tableStack[MAX_JAM];

#define VAR_ALPHA    0
#define VAR_NUMBER   1
#define VAR_DECIMAL2 2

typedef struct {
	char *name;
	int type;
	char *aValue;
	int nValue;
	double dValue;
} VAR;
#define MAX_VAR 10000
VAR *var[MAX_VAR];

char *readTemplate(string fname);
char *curlies2JamArray(char *tplPos);
JAM *initJam();
int genHtml(int startIx, MYSQL_ROW *row, char *tableName);
void emit(char *line);
void die(const char *errorString);
int getWord(char *dest, char *src, int wordnum, char *separator);
int openDB(char *name);
void setFieldValues(char *qualifier, char **headers, int numFields, MYSQL_ROW *rowP);
char *findVar(char *qualifiedName);
void updateVar(char *qualifiedName);
void jamDump();

int main() {
	// Read in template
	//string fname = templatePath + "ex1.html";
	string fname = templatePath + "ex_customer_area.html";
	char *tpl = readTemplate(fname);

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
jamDump();
	genHtml(0, NULL, NULL);

	free(tpl);
	if (conn)
		mysql_close(conn);
	exit(0);
}

int genHtml(int startIx, MYSQL_ROW *row, char *tableName) {
	int ix = startIx;
	char *tmp = (char *) calloc(1, 4096);
	while (jam[ix]) {
		char *cmd = jam[ix]->command;
		char *args = jam[ix]->args;
//printf("%s\n", cmd);
		// Process the jam command
		if (!(strcmp(cmd, "@!begin"))) {
			emit(jam[ix]->trailer);
		} else if (!(strcmp(cmd, "@database"))) {
			if (strlen(args) == 0) {
				printf("missing database name\n");
				exit(1);
			}
			if (openDB(args) < 0) {
				die(mysql_error(conn));
			}
			emit(jam[ix]->trailer);
		} else if (!(strcmp(cmd, "@each"))) {
			// Read record
			MYSQL_RES *res;
			MYSQL_ROW row;
			char *query = (char *) calloc(1, MAX_SQL_QUERY_LEN);
			sprintf(query, "select * from %s LIMIT 4",  args);
			if (mysql_query(conn, query)) {
				die(mysql_error(conn));
			}
			res = mysql_store_result(conn);
  			if (!res) {
				sprintf(tmp, "Couldn't get results set: %s\n", mysql_error(conn));
				die(tmp);
			}
			int numFields = mysql_num_fields(res);
			char *headers[numFields];
			MYSQL_FIELD *field;
			for (int i = 0; (field = mysql_fetch_field(res)); i++) {
				headers[i] = field->name;
			}

			while ((row = mysql_fetch_row(res)) != NULL) {
				// Recurse - start an each-end loop
				emit(jam[ix]->trailer);		
				setFieldValues(args, headers, numFields, &row);

//printf("GOING\n");
				genHtml((ix + 1), &row, args);
//printf("BACK\n");
			}
			// Finished. Now emit the loops' trailer and make it current, so we will immediately advance past it
			while (jam[ix] && (strcmp(jam[ix]->command, "@end") || (strcmp(jam[ix]->args, args)))) {
				ix++;
			}
			if (jam[ix]) {
				emit(jam[ix]->trailer);
			}
			mysql_free_result(res);
			free(query);
		} else if (!(strcmp(cmd, "@end"))) {
			// Return from an each-end loop
//printf("RETURNING\n");
			return(0);
		} else if (!(strcmp(cmd, "@count"))) {
			emit(jam[ix]->trailer);
		} else if (!(strcmp(cmd, "@sum"))) {
			char sep = ' ';
			getWord(tmp, args, 1, &sep);
			char *sumFieldName = strdup(tmp);
			if (!sumFieldName)
				die("Cant sum a nonexistent field");
			char *sumFieldAs = NULL;
			getWord(tmp, args, 2, &sep);
			if (!(strcmp(tmp, "as"))) {
				getWord(tmp, args, 3, &sep);
				sumFieldAs = strdup(tmp);
			}
			char *value = findVar(sumFieldName);
//if (value)
//	printf("FOUND!=[%s]\n",value);
//else
//	printf("NOT-FOUND :( Was looking for sumFieldName=[%s] and tableName=[%s]\n",sumFieldName, tableName);
			free(sumFieldName);
			free(sumFieldAs);
			emit(jam[ix]->trailer);
		} else if (cmd[0] != '@') {
			// Get the stored value
			char *value = findVar(cmd);
			if (!value)
				value = "???";
//emit("valuestart-");
			emit(value);
//emit("valueend-");
			emit(jam[ix]->trailer);		
		} else {
			emit(jam[ix]->trailer);
		}

		// Next
		ix++;
	}
	free(tmp);
}

char *findVar(char *qualifiedName) {
	if (!(strchr(qualifiedName, '.'))) {
		char *tmp = (char *) calloc(1, 1024);
		sprintf(tmp, "findVar() failed - was given a non-qualified field name [%s]", qualifiedName);
		die(tmp);
	}
	for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
		if (!(var[i]))
			break;	
		if (!strcmp(var[i]->name, qualifiedName)) {
			return var[i]->aValue;
		}
	}
	return NULL;
}

void updateVar(char *qualifiedName, char *value) {
	if (!qualifiedName) {
		printf("NULL 'qualifiedName' passed to updateVar\n");
		//return;
	}
/*	if (!value) {
		printf("NULL 'value' passed to updateVar\n");
		return;
	} */
	char *oldValue = findVar(qualifiedName);
	if (!(oldValue)) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		newVar->type = VAR_ALPHA;
		if (value)
			newVar->aValue = strdup(value);
		for (int i = 0; i < MAX_VAR; i++) {
			if (!var[i]) {
				var[i] = newVar;
				return;
			}
		}
	} else {
		for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
			if (!var[i])
				break;	
			if (!strcmp(var[i]->name, qualifiedName)) {
				if (var[i]->aValue) {
					free(var[i]->aValue);
					var[i]->aValue = NULL;
				}
				if (value)
					var[i]->aValue = strdup(value);
			}
		}
	}
}

void setFieldValues(char *qualifier, char **headers, int numFields, MYSQL_ROW *rowP) {
	MYSQL_ROW row = *rowP;
	int i = 0;
	for (i = 0; i < numFields; i++) {
		char qualifiedName[256];
		sprintf(qualifiedName, "%s.%s", qualifier, headers[i]);
		updateVar(qualifiedName, row[i]);
//printf("HDR=[%s.%s]:[%s]\n", qualifier, headers[i], row[i]);
	}
}

char *curlies2JamArray(char *tplPos) {
	char *startCurly = strchr(tplPos, '{');
	if (startCurly == NULL)
		return NULL;
	char *endCurly = strchr(tplPos, '}');
	if (endCurly == NULL) {
		printf("an opening '{' has a missing closing '}'\n");
		exit(1);
	}
	char *wd = (char *) malloc(endCurly - startCurly);
	int wdLen = (endCurly - startCurly - 1);
	memcpy(wd, (startCurly+1), wdLen);
	wd[wdLen] = 0;
	if (strchr(wd, '{')) {
		printf("there is an opening '{' within a '{}'\n");
		exit(1);
	}
//printf("startCurly=%p, endCurly=%p, wdLen=%d, wd=%s\n", startCurly, endCurly, wdLen, wd);

	jam[jamIx] = (JAM *) calloc(1, sizeof(JAM));

	char *buf = (char *) calloc(1, strlen(wd)+1);
	char sep = ' ';
	getWord(buf, wd, 1, &sep);

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
	for (char *p = buf; *p; ++p) *p = tolower(*p);
	jam[jamIx]->command = buf;

	buf = (char *) calloc(1, strlen(wd)+1);
	if (char *p = strchr(wd, ' ')) {
	 jam[jamIx]->args = strdup(p+1);
	}
//printf("SETTING [%s]=[%s]\n", jam[jamIx]->command, jam[jamIx]->args);

	char *trailer = strdup(endCurly + 1);
	char *c = strchr(trailer, '{');
	if (c)
		*c = 0;
	jam[jamIx]->trailer = strdup(trailer);

	// Push the table onto the stack at every start of loop
	if (!(strcmp(jam[jamIx]->command, "@each"))) {
		for (int i = 0; i < MAX_JAM; i++) {
			if (tableStack[i] == NULL) {
				char *p = (char *) calloc(1, 4096);
				getWord(p, jam[jamIx]->args, 1, &sep);
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
				jam[jamIx]->args = strdup(tableStack[i]);
				free(tableStack[i]);
				tableStack[i] = NULL;
				break;
			}
		}
	}

	free(trailer);

	return (endCurly+1);
}

char *readTemplate(string fname){
	char *buf = NULL;
	std::ifstream html (fname.c_str(), std::ifstream::binary);
	if (!html){
		std::cout << "error: cant open file " << fname << endl;
		return NULL;
	}
	html.seekg (0, html.end);
	int length = html.tellg();
	html.seekg (0, html.beg);

	buf = (char *) calloc(1, 9 +length+1);
	if (!buf) {
		std::cout << "error: cant calloc memory " << fname << endl;
		exit(1);
	}
	strcpy(buf, "{@!begin}");
	html.read (buf+9,length);
	buf[9+length] = 0;
	html.close();
	if (!html) {
		std::cout << "error: only " << html.gcount() << " could be read" << endl;
		exit(1);
	}
//	printf("-->%s<--\n", buf);
	return buf;
}

int openDB(char *name) {
	char *server = "localhost";
	char *user = "root";
	char *password = "Wole9anic-"; /* set me first */
	char *database = name;
	conn = mysql_init(NULL);
	if (!mysql_real_connect(conn, server, user, password, database, 0, NULL, 0)) {
		return -1;
	}
	return 0;
}

int main2() {
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_ROW row;
	char *server = "localhost";
	char *user = "root";
	char *password = "Wole9anic-"; /* set me first */
	char *database = "mysql";
	conn = mysql_init(NULL);
	if (!mysql_real_connect(conn, server, user, password, database, 0, NULL, 0)) {
		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);
	}
	/* send SQL query */
	if (mysql_query(conn, "show tables")) {
	fprintf(stderr, "%s\n", mysql_error(conn));
	exit(1);
	}
	res = mysql_use_result(conn);
	/* output table name */
	printf("MySQL Tables in mysql database:\n");
	while ((row = mysql_fetch_row(res)) != NULL)
	printf("%s \n", row[0]);
	/* close connection */
	mysql_free_result(res);
	mysql_close(conn);
}


void emit(char *line) {
	printf("%s", line);
}

void die(const char *errorString) {
	fprintf(stderr, "%s\n", errorString);
	exit(1);
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

void jamDump() {
	for (int i = 0; i < MAX_JAM; i++) {
		if (jam[i] == NULL)
			break;
		//printf("%02d JAMDUMP: %s >>>>>%s<<<<<\n\n\n", i, jam[i]->command, jam[i]->trailer);
		printf("%02d JAMDUMP: %s:%s\n", i, jam[i]->command, jam[i]->args);
	}

}

