#include <stdio.h>
#include <strings.h>
#include <string.h>
#include <string>
#include <iostream>
#include <fstream>
#include <vector>
#include <cstdlib>

#include </usr/include/mysql/mysql.h>

#include "wordHtml.h"
#include "common.h"
#include "stringUtil.h"
#include "database.h"
#include "log.h"

#define MAX_BREAKPOINTS 10000	
// Breakpoint vars collected along the way that will now be used to generate stuff									// quite a few
int breakpointAutocompleteId[MAX_BREAKPOINTS];

#define INP 0
#define INPUT 1
#define GRIDINP 2
#define GRIDINPUT 3

//-----------------------------------------------------------------
// HTML <tag> generation from {{curly}}

int wordHtmlGrid(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *tableName = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(tableName, args, 2, " ");
	if (!tableName)
	   die("missing table name to gridify");

	emit(jam[ix]->trailer);
	free(tableName);
	free(tmp);
}

int _wordHtmlInputInp(int ix, char *defaultTableName, int inputType) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldType = (char *) calloc(1, 4096);
	char *fieldVar = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);			// NB this is 'jam:action' for keyaction
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldSearchValue = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);		// NB this is 'size' for keyaction
	char *fieldPlaceholder = (char *) calloc(1, 4096);	
	char *disabledStr = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	int randId = rand() % 9999999;	// default to non-grid
	VAR *variable = NULL;
	VAR *variableSearch = NULL;

	if ((inputType == GRIDINPUT) || (inputType == GRIDINP))
		randId = cmdSeqnum;

	getWord(fieldType, args, 2, " \t");
	if (!fieldType) {
	   logMsg(LOGERROR, "missing input type for html input");
	   return(-1);
	}
	getWord(fieldVar, args, 3, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html input");
	   return(-1);
	}
	getWord(fieldSize, args, 4, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html input");
	   return(-1);
	}

	if (strcasecmp(fieldType, "disabled")) {
		if ((!strchr(fieldVar, '.')) && (defaultTableName))
			sprintf(fieldVar, "%s.%s", defaultTableName, fieldVar);
	}
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	if (!strcasecmp(fieldType, "filter")) {
		if ((!strchr(fieldSize, '.')) && (defaultTableName))
			sprintf(fieldSize, "%s.%s", defaultTableName, fieldSize);
		variableSearch = findVarStrict(fieldSize);
		if (variableSearch)
			strcpy(fieldSearchValue, variableSearch->portableValue);
	}

	getWord(fieldPrompt, args, 5, " \t");
	getWord(fieldPlaceholder, args, 6, " \t");

	if ((inputType == INPUT) || (inputType == GRIDINPUT)) {
		printf("<div class='uk-form-row'>\n");
		if (!strcasecmp(fieldType, "filter"))
			printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPlaceholder);
		else
			printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
		printf("		<div class='uk-form-controls'>\n");
	}
	if (!strcasecmp(fieldType, "filter")) {
		scratchJs(	"// Handler for autocomplete (%d) \n", randId);
		scratchJs(	"function SEQ_%d_SEARCH_DIV_AJAX(release) { \n", randId);
		scratchJs(	"	$.ajax({ \n");
		scratchJs(	"		url : '/run/sys/autocomplete:filterAutocomplete', type: 'POST', data : '_filtervalue='+document.getElementById('SEQ_%d_SEARCH_VALUE').value+'&_filterfield='+document.getElementById('SEQ_%d_SEARCH_FIELDNAME').value+'&_dbname='+document.getElementById('_dbname').value, \n", randId, randId);
		scratchJs(	"		success: function(data, textStatus, jqXHR) { \n");
		scratchJs(	"			var dat = []; \n");
		scratchJs(	"			dat = JSON.parse(data); \n");
		scratchJs(	"			release(dat); \n");
		scratchJs(	"		}, \n");
		scratchJs(	"		error: function (jqXHR, textStatus, errorThrown) { \n");
		scratchJs(	"			alert('autocomplete ajax call failed'); \n");
		scratchJs(	"		}, \n");
		scratchJs(	"	}); \n");
		scratchJs(	"} \n");
		// Add to breakpoint
		int i;
		for (i = 0; i < MAX_BREAKPOINTS; i++) {
			if (breakpointAutocompleteId[i] < 1 ) {
				breakpointAutocompleteId[i] = randId;
				break;
			}
		}
		if (i == MAX_BREAKPOINTS) {
			logMsg(LOGFATAL, "Max breakpoints reached");
			return(-1);
		}

#ifdef ABC
filter:       fieldType  fieldVar->fieldVarValue              fieldSize->fieldSearchValue  prompt  placeholder
        1     2          3                                    4                            5       6
{{@html input filter    stock_supplier_order.stock_supplier_id stock_supplier.name medium Supplier}}
#endif
		printf("<input type='hidden' id='SEQ_%d_SEARCH_FIELDNAME' value='%s'> \n", randId, fieldSize);
		printf("<input type='hidden' id='SEQ_%d_SEARCH_RESULT' name='%s' value='%s'> \n", randId, fieldVar, fieldVarValue);
		printf("<div id='SEQ_%d_SEARCH_DIV' class='uk-autocomplete uk-form' data-uk-autocomplete='off'> \n", randId);
		printf("	<input type='text' id='SEQ_%d_SEARCH_VALUE' value='%s' class='uk-form-width-%s'> \n", randId, fieldSearchValue, fieldPrompt);
		printf("	<script type='text/autocomplete'> \n");
		printf("		<ul class='uk-nav uk-nav-autocomplete uk-autocomplete-results'> \n");
		printf("			{{~items}} \n");
		printf("				<li class='clicked' data-value='{{ $item.value }}'  data-id='{{ $item.id }}'> \n");
		printf("					<a> {{ $item.value }} </a> \n");
		printf("				</li> \n");
		printf("			{{/items}} \n");
		printf("		</ul> \n");
		printf("	</script> \n");
		printf("</div> \n");
	}
	else if (!strcasecmp(fieldType, "keyaction")) {
		scratchJs(	"// onKeyUp handler for keyaction ID %d \n"
					"function onKeyUp_%d() { \n"
					"	var valel = document.getElementById('keyaction_%d'); \n"
					"	var el = document.getElementById('keyaction'); \n"
					"	if (el == null) { \n"
					"		var input = document.createElement('input'); \n"
					"		input.setAttribute('type', 'hidden'); \n"
					"		input.setAttribute('name', 'keyaction'); \n"
					"		input.setAttribute('id', 'keyaction'); \n"
					"		input.setAttribute('value', valel.value); \n"
					"		document.body.appendChild(input); \n"
					"	} else { \n"
					"		el.setAttribute('value', valel.value); \n"
					"	} \n"
					"	runAction('%s', 'keyaction %s', '%s'); \n"
					"} \n"
					, randId, randId, randId, fieldVar /*actually jam:action*/, fieldSize /*actually input*/, fieldPrompt /*actually outputResult*/);
		printf("		<input type='text' name=keyaction_%d id='keyaction_%d' value='' onkeyup='onKeyUp_%d()' class='uk-form-width-%s'>\n", randId, randId, randId, fieldPlaceholder);
	} else {
		if (!strcasecmp(fieldType, "disabled")) {
			strcpy(disabledStr, " disabled ");
			strcpy(fieldType, "text");
		}
		if ((inputType == INPUT) || (inputType == GRIDINPUT))
			printf("		<input type='%s' name='%s' id='SEQ_%d_%s' value='%s' placeholder='%s' class='uk-form-width-%s' onChange='fn(this, event);' %s>\n", fieldType, fieldVar, randId, fieldVar, fieldVarValue, fieldPlaceholder, fieldSize, disabledStr);
		else		// 'inp' only
			printf("		<input type='%s' name='%s' id='SEQ_%d_%s' value='%s' class='uk-form-width-%s' onChange='fn(this, event)' %s>\n", fieldType, fieldVar, randId, fieldVar, fieldVarValue, fieldSize, disabledStr);
	}
	if ((inputType == INPUT) || (inputType == GRIDINPUT)) {
		printf("	</div>\n");
		printf("</div>\n");
	}
	emit(jam[ix]->trailer);
	free(fieldName);
	free(fieldType);
	free(fieldSize);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldSearchValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(disabledStr);
	free(tmp);
}

int wordHtmlInp(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, INP));
}
int wordHtmlInput(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, INPUT));
}
int wordHtmlGridInp(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, GRIDINP));
}
int wordHtmlGridInput(int ix, char *defaultTableName) {
	return(_wordHtmlInputInp(ix, defaultTableName, GRIDINPUT));
}

//	{{@html textarea stock_supplier.notes 60x5 Notes}}

int wordHtmlTextarea(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldName = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
	char *fieldSize2 = (char *) calloc(1, 4096);
	char *fieldVar = (char *) calloc(1, 4096);
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);
	char *fieldPlaceholder = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	VAR *variable = NULL;

	getWord(fieldVar, args, 2, " \t");
	if (!fieldVar) {
	   logMsg(LOGERROR, "missing input variable for html textarea");
	   return(-1);
	}
	getWord(fieldSize, args, 3, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing input size for html textarea");
	   return(-1);
	}
	getWord(fieldSize2, fieldSize, 2, "x");	// rows
	getWord(fieldSize, fieldSize, 1, "x");	// cols

	if ((!strchr(fieldVar, '.')) && (defaultTableName))
		sprintf(fieldVar, "%s.%s", defaultTableName, fieldVar);
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 4, " \t");
	getWord(fieldPlaceholder, args, 5, " \t");

	printf("<div class='uk-form-row'>\n");
	printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	printf("		<div class='uk-form-controls'>\n");
	printf("		<textarea name='%s' id='%s' cols='%s' rows='%s' placeholder='%s'>%s</textarea>", fieldVar, fieldVar, fieldSize, fieldSize2, fieldPlaceholder, fieldVarValue);
	printf("	</div>\n");
	printf("</div>\n");

	emit(jam[ix]->trailer);
	free(fieldName);
	free(fieldSize);
	free(fieldSize2);
	free(fieldVar);
	free(fieldVarValue);
	free(fieldPrompt);
	free(fieldPlaceholder);
	free(tmp);
}

/*	{{@html button Save primary small
		alert('ok')     // or any js
		runJam
		runJam supplierMaint
		runAction updateSupplier supplierForm outputResult backButton
		runAction updateSupplier supplierForm outputResult runJam supplierMaint    // where supplierMaint is the arg to runJam
}} */

int wordHtmlButton(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *buttonText = (char *) calloc(1, 4096);
	char *buttonType = (char *) calloc(1, 4096);
	char *buttonSize = (char *) calloc(1, 4096);
	char *buttonJS = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	int buttonId = rand() % 9999999;			// @@TODO fix

	getWord(buttonText, args, 2, " \t");
	if (!buttonText) {
	   logMsg(LOGERROR, "missing button text");
	   return(-1);
	}
	getWord(buttonType, args, 3, " \t");
	if (!buttonType) {
	   logMsg(LOGERROR, "missing button type");
	   return(-1);
	}
	getWord(buttonSize, args, 4, " \t");
	if (!buttonSize) {
	   logMsg(LOGERROR, "missing button size");
	   return(-1);
	}

	printf("<button type='button' onClick='buttonClick%d()' class='uk-button uk-button-%s uk-button-%s'>%s</button>\n", buttonId, buttonSize, buttonType, buttonText);
	sprintf(buttonJS, "<script>\nfunction buttonClick%d() {\n", buttonId);
	int cnt = 2;
	while (char *block = strTrim(getWordAlloc(args, cnt++, "\n"))) {
		char *command = strTrim(getWordAlloc(block, 1, " \t"));
		if (!command)
			break;
		if (!strcasecmp(command, "runJam")) {		// Parenthesize argument if any
			char *jamName = strTrim(getWordAlloc(block, 2, " \t"));
			if (jamName)
				sprintf(tmp, "%s('%s');\n", command, jamName);
			else
				sprintf(tmp, "%s();\n", command);
			strcat(buttonJS, tmp);
			free(command);
			free(jamName);
		} else if (!strcasecmp(command, "runAction")) {	// Look for AJAX command, we need to wait for return there
			char *pBlock = strchr(block, ' ');
			sprintf(tmp, "%s(", command);
			strcat(buttonJS, tmp);
			int ix = 1;
//printf("<br> \n----->%s <br>\n", pBlock);
			while(char *runActionArg = strTrim(getWordAlloc(pBlock, ix, " "))) {
				if (!runActionArg)
					break;
//printf("-->%s <br>\n", runActionArg);
				if (ix != 1)
					strcat(buttonJS, ", ");
				if (ix < 4) {
					if ((!strchr(runActionArg, '"')) && (!strchr(runActionArg, '\''))) {
						sprintf(tmp, "'%s'", runActionArg);
						strcat(buttonJS, tmp);
					} else
						strcat(buttonJS, runActionArg);
				}
				else
					strcat(buttonJS, runActionArg);
					//strcat(buttonJS, "@@TODOCALLBACK");
				free(runActionArg);
				ix++;
			}
			strcat(buttonJS, ");\n");
		} else {
			sprintf(tmp, "%s\n", block);
			strcat(buttonJS, tmp);
		}
//printf("-----> <br>\n");
		free(block);
	}
	strcat(buttonJS, "}\n</script>\n");
	printf("%s", buttonJS);
	emit(jam[ix]->trailer);
	free(buttonText);
	free(buttonType);
	free(buttonSize);
	free(buttonJS);
	free(tmp);
}

//	{{@html select stock_supplier.name stock_supplier._id medium "Choose supplier" stock_purchorder.supplier_id}}

int wordHtmlSelect(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *fieldVarName = (char *) calloc(1, 4096);
	char *fieldVarValue = (char *) calloc(1, 4096);
	char *fieldSize = (char *) calloc(1, 4096);
	char *fieldPrompt = (char *) calloc(1, 4096);
	char *fieldVarSelected = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);
	VAR *variable = NULL;
/*
	getWord(fieldVarName, args, 2, " \t");
	if (!fieldVarName) {
	   logMsg(LOGERROR, "missing name variable for select");
	   return(-1);
	}
	getWord(fieldVarValue, args, 3, " \t");
	if (!fieldVarValue) {
	   logMsg(LOGERROR, "missing value variable for select");
	   return(-1);
	}
	getWord(fieldSize, args, 4, " \t");
	if (!fieldSize) {
	   logMsg(LOGERROR, "missing size for select");
	   return(-1);
	}
	getWord(fieldSelected, args, 5, " \t");

	if ((!strchr(fieldVar, '.')) && (defaultTableName))
		sprintf(fieldVar, "%s.%s", fieldVar, defaultTableName);
	variable = findVarStrict(fieldVar);
	if (variable)
		strcpy(fieldVarValue, variable->portableValue);

	getWord(fieldPrompt, args, 4, " \t");
	getWord(fieldPlaceholder, args, 5, " \t");

	printf("<div class='uk-dropdown uk-dropdown-scrollable'>\n");
	printf(""

	printf("<div class='uk-dropdown uk-dropdown-scrollable>\n");
	printf("	<label class='uk-form-label' for='%s'>%s</label>\n", fieldVar, fieldPrompt);
	printf("		<div class='uk-form-controls'>\n");
	printf("		<textarea name='%s' id='%s' cols='%s' rows='%s'>%s</textarea>", fieldVar, fieldVar, fieldSize, fieldSize2, fieldVarValue);
	printf("	</div>\n");
	printf("</div>\n");


<select name="{filter.select}">
	{@each stock_area}
		<option value="{id}">{name}</option>
	{@end}
</select>


	emit(jam[ix]->trailer);
	free(fieldVarName);
	free(fieldVarValue);
	free(fieldSize);
	free(fieldPrompt);
	free(fieldVarSelected);
	free(tmp);
*/
}

//-----------------------------------------------------------------
// HTML sys - 'filterAutocomplete' is called from run/sys/xxxx.jam

int wordHtmlSys(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *sysName = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(sysName, args, 2, " \t");
	if (!sysName)
	   die("missing HTML sys name");

	logMsg(LOGDEBUG, "html sys %s activated", sysName);

	if (!strcasecmp(sysName, "filterAutocomplete")) {
// Autocomplete <input type=filter> cant produce json (yet) or db handle '%like%' (needs embedded curlies to work) so we have this custom operation temporarily - a @@TODO
		char *q = (char *) calloc(1, 4096);
		char *customField = (char *) calloc(1, 4096);
		char *customValue = (char *) calloc(1, 4096);
		char *tableName = (char *) calloc(1, 4096);
		char *fieldName = (char *) calloc(1, 4096);
		getWord(customField, args, 3, " \t");
		if (!customField) {
			logMsg(LOGERROR, "missing field '_filterfield'");
			return(-1);
		}
		getWord(customValue, args, 4, " \t");
		if (!customValue) {
			logMsg(LOGERROR, "missing value '_filtervalue'");
			return(-1);
		}
		VAR *variableField = findVarStrict(customField);
		if (!variableField) {
			logMsg(LOGERROR, "missing custom variable for '_filterfield'. Looking (strict) for '%s'", customField);
			return(-1);	
		}
		VAR *variableValue = findVarStrict(customValue);
		if (!variableValue) {
			logMsg(LOGERROR, "missing custom variable for '_filtervalue'. Looking (strict) for '%s'", customValue);
			return(-1);	
		}
		getWord(tableName, variableField->portableValue, 1, ".");
		getWord(fieldName, variableField->portableValue, 2, ".");
		// Kludge to handle old 'id' vs '_id' field in tables
		char idField[512];
		sprintf(idField, "_id");
		if ( (!strcmp(tableName, "stock_customer"))
		||   (!strcmp(tableName, "stock_location"))
		||   (!strcmp(tableName, "stock_product")) )
			sprintf(idField, "id");
		char searchValue[1024];
		sprintf(searchValue, variableValue->portableValue);
		if (!strcmp(searchValue, " "))
			strcpy(searchValue, "");
		sprintf(q, "select %s, %s from %s where %s like '%%%s%%'", idField, fieldName, tableName, fieldName, searchValue);
		logMsg(LOGDEBUG, "Autocomplete custom prepared query [%s]", q);
		int status = doSqlQuery(q);
		if (status == -1) {
			logMsg(LOGERROR, "Sql query failed - doSqlQuery() failed");
			return (-1);
		}
		logMsg(LOGDEBUG, "Getting RES for raw query");
		MYSQL_RES *res = mysql_store_result(conn);
		if (res != NULL) {	// ie the query returned row(s)
			logMsg(LOGDEBUG, "RES is non-null");
			SQL_RESULT *rp = sqlCreateResult(tableName, res);
			int first = 1;
			printf("[");
			while (sqlGetRow2Var(rp) != SQL_EOF) {
				VAR *v = findVarStrict(variableField->portableValue);
				sprintf(tmp, "%s.%s", tableName, idField);
				VAR *_id = findVarStrict(tmp);
				if ((!v) || (!_id)) {
					logMsg(LOGERROR, "internal error - either cant retrieve row jam variable or its _id");
					return(-1);	
				}
				if (first)
					first = 0;
				else
					printf(", ");
				// Avoid single quotes - the formal JSON spec doesnt allow them
				printf("{\"value\":\"%s\", \"id\":\"%d\"}", v->portableValue,  atoi(_id->portableValue));
			}
			printf("]");
		} else
			logMsg(LOGDEBUG, "RES is null");
		free(q);
		free(customField);
		free(customValue);
		free(tableName);
		free(fieldName);
	}

	emit(jam[ix]->trailer);
	free(sysName);
	free(tmp);
}

//-----------------------------------------------------------------
// HTML breakpoints. End of header, body, etc. Do something at these points

int wordHtmlBreakpoint(int ix, char *defaultTableName) {
	char *cmd = jam[ix]->command;
	char *args = jam[ix]->args;
	char *rawData = jam[ix]->rawData;
	char *breakpointName = (char *) calloc(1, 4096);
	char *tmp = (char *) calloc(1, 4096);

	getWord(breakpointName, args, 2, " \t");
	if (!breakpointName)
	   die("missing HTML breakpoint name");

	logMsg(LOGDEBUG, "html breakpoint %s activated", breakpointName);

	if (!strcasecmp(breakpointName, "body")) {
		char *breakpointBodyArg = (char *) calloc(1, 4096);
		getWord(breakpointBodyArg, args, 3, " \t");
		if (!breakpointBodyArg)
		   die("missing HTML breakpoint body arg");

		if (!strcasecmp(breakpointBodyArg, "end")) {	// Called from sys/html/footer.html
			// GENERATE END STUFF WEVE BEEN COLLECTING
			// ---------------------------------------
			if (breakpointAutocompleteId[0] != 0) {
				// Generate the init for uikit autocomplete
				scratchJs(	"// Include autocomplete JS and CSS \n");
				scratchJs(	"$.getScript('/jam/sys/extern/uikit/js/components/autocomplete.js', initAutocomplete ); \n");
				scratchJs(	"var linkElem = document.createElement('link'); \n");
				scratchJs(	"document.getElementsByTagName('head')[0].appendChild(linkElem); \n");
				scratchJs(	"linkElem.rel = 'stylesheet'; linkElem.type = 'text/css'; \n");
				scratchJs(	"linkElem.href = '/jam/sys/extern/uikit/css/components/autocomplete.css'; \n\n");

				scratchJs(	"function initAutocomplete() { \n");

				scratchJs(	"	// Init autocomplete for zero'th element (grid inserts) \n\n");
				scratchJs(	"	autocomplete = $.UIkit.autocomplete($('#SEQ_0_SEARCH_DIV'), { 'source': SEQ_0_SEARCH_DIV_AJAX, minLength:1}); \n");
				scratchJs(	"	$('#SEQ_0_SEARCH_DIV').on('selectitem.uk.autocomplete', function(event, data, ac){ \n");
				scratchJs(	"		document.getElementById('SEQ_0_SEARCH_RESULT').value = data.id; \n");
				scratchJs("	}); \n");
	
				scratchJs(	"// Init autocomplete for each element \n\n");
				for (int i = 0; breakpointAutocompleteId[i] != 0; i++) {
					scratchJs(	"	autocomplete = $.UIkit.autocomplete($('#SEQ_%d_SEARCH_DIV'), { 'source': SEQ_%d_SEARCH_DIV_AJAX, minLength:1}); \n", breakpointAutocompleteId[i], breakpointAutocompleteId[i]);
					scratchJs(	"	$('#SEQ_%d_SEARCH_DIV').on('selectitem.uk.autocomplete', function(event, data, ac){ \n", breakpointAutocompleteId[i]);
					scratchJs(	"		document.getElementById('SEQ_%d_SEARCH_RESULT').value = data.id; \n", breakpointAutocompleteId[i]);
					scratchJs("	}); \n");
				}
				scratchJs("} \n");
				logMsg(LOGDEBUG, "created init js for uikit autocomplete");
			}
			// Embed the db name in the html for any @action calls
			if (connDbName == NULL)
				connDbName = strdup("");
			printf("<input type='hidden' id='_dbname' name='_dbname' value='%s'>", connDbName);
			logMsg(LOGDEBUG, "created hidden _dbname element");
		}
		free(breakpointBodyArg);
	}
	emit(jam[ix]->trailer);
	free(breakpointName);
	free(tmp);
}
