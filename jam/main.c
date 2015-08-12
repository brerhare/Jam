/*
 * @@TODO Fix the priority of assignment to non-qualified vars. This worked well until I stuck in a @GET inside a @EACH loop - it still obeys the @EACH x
 */
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
#include "wordDatabase.h"
#include "wordMisc.h"
#include "database.h"
#include "list.h"
#include "stringUtil.h"
#include "linkListUtil.h"
#include "cgiUtil.h"
#include "template.h"

#include <locale.h>

#include "log.h"

#ifndef __STDC_ISO_10646__
#error "Oops, our wide chars are not Unicode codepoints, sorry!"
#endif

// Common declares start
MYSQL *conn = NULL;
char *startJam = "{{";
char *endJam = "}}";
int literal = 0;
JAM *jam[MAX_JAM];
int jamIx = 0;
char *tableStack[MAX_JAM];
VAR *var[MAX_VAR];
char *documentRoot = NULL;
char *tplEntrypoint = NULL;

// Common declares end

JAM *initJam();
int control(int startIx, char *tableName);

int isASCII(const char *data, size_t size)
{
    const unsigned char *str = (const unsigned char*)data;
    const unsigned char *end = str + size;
    for (; str != end; str++) {
        if (*str & 0x80)
            return 0;
    }
    return 1;
}


int main(int argc, char *argv[]) {
	char **cgivars ;
	char tmpPath[PATH_MAX], binary[PATH_MAX];
	char *tmp = (char *) calloc(1, 4096);
	char *tplName = NULL;

	logMsg(LOGINFO, "Starting");
	printf("Content-type: text/html; charset=UTF-8\n\n");
	documentRoot = getenv("DOCUMENT_ROOT");

	cgivars = getcgivars() ;
	for (int i=0; cgivars[i]; i+= 2) {
		//printf("[%s] = [%s]<br>", cgivars[i], cgivars[i+1]) ;

		if (!(strcmp(cgivars[i], "template"))) {
			tplName = strTrim(getWordAlloc(cgivars[i+1], 1, ":"));
			tplEntrypoint = strTrim(getWordAlloc(cgivars[i+1], 2, ":"));
//printf("[%s][%s]<br>", tplName, tplEntrypoint);
// @@KIM! remove next if
		} else if (!tplEntrypoint){
			VAR *assignVar = (VAR *) calloc(1, sizeof(VAR));
			assignVar->name = strdup(cgivars[i]);
			assignVar->type = VAR_STRING;	// @@FIX!!!!!!
			clearVarValues(assignVar);
			fillVarDataTypes(assignVar, cgivars[i+1]);
//printf("PREFILL-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
			assignVar->source = strdup("prefill");
			assignVar->debugHighlight = 1;
			for (int i = 0; i < MAX_VAR; i++) {
				if (!var[i]) {
					var[i] = assignVar;
					break;
				}
			}
		}
	}

	// Read in template, including any @include's
	sprintf(tmp, "%s/%s", documentRoot, tplName);
	//sprintf(tmp, "/home/kim/dev/src/jam/template2/supplier.tpl");
	char *tpl = readTemplate(tmp);

int sanity = 0;
	while (1) {
if (++sanity > 100) { printf("Overflow!"); break; }
		TAGINFO *tagInfo = getTagInfo(tpl, "@include");
		if (tagInfo == NULL)
			break;
		// Read in the include file to memory
		sprintf(tmp, "%s/%s", documentRoot, tagInfo->content);
		std::ifstream includeFile (tmp, std::ifstream::binary);
		if (!includeFile) {
			char *error = (char *) calloc(1, 4096);
			sprintf(error, "@include : cant find file %s", tmp);
			die(error);
		}
		includeFile.seekg (0, includeFile.end);
		int length = includeFile.tellg();
		includeFile.seekg (0, includeFile.beg);
		char *includeBuf = (char *) calloc(1, length+1);
		if (!includeBuf) {
			sprintf(tmp, "cant calloc memory to @include %s", tagInfo->content);
			die(tmp);
		}
   		includeFile.read(includeBuf, length);
   		includeBuf[length] = 0;
	    includeFile.close();
		//printf("[file=%s][len=%d][includeBuf=%s][1st=%c][strlen=%d]", tmp, length, includeBuf, includeBuf[0], (int) strlen(includeBuf));
		//exit(0);

		// Include the include
		char *newTpl = (char *) calloc(1, (strlen(tpl) + strlen(includeBuf) + 1));
		*(tagInfo->startCurlyPos) = '\0';
		strcpy(newTpl, tpl);
		strcat(newTpl, includeBuf);
		strcat(newTpl, (tagInfo->endCurlyPos + strlen(endJam)));
		//printf("1st=%d, incl=%d, 2nd=%d<br>", (int)strlen(tpl), (int)strlen(includeBuf), (int)strlen((endCurly+strlen(endCurly))));
		free(tpl);
		tpl = newTpl;
		free(tagInfo->name);
		free(tagInfo->content);
		free(tagInfo);
	}

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
	int startIx = 0;
	if (tplEntrypoint) {
		int ix = 0;
		while (jam[ix]) {
			if ((!strcmp(jam[ix]->command, "@action")) && (!strcmp(jam[ix]->args, tplEntrypoint))) {
				startIx = ix;
				//printf("XXXXXXXXXXXXXXXXXXXXX FOUND ACTION TO RUN! XXXXXXXXXXXXXXXXXXXXXXX<br>");
				break;
			}
			ix++;
		}
	}
	control(startIx, NULL);

	free(tmp);
	free(tpl);
	free(tplEntrypoint);
	if (conn)
		closeDB();
jamDump(2);
	exit(0);
}

int Xmain(int argc, char *argv[]) {
	char *argName[MAX_ARGS];
	char *argValue[MAX_ARGS];
	for (int i = 0; i < MAX_ARGS; i++)
		argName[i] = argValue[i] = NULL;
	char *eq = "=";
	int i;
	for (i = 1; i < argc; i++) {
		argName[i-1]  = strTrim(getWordAlloc(argv[i], 1, eq));
		argValue[i-1] = strTrim(getWordAlloc(argv[i], 2, eq));
		//printf("arg [%s] has value [%s]\n", argName[i-1], argValue[i-1]);
	}

	char *tplName = NULL;
	char *tplEntrypoint = NULL;
	for (i = 0; i < MAX_ARGS; i++) {
		if (!argName[i])
			break;
		if (!(strcmp(argName[i], "template"))) {
			tplName = strTrim(getWordAlloc(argValue[i], 1, ":"));
			tplEntrypoint = strTrim(getWordAlloc(argValue[i], 2, ":"));
// @@KIM! remove next if
		} else if (!tplEntrypoint){
			VAR *assignVar = (VAR *) calloc(1, sizeof(VAR));
			assignVar->name = strdup(argName[i]);
			assignVar->type = VAR_STRING;	// @@FIX!!!!!!
			clearVarValues(assignVar);
			fillVarDataTypes(assignVar, argValue[i]);
//printf("PREFILL-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
			assignVar->source = strdup("prefill");
			assignVar->debugHighlight = 1;
			for (int i = 0; i < MAX_VAR; i++) {
				if (!var[i]) {
					var[i] = assignVar;
					break;
				}
			}
		}
	}

	// @@TODO @@FIX!
	if (tplEntrypoint) {
		int add = 0;
		for (int i = 0; i < MAX_ARGS; i++) {
			if (!argName[i])
				break;
			if (!strcmp(argName[i], "form.addbutton"))
				add = 1;
		}
		if (add) {
			char *query = (char *) calloc(1, 4096);
			char *name = NULL, *a1 = NULL, *a2 = NULL, *a3 = NULL, *a4 = NULL, *pc = NULL, *tel = NULL, *email = NULL;
			for (int i = 0; i < MAX_ARGS; i++) {
				if (!argName[i])
					break;
				if (!argValue[i])
					continue;
				if (!strcmp(argName[i], "stock_supplier.name"))	name = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.address1"))	a1 = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.address2"))	a2 = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.address3"))	a3 = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.address4"))	a4 = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.postcode"))	pc = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.telephone"))	tel = strdup(argValue[i]);
				if (!strcmp(argName[i], "stock_supplier.email"))	email = strdup(argValue[i]);
			}
			if (((!name))  || (!strcmp(name, ","))) name = strdup("");
			if (((!a1) )    || (!strcmp(a1, ","))) a1 = strdup("");
			if (((!a2) )    || (!strcmp(a2, ","))) a2 = strdup("");
			if (((!a3) )    || (!strcmp(a3, ",")))  a3 = strdup("");
			if (((!a4) )    || (!strcmp(a4, ","))) a4 = strdup("");
			if (((!pc) )    || (!strcmp(pc, ",")))  pc = strdup("");
			if (((!tel))    || (!strcmp(tel, ","))) tel = strdup("");
			if (((!email))  || (!strcmp(email, ","))) email = strdup("");
			if ((name) && (strlen(name)) && (1==1)) {
				sprintf(query, "insert into stock_supplier values (NULL, 1, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", name,a1,a2,a3,a4,pc,tel,email);
				if (openDB("stock") != 0)
					die(mysql_error(conn));
				if (mysql_query(conn, query)) {
					fprintf(stderr, "%s\n", mysql_error(conn));
					exit(1);
				}
				closeDB();
			}
		}
	}

	// Read in template
	char *tpl = readTemplate(tplName);

	// Create Jam array from template
	char *tplPos = tpl;
	while (tplPos = curlies2JamArray(tplPos)) {
		//printf("%s\n", jam[jamIx]->command);
		jamIx++;
	}


	// Generate HTML from Jam array
	control(0, NULL);

	free(tpl);
	free(tplEntrypoint);
	if (conn)
		closeDB();
jamDump(2);
	exit(0);
}

int control(int startIx, char *defaultTableName) {
	int ix = startIx;
	char *tmp = (char *) calloc(1, 4096);
	while (jam[ix]) {
		char *cmd = jam[ix]->command;
		char *args = jam[ix]->args;
		char *rawData = jam[ix]->rawData;

//printf("Processing [%s]<br>", cmd);

//		-----------------------------------------
		if (!strcmp(cmd, "@literal")) {
//		-----------------------------------------
			literal = 1;
			if (args) {
				getWord(tmp, args, 1, " ");
				if (*tmp) {
					if ((!strcmp(tmp, "off")) || (!strcmp(tmp, "0")))
						literal = 0;
				}
			}
		}

		if (literal) {
			char *newStr = strReplaceAlloc(jam[ix]->trailer, "\n", "<br>\n");
			if (newStr) {
				free(jam[ix]->trailer);
				jam[ix]->trailer = newStr;
			}
			newStr = strReplaceAlloc(jam[ix]->trailer, "\t", "&nbsp&nbsp&nbsp&nbsp");
			if (newStr) {
				free(jam[ix]->trailer);
				jam[ix]->trailer = newStr;
			}
			emit(jam[ix]->trailer);
		}

//		-----------------------------------------
		if (!(strcmp(cmd, "@!begin")))
//		-----------------------------------------
			emit(jam[ix]->trailer);
//		-----------------------------------------
		else if (!(strcmp(cmd, "@new"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " ");
				if (*tmp) {
					if (!strcmp(tmp, "database"))
						wordDatabaseNewDatabase(ix, defaultTableName);
					if (!strcmp(tmp, "table"))
						wordDatabaseNewTable(ix, defaultTableName);
					if (!strcmp(tmp, "index"))
						wordDatabaseNewIndex(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@remove"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " ");
				if (*tmp) {
					if (!strcmp(tmp, "database"))
						wordDatabaseRemoveDatabase(ix, defaultTableName);
					if (!strcmp(tmp, "table"))
						wordDatabaseRemoveTable(ix, defaultTableName);
					if (!strcmp(tmp, "index"))
						wordDatabaseRemoveIndex(ix, defaultTableName);
					if (!strcmp(tmp, "item"))
						wordDatabaseRemoveItem(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@list"))) {
//		-----------------------------------------
		if (args) {
			getWord(tmp, args, 1, " ");
			if (*tmp) {
				if (!strcmp(tmp, "databases"))
					wordDatabaseListDatabases(ix, defaultTableName);
				if (!strcmp(tmp, "tables"))
					wordDatabaseListTables(ix, defaultTableName);
			}
		}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@describe"))) {
//		-----------------------------------------
			wordDatabaseDescribe(ix, defaultTableName);
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@database"))) {
//		-----------------------------------------
			wordDatabaseDatabase(ix, defaultTableName);
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@skip"))) {
//		-----------------------------------------
			;	// @@TODO!	// @@TODO also any other skips, in @each etc

//		------------------------------------
		} else if (!(strcmp(cmd, "@get"))) {
//		------------------------------------
			wordDatabaseGet(ix, defaultTableName);
//		-------------------------------------
		} else if (!(strcmp(cmd, "@each"))) {
//		-------------------------------------
			char *givenTableName = (char *) calloc(1, 4096);
			MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 999999);
			SQL_RESULT *rp = sqlCreateResult(givenTableName, res);
			while (sqlGetRow(rp) != SQL_EOF) {
				emit(jam[ix]->trailer);
				control((ix + 1), givenTableName);
			}
			// Finished. Now emit the loops' trailer and make it current, so we will immediately advance past it
			while (jam[ix] && (strcmp(jam[ix]->command, "@end") || (strcmp(jam[ix]->args, givenTableName)))) {
				ix++;
			}
			if (jam[ix]) {
				emit(jam[ix]->trailer);
			}
			mysql_free_result(res);
			free(givenTableName);
//		-------------------------------------
		} else if (!(strcmp(cmd, "@action"))) {
//		-------------------------------------
			if (!tplEntrypoint) {		// not for us - ignore completely
				while (jam[ix] && (strcmp(jam[ix]->command, "@end")) )
					ix++;				
				emit(jam[ix]->trailer);
			} else {					// for us - run and stop
				emit(jam[ix]->trailer);
				control((ix + 1), defaultTableName);
				// Now emit the loops' trailer and stop
				while (jam[ix] && (strcmp(jam[ix]->command, "@end")) )
					ix++;		// skip over all the action content
				if (jam[ix])
					emit(jam[ix]->trailer);
				return(0);
			}
//		------------------------------------
		} else if (!(strcmp(cmd, "@end"))) {
//		------------------------------------
			// Return from an each-end or action-end loop
//printf("RETURNING\n");
			free(tmp);
			return(0);
//		----------------------------------------
		} else if (!(strcmp(cmd, "@Xinclude"))) {
//		----------------------------------------
			wordMiscInclude(ix, defaultTableName);
//		--------------------------------------
		} else if (!(strcmp(cmd, "@count"))) {
//		--------------------------------------
			wordMiscCount(ix, defaultTableName);
//		------------------------------------
		} else if (!(strcmp(cmd, "@sum"))) {
//		------------------------------------
			wordMiscSum(ix, defaultTableName);
//		---------------------------
		} else if (cmd[0] != '@') {
//		---------------------------
/*			data = LHS string
			if '='
				data = RHS string
			expandFields(data)

			if findVar(data)
				result string = var value
			else if isCalculation(data)
				result string = calculate(data)
			else
				result string = expandedData

			if '='
				prepare LHS for receiving result (may not exist yet)
				LHS var = result string
			else
				emit(result string)		*/

			// data = LHS to start with
			char *data = (char *) calloc(1, 4096);
			strcpy(data, cmd);							// eg: [mem.group_count]
//printf("{AWAY:%s and %s}",cmd, data);

			char *resultString = (char *) calloc(1, 4096);
			char *fullLine = (char *) calloc(1, 4096);
			strcpy(fullLine, cmd);
//printf("(CHK:%s and %s)", fullLine, args);
			if (args)
				strcpy(fullLine, rawData);
			strTrim(fullLine);
//printf("T=[%s]\n",fullLine);
			//sprintf(fullLine, "%s%s", cmd, args);			// eg: [mem.group_count][= stock_group.count]
			strcpy(data, fullLine);

			// data = RHS if equals sign
			char *eq = "=";
			if (strchr(fullLine, '=')) {
				char *rhs = strTrim(getWordAlloc(fullLine, 2, eq));		// eg [stock_group.count]
				strcpy(data, rhs);
				free(rhs);
			}

			// Expand any fields to values
			char *expandedData = expandVarsInString(data, defaultTableName);	// Allocates memory - needs freeing
//printf("\nSTART.... [c=%s][a=%s][f=%s][r=%s][e%s]\n", cmd, args, fullLine, data, expandedData);

			// Either retrieve the data from a field or calculate
			VAR *searchVar = findVarLenient(data, defaultTableName);
			if (searchVar)
				strcpy(resultString, searchVar->portableValue);
			else if (isCalculation(expandedData)) {
				char *calc = calculate(expandedData);
				sprintf(resultString, "%s", calc);						//@TODO: decimals artificially removed!!!!
				free(calc);
			}
			else
				strcpy(resultString, expandedData);

			// If '=' store result in LHS
			if (strchr(fullLine, '=')) {
				// Create / update LHS
				int createNew = 0;
				VAR *assignVar = findVarLenient(cmd, defaultTableName);
				if (!assignVar) {
					createNew = 1;
					assignVar = (VAR *) calloc(1, sizeof(VAR));
					assignVar->name = strdup(cmd);
					assignVar->type = VAR_STRING;	// @@FIX!!!!!!
				}
				clearVarValues(assignVar);
				fillVarDataTypes(assignVar, resultString);
				if (createNew) {
//printf("NEW-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
					assignVar->debugHighlight = 4;
					for (int i = 0; i < MAX_VAR; i++) {
						if (!var[i]) {
							var[i] = assignVar;
							break;
						}
					}
				}
			} else {		// Not an assignment - just emit variable
				emit(resultString);
				// Clear if necessary
				VAR *variable = findVarLenient(cmd, defaultTableName);
				if (variable) {
//printf("RETR-->%s<--\n", variable->name);
//char xx[256]; sprintf(xx, "R[%s]:%s", resultString, variable->portableValue);
//emit(xx);
					// Clear if 'count.'
					if ((variable->source) && (!strcmp(variable->source, "count"))) {
						variable->numberValue = 0;
						free(variable->portableValue);
						variable->portableValue = strdup("0");
					}
					// Clear if 'sum.'
					if ((variable->source) && (!strcmp(variable->source, "sum"))) {
						if (variable->type == VAR_NUMBER) {
							variable->numberValue = 0;
							free(variable->portableValue);
							variable->portableValue = strdup("0");
						} else if (variable->type == VAR_DECIMAL2) {
							variable->decimal2Value = 0;
							free(variable->portableValue);
							variable->portableValue = strdup("0.00");
						}
					}
				}
			}
			emit(jam[ix]->trailer);
			free(data);
			free(expandedData);
			free(resultString);
			free(fullLine);
//		--------
		} else {
//		--------
			emit(jam[ix]->trailer);
		}

		// Next
		ix++;
	}
	free(tmp);
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
