#include <stdio.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

using namespace std;

#define MAX_SQL_QUERY_LEN 1024
MYSQL *conn = NULL;

typedef struct {
	char *command;
	char *args;
	char *trailer;
} JAM;
#define MAX_JAM 10000
JAM *jam[MAX_JAM];
int jamIx = 0;

char *tableStack[MAX_JAM];

#define VAR_STRING    0
#define VAR_NUMBER    1
#define VAR_DECIMAL2  2
#define VAR_DATE      3
#define VAR_TIME      4
#define VAR_DATETIME  5

typedef struct {
	char *name;
	int type;
	char *portableValue;
	char *stringValue;
	long numberValue;
	double decimal2Value;
	char *dateValue;
	char *timeValue;
	char *datetimeValue;
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
void setFieldValues(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP);
char *findVar(char *qualifiedName);
void updateVar(char *qualifiedName, enum enum_field_types mysqlType, char *value);
int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value);
void jamDump();

int main(int argc, char *argv[]) {
	if (argc < 2) {
		die("no template file given to process");
	}
	string fname = argv[1];

	// Read in template
	//string fname = templatePath + "ex1.html";
	//string fname = templatePath + "ex_customer_area.html";
	char *tpl = readTemplate(fname);

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
	genHtml(0, NULL, NULL);

	free(tpl);
	if (conn)
		mysql_close(conn);
jamDump();
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
			char *mysqlHeaders[numFields];
			enum enum_field_types mysqlTypes[numFields];
			MYSQL_FIELD *field;
			for (int i = 0; (field = mysql_fetch_field(res)); i++) {
				mysqlHeaders[i] = field->name;
				mysqlTypes[i] = field->type;
			}

			while ((row = mysql_fetch_row(res)) != NULL) {
				// Recurse - start an each-end loop
				emit(jam[ix]->trailer);		
				setFieldValues(args, mysqlHeaders, mysqlTypes, numFields, &row);

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
		} else if (!(strcmp(cmd, "@include"))) {
			char *buf = NULL;
			std::ifstream includeFile (args, std::ifstream::binary);
			if (!includeFile){
				sprintf(tmp, "cant @include %s", args);
				die(tmp);
			}
			includeFile.seekg (0, includeFile.end);
			int length = includeFile.tellg();
			includeFile.seekg (0, includeFile.beg);
			buf = (char *) calloc(1, length+1);
			if (!buf) {
				sprintf(tmp, "cant calloc memory to @include %s", args);
				die(tmp);
			}
			// Emit any pre-tags
			if (strstr(args, ".css"))
				emit("<style>");
			includeFile.read (buf,length);
			buf[length] = 0;
			includeFile.close();
			if (!includeFile) {
				sprintf(tmp, "error: not the whole of %s could be read", args);
				die(tmp);
			}
			emit(buf);
			// Emit any post-tags
			if (strstr(args, ".css"))
			emit("</style>");
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
if (value)
	printf("FOUND!=[%s]\n",value);
else
	printf("NOT-FOUND :( Was looking for sumFieldName=[%s] and tableName=[%s]\n",sumFieldName, tableName);
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
/*
	if (!(strchr(qualifiedName, '.'))) {
		char *tmp = (char *) calloc(1, 1024);
		sprintf(tmp, "findVar() failed - was given a non-qualified field name [%s]", qualifiedName);
		die(tmp);
	}
*/
	for (int i = 0; (i < MAX_VAR) && var[i]; i++) {
		if (!(var[i]))
			break;	
		if (!strcmp(var[i]->name, qualifiedName)) {
			return var[i]->portableValue;	// the string representation of the value, whatever type it really is
		}
	}
	return NULL;
}

void updateVar(char *qualifiedName, enum enum_field_types mysqlType, char *value) {
	if (!qualifiedName)
		printf("NULL 'qualifiedName' passed to updateVar\n");
	char *oldValue = findVar(qualifiedName);
	if (!(oldValue)) {
		VAR *newVar = (VAR *) calloc(1, sizeof(VAR));
		newVar->name = strdup(qualifiedName);
		int ret = decodeMysqlType(newVar, mysqlType, value);
//printf( "NAME=%s TYPE=%d AVALUE=%s NVALUE=%ld DVALUE=%2.f\n", newVar->name, newVar->type, newVar->stringValue, newVar->numberValue, newVar->decimal2Value);
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
				int ret = decodeMysqlType(var[i], mysqlType, value);
				break;

/*
				if (var[i]->stringValue) {
					free(var[i]->stringValue);
					var[i]->stringValue = NULL;
				}
				if (value)
					var[i]->stringValue = strdup(value);
*/



			}
		}
	}
}

int decodeMysqlType(VAR *var, enum enum_field_types mysqlType, char *value) {
	var->type = -1;
	// Sanitise input for general purpose use
	char *safeValue = NULL;
	if (value)
		safeValue = strdup(value);
	// Free any existing value
	if (var->stringValue) free(var->stringValue);
	if (var->dateValue) free(var->dateValue);
	if (var->timeValue) free(var->timeValue);
	if (var->datetimeValue) free(var->datetimeValue);
	var->stringValue = NULL;
	var->dateValue = NULL;
	var->timeValue = NULL;
	var->datetimeValue = NULL;
	var->numberValue = 0;
	var->decimal2Value = 0;
	// Determine the type
	switch (mysqlType)
	{
		case MYSQL_TYPE_DATE:
			var->type = VAR_DATE;
			var->dateValue = safeValue;
			break;
		case MYSQL_TYPE_TIME:
			var->type = VAR_TIME;
			var->timeValue = safeValue;
		case MYSQL_TYPE_DATETIME:
		case MYSQL_TYPE_TIMESTAMP:
			var->type = VAR_DATETIME;
			var->datetimeValue = safeValue;
			break;
		case MYSQL_TYPE_DECIMAL:
		case MYSQL_TYPE_NEWDECIMAL:
			var->type = VAR_DECIMAL2;
			if (safeValue)
				var->decimal2Value = atof(safeValue);
			break;
	}
	if (var->type == -1) {
		if (IS_NUM(mysqlType)) {
			var->type = VAR_NUMBER;
			if (safeValue)
				var->numberValue = atol(safeValue);
		} else {
			var->type = VAR_STRING;
			var->stringValue = safeValue;
		}
	}
	var->portableValue = safeValue;
	return 0;
}

void setFieldValues(char *qualifier, char **mysqlHeaders, enum enum_field_types mysqlTypes[], int numFields, MYSQL_ROW *rowP) {
	MYSQL_ROW row = *rowP;
	int i = 0;
	for (i = 0; i < numFields; i++) {
		char qualifiedName[256];
		sprintf(qualifiedName, "%s.%s", qualifier, mysqlHeaders[i]);
		updateVar(qualifiedName, mysqlTypes[i], row[i]);
//printf("HDR=[%s.%s]:[%s]\n", qualifier, mysqlHeaders[i], row[i]);
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
		die("");
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
	printf("<br><br><div style='font-size:11px;color:#ffffff;background-color:#00727a'>");
	for (int i = 0; i < MAX_JAM; i++) {
		if (jam[i] == NULL)
			break;
		//printf("%02d JAMDUMP: %s >>>>>%s<<<<<\n\n\n", i, jam[i]->command, jam[i]->trailer);
		printf("%02d JAMDUMP: %s : %s<br>", i, jam[i]->command, jam[i]->args);
	}
	printf("<hr>");
	for (int i = 0; i < MAX_VAR; i++) {
		if (var[i] == NULL)
			break;
		if (var[i]->type == VAR_STRING)
			printf("%02d VARDUMP: %s : VAR_STRING : %s<br>", i, var[i]->name, var[i]->stringValue);
		if (var[i]->type == VAR_NUMBER)
			printf("%02d VARDUMP: %s : VAR_NUMBER : %ld<br>", i, var[i]->name, var[i]->numberValue);
		if (var[i]->type == VAR_DECIMAL2)
			printf("%02d VARDUMP: %s : VAR_DECIMAL2 : %.2f<br>", i, var[i]->name, var[i]->decimal2Value);
	}
	printf("</div>");
}

