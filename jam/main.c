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
#include "wordHtml.h" 
#include "wordMisc.h"
#include "database.h"
#include "list.h"
#include "stringUtil.h"
#include "linkListUtil.h"
#include "cgiUtil.h"
#include "jam.h"

#include <locale.h>

#include "log.h"

#ifndef __STDC_ISO_10646__
#error "Oops, our wide chars are not Unicode codepoints, sorry!"
#endif

// Common declares start
char *startJam = "{{";
char *endJam = "}}";
int literal = 0;
JAM *jam[MAX_JAM];
int jamIx = 0;
char *tableStack[MAX_JAM];
VAR *var[MAX_VAR];
char *documentRoot = NULL;
char *jamEntrypoint = NULL;

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
	char *jamName = NULL;

	curl = curl_easy_init();
	if (!curl) {
		logMsg(LOGFATAL, "Cant initialize curl, aborting");
		exit(1);
	}

	logMsg(LOGINFO, "--------------------------------------------------------------------------");
	logMsg(LOGINFO, "Starting");

	// Output headers to prevent caching
	emitHeader("Cache-Control: no-store, must-revalidate, max-age=0");
	emitHeader("Pragma: no-cache");
	emitHeader("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	// Always need this header
	emitHeader("Content-type: text/html; charset=UTF-8");

	documentRoot = getenv("DOCUMENT_ROOT");
	logMsg(LOGINFO, "DOCUMENT_ROOT is %s", documentRoot);

	cgivars = getcgivars() ;
	for (int i=0; cgivars[i]; i+= 2) {
		logMsg(LOGDEBUG, "Parameter [%s] = [%s]", cgivars[i], cgivars[i+1]) ;

		if (!strcmp(cgivars[i], "jamDataRequested"))
			jamDataRequested = 1;
		if (!strcmp(cgivars[i], "jam")) {
			logMsg(LOGDEBUG, "Found jam parameter");
			jamName = strTrim(getWordAlloc(cgivars[i+1], 1, ":"));
			jamEntrypoint = strTrim(getWordAlloc(cgivars[i+1], 2, ":"));
			if (jamEntrypoint)
				logMsg(LOGDEBUG, "Jam parameter contains an action to run: [%s]", jamEntrypoint);
//emitData("[%s][%s]<br>", jamName, jamEntrypoint);
// @@KIM! remove next if
		} else /* if (!jamEntrypoint) */ {
			VAR *assignVar = (VAR *) calloc(1, sizeof(VAR));
			assignVar->name = strdup(cgivars[i]);
			assignVar->type = VAR_STRING;	// @@FIX!!!!!!
			clearVarValues(assignVar);
			fillVarDataTypes(assignVar, cgivars[i+1]);
//			logMsg(LOGMICRO, "Initializing startup variable %s with value %s", assignVar->name, assignVar->portableValue);
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

	// Read in jam, including any @include's
	sprintf(tmp, "%s/%s", documentRoot, jamName);
	if (!strstr(tmp, ".jam"))
		strcat(tmp, ".jam");
	logMsg(LOGINFO, "asking for jam %s", tmp);
	char *jamBuf = readJam(tmp);
	if (jamBuf)
		logMsg(LOGINFO, "successfully read jam %s", tmp);
	else
		logMsg(LOGINFO, "could not read jam %s", tmp);

int sanity = 0;
	while (1) {
if (++sanity > 100) { emitData("Overflow in main!"); break; }
		TAGINFO *tagInfo = getTagInfo(jamBuf, "@include");
		if (tagInfo == NULL)
			break;
		// Read in the include file
		sprintf(tmp, "%s/%s", documentRoot, tagInfo->content);
		logMsg(LOGINFO, "including @INCLUDE file %s", tmp);
		std::ifstream includeFile (tmp, std::ifstream::binary);
		if (!includeFile) {
			char *error = (char *) calloc(1, 4096);
			sprintf(error, "@include : cant find file %s", tmp);
			logMsg(LOGFATAL, "%s", error);
			die(error);
		}
		includeFile.seekg (0, includeFile.end);
		int length = includeFile.tellg();
		includeFile.seekg (0, includeFile.beg);
		char *includeBuf = (char *) calloc(1, length+1);
		if (!includeBuf) {
			sprintf(tmp, "cant calloc memory to @include %s", tagInfo->content);
			logMsg(LOGFATAL, "%s", tmp);
			die(tmp);
		}
   		includeFile.read(includeBuf, length);
   		includeBuf[length] = 0;
	    includeFile.close();
		//emitData("[file=%s][len=%d][includeBuf=%s][1st=%c][strlen=%d]", tmp, length, includeBuf, includeBuf[0], (int) strlen(includeBuf));
		//exit(0);

		// Include the include
		char *newJam = (char *) calloc(1, (strlen(jamBuf) + strlen(includeBuf) + 1));
		*(tagInfo->startCurlyPos) = '\0';
		strcpy(newJam, jamBuf);
		strcat(newJam, includeBuf);
		strcat(newJam, (tagInfo->endCurlyPos + strlen(endJam)));
		logMsg(LOGDEBUG, "Splicing included file into jam. 1stpart=%d, include=%d, 2ndpart=%d<br>", (int)strlen(jamBuf), (int)strlen(includeBuf), (int)strlen((tagInfo->endCurlyPos + strlen(endJam))));
		free(jamBuf);
		jamBuf = newJam;
		free(tagInfo->name);
		free(tagInfo->content);
		free(tagInfo);
	}

	// Create Jam array from jamBuf
	logMsg(LOGINFO, "Creating jam array");
	char *jamPos = jamBuf;
	while (jamPos = curlies2JamArray(jamPos)) {
			logMsg(LOGDEBUG, "Array command=%s", jam[jamIx]->command);
		jamIx++;
	}

	// Generate HTML from Jam array
	logMsg(LOGINFO, "Generating HTML from Jam array");
	int startIx = 0;
	if (jamEntrypoint) {
		int ix = 0;
		while (jam[ix]) {
			if ((!strcmp(jam[ix]->command, "@action")) && (!strcmp(jam[ix]->args, jamEntrypoint))) {
/*				logMsg(LOGINFO, "Preparing to run @action %s, checking for _dbname", jamEntrypoint);
				// Set to use the named db
				for (int i=0; cgivars[i]; i+= 2) {
					if (!strcasecmp(cgivars[i], "_dbname")) {
						logMsg(LOGDEBUG, "Using parameter [%s] = [%s] to set db", cgivars[i], cgivars[i+1]);
						if (openDB(cgivars[i+1]) != 0)
							return(-1);
						break;
					}
				} */
				// Set startpoint
				startIx = ix;
				break;
			}
			ix++;
		}
	}
	if (startIx == 0) {
		logMsg(LOGINFO, "Processing command loop starting from @!begin");
		control(startIx, NULL);
	}
	else {
		logMsg(LOGINFO, "Processing command loop for @action %s", jamEntrypoint);
		control(startIx, NULL);
	}

	logMsg(LOGINFO, "Finished command loop");

	free(tmp);
	free(jamBuf);
	free(jamEntrypoint);
	if (conn)
		closeDB();

	VAR *debugVar = findVarStrict("debug");
	if ((debugVar) && (atoi(debugVar->portableValue) > 0))
		jamDump(atoi(debugVar->portableValue));

	// Output the data
	endHeader();
	endData();
	if (jamDataRequested == 1)
		oobJamData();

	logMsg(LOGINFO, "Normal exit");
	exit(0);
}

int control(int startIx, char *defaultTableName) {
	int ix = startIx;
	char *tmp = (char *) calloc(1, 4096);
	while (jam[ix]) {
		char *cmd = jam[ix]->command;
		char *args = NULL;
		char *rawData = NULL;

		if ((strlen(cmd)) && (cmd[0] == '@')) {
			// Expand any {{values}} in the argument string with the current values
			args = expandCurliesInString(jam[ix]->args, defaultTableName);
			rawData = expandCurliesInString(jam[ix]->rawData, defaultTableName);
			free(jam[ix]->args);
			free(jam[ix]->rawData);
			jam[ix]->args = args;
			jam[ix]->rawData = rawData;
			logMsg(LOGMICRO, "Command loop processing command [%s] args [%s]", cmd, args);
		}

		args = jam[ix]->args;
		rawData = jam[ix]->rawData;

//		-----------------------------------------
		if (!strcmp(cmd, "@literal")) {
//		-----------------------------------------
			literal = 1;
			if (args) {
				getWord(tmp, args, 1, " \t");
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
			emitData(jam[ix]->trailer);
		}

//		-----------------------------------------
		if (!(strcmp(cmd, "@!begin"))) {
//		-----------------------------------------
			emitData(jam[ix]->trailer);
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@html"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					if ( (!strcmp(tmp, "input")) || (!strcmp(tmp, "date")) )
						wordHtmlInput(ix, defaultTableName);
					else if (!strcmp(tmp, "inp"))
						wordHtmlInp(ix, defaultTableName);

					else if ( (!strcmp(tmp, "gridinput")) || (!strcmp(tmp, "griddate")) )
						wordHtmlGridInput(ix, defaultTableName);
					else if (!strcmp(tmp, "gridinp"))
						wordHtmlGridInp(ix, defaultTableName);

					else if (!strcmp(tmp, "textarea"))
						wordHtmlTextarea(ix, defaultTableName);
					else if (!strcmp(tmp, "button"))
						wordHtmlButton(ix, defaultTableName);
					else if (!strcmp(tmp, "breakpoint"))
						wordHtmlBreakpoint(ix, defaultTableName);
					else if (!strcmp(tmp, "sys"))
						wordHtmlSys(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@clear"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					if (!strcmp(tmp, "item"))
						wordDatabaseClearItem(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@new"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					if (!strcmp(tmp, "database"))
						wordDatabaseNewDatabase(ix, defaultTableName);
					else if (!strcmp(tmp, "table"))
						wordDatabaseNewTable(ix, defaultTableName);
					else if (!strcmp(tmp, "index"))
						wordDatabaseNewIndex(ix, defaultTableName);
					else if (!strcmp(tmp, "item"))
						wordDatabaseNewItem(ix, defaultTableName);
					else if (!strcmp(tmp, "list"))
						wordMiscNewList(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@remove"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					logMsg(LOGDEBUG, "@remove requested");
					if (!strcmp(tmp, "database"))
						wordDatabaseRemoveDatabase(ix, defaultTableName);
					else if (!strcmp(tmp, "table"))
						wordDatabaseRemoveTable(ix, defaultTableName);
					else if (!strcmp(tmp, "index"))
						wordDatabaseRemoveIndex(ix, defaultTableName);
					else if (!strcmp(tmp, "item"))
						wordDatabaseRemoveItem(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@update"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					logMsg(LOGDEBUG, "@update requested");
					if (!strcmp(tmp, "item"))
						wordDatabaseUpdateItem(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@amend"))) {
//		-----------------------------------------
			if (args) {
				getWord(tmp, args, 1, " \t");
				if (*tmp) {
					logMsg(LOGDEBUG, "@amend requested");
					if (!strcmp(tmp, "item"))
						wordDatabaseAmendItem(ix, defaultTableName);
				}
			}
//		-----------------------------------------
		} else if (!(strcmp(cmd, "@list"))) {
//		-----------------------------------------
		if (args) {
			getWord(tmp, args, 1, " \t");
			if (*tmp) {
				if (!strcmp(tmp, "databases"))
					wordDatabaseListDatabases(ix, defaultTableName);
				else if (!strcmp(tmp, "tables"))
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
//		------------------------------------
		} else if (!(strcmp(cmd, "@sql"))) {
//		------------------------------------
			wordDatabaseSql(ix, defaultTableName);
//		------------------------------------
		} else if (!(strcmp(cmd, "@each"))) {
//		-------------------------------------
			// This is either a list or a db table
			char *listName = (char *) calloc(1, 4096);
			getWord(listName, args, 1, " \t");
			logMsg(LOGDEBUG, "@each - looking to see if [%s] is a list (exists as a variable)", listName);
			VAR *listVar = findVarStrict(listName);
			if (listVar) {	// its a list
				logMsg(LOGDEBUG, "Its a list. Do listfirst() for list [%s]", listName);
				char *p = (char *) listFirst(listName);
				while (p) {
					emitData(jam[ix]->trailer);
					logMsg(LOGMICRO, "setting list variable [%s] to value [%s]", listName, p);
					clearVarValues(listVar);
					fillVarDataTypes(listVar, p);
					logMsg(LOGMICRO, "@each (list %s) starting recurse", listName);
					cmdSeqnum++;		// up the unique sequence number
					control((ix + 1), defaultTableName);
					logMsg(LOGMICRO, "@each (list %s) ended recurse", listName);
					p = (char *) listNext(listName);
				}
				while (jam[ix] && (strcmp(jam[ix]->command, "@end"))) {
					ix++;
				}
				if (jam[ix]) {
					emitData(jam[ix]->trailer);
				}
			} else {		// its a db table
				logMsg(LOGDEBUG, "Its a db, not a list. do the select()");
				char *givenTableName = (char *) calloc(1, 4096);
				MYSQL_RES *res = doSqlSelect(ix, defaultTableName, &givenTableName, 999999);
				SQL_RESULT *rp = sqlCreateResult(givenTableName, res);
				while (sqlGetRow2Vars(rp) != SQL_EOF) {
					emitData(jam[ix]->trailer);
					logMsg(LOGMICRO, "@each (db table %s) starting recurse", givenTableName);
					cmdSeqnum++;		// up the unique sequence number
					control((ix + 1), givenTableName);
					logMsg(LOGMICRO, "@each (db table %s) ended recurse", givenTableName);
				}
				// Finished. Now emit the loops' trailer and make it current, so we will immediately advance past it
				while (jam[ix] && (strcmp(jam[ix]->command, "@end") || (strcmp(jam[ix]->args, givenTableName)))) {
					ix++;
				}
				if (jam[ix])
					emitData(jam[ix]->trailer);
				mysql_free_result(res);
				free(givenTableName);
			}
//		-------------------------------------
		} else if (!(strcmp(cmd, "@runaction"))) {
//		-------------------------------------
			int startIx = 0;
			int aix = 0;
			while (jam[aix]) {
				if ((!strcmp(jam[aix]->command, "@action")) && (!strcmp(jam[aix]->args, jam[ix]->args))) {
					// Set startpoint
					startIx = aix;
					break;
				}
				aix++;
			}
			if (startIx == 0)
				logMsg(LOGERROR, "Cant find action [%s] to run from within jam script", jam[ix]->args);
			else
				logMsg(LOGINFO, "Running @action [%s] within jam script", jam[startIx]->args);
			control(startIx, NULL);
			logMsg(LOGINFO, "Finished running action [%s] within jam script");
//		-------------------------------------
		} else if (!(strcmp(cmd, "@action"))) {
//		-------------------------------------
			if (!jamEntrypoint) {		// not for us - ignore completely
				while (jam[ix] && (strcmp(jam[ix]->command, "@end")) ) {
					if (!strcmp(jam[ix]->command, "@each")) {
						while (jam[ix] && (strcmp(jam[ix]->command, "@end")) )
							ix++;
					}
					if (jam[ix])
						ix++;
				}
				if (jam[ix])
					emitData(jam[ix]->trailer);
			} else {					// for us - run and stop
				emitData(jam[ix]->trailer);
				VAR *v = findVarStrict("_dbname");
				if ((v) && (v->portableValue) && (strlen(v->portableValue))) {
					logMsg(LOGDEBUG, "@action preprocess - _dbname '%s' was given", v->portableValue);
					if (openDB(v->portableValue) != 0) {
						return(-1);
					}
				} else logMsg(LOGDEBUG, "@action preprocess - no _dbname was given, assuming db already open");
				logMsg(LOGMICRO, "@action starting recurse");
				control((ix + 1), defaultTableName);
				logMsg(LOGMICRO, "@action ended recurse");
				// Now emit the loops' trailer and stop
				while (jam[ix] && (strcmp(jam[ix]->command, "@end")) )
					ix++;
				free(tmp);
				return(0);
			}
//		------------------------------------
		} else if (!(strcmp(cmd, "@end"))) {
//		------------------------------------
			// Return from an each-end or action-end loop
//emitData("RETURNING\n");
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
//		------------------------------------
		} else if (!(strcmp(cmd, "@email"))) {
//		------------------------------------
			wordMiscEmail(ix, defaultTableName);
//		---------------------------
		} else if (!(strcmp(cmd, "@type"))) {
//		------------------------------------
			wordMiscType(ix, defaultTableName);
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
				emitData(result string)		*/

			// data = LHS to start with
			char *data = (char *) calloc(1, 4096);
			strcpy(data, cmd);							// eg: [mem.group_count]
//emitData("{AWAY:%s and %s}",cmd, data);

			char *resultString = (char *) calloc(1, 4096);
			char *fullLine = (char *) calloc(1, 4096);
			strcpy(fullLine, cmd);
//emitData("(CHK:%s and %s)", fullLine, args);
			if (args)
				strcpy(fullLine, rawData);
			strTrim(fullLine);
//emitData("T=[%s]\n",fullLine);
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
//emitData("\nSTART.... [c=%s][a=%s][f=%s][r=%s][e%s]\n", cmd, args, fullLine, data, expandedData);

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
//emitData("NEW-->%s<-- with value -->%s<--\n", assignVar->name, assignVar->portableValue);
					assignVar->debugHighlight = 4;
					if (addVar(assignVar) == -1) {
						logMsg(LOGFATAL, "Cant create any more vars, terminating");
						exit(1);
					}
				}
			} else {		// Not an assignment - just emit variable
				emitData(resultString);
				// Clear if necessary
				VAR *variable = findVarLenient(cmd, defaultTableName);
				if (variable) {
//emitData("RETR-->%s<--\n", variable->name);
//char xx[256]; sprintf(xx, "R[%s]:%s", resultString, variable->portableValue);
//emitData(xx);
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
			emitData(jam[ix]->trailer);
			free(data);
			free(expandedData);
			free(resultString);
			free(fullLine);
//		--------
		} else {
//		--------
			emitData(jam[ix]->trailer);
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
	emitData("MySQL Tables in mysql database:\n");
	while ((row = mysql_fetch_row(res)) != NULL)
	emitData("%s \n", row[0]);
	/* close connection */
	mysql_free_result(res);
	mysql_close(conn);
}
