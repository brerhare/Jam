// #include "stdafx.h"

#include <unistd.h>
#include <stdio.h>
#include <stdarg.h>
#include <string.h>
#include <stdlib.h>
#include <malloc.h>
#include <sys/stat.h>
#include <time.h>
#include <locale.h>

#include "log.h"

int logLevel = LOGDEBUG;
FILE *log_message_stream = NULL;
char *logFileName = "/tmp/log.dat";
long logMaxBytes = 10000000L;

void logMsg(int type, char *str, ...)
{
	va_list ap;

	long length = 0;

	if (type < logLevel)
		return;

	//if (log_message_stream == NULL)
		//log_message_stream = stderr;

	if (log_message_stream == NULL) {
		log_message_stream = fopen(logFileName, "a+");
	}

/*
FILE *fp = fopen("c:/tmp/xx.log", "a");
va_start(ap, str);
vfprintf(fp, str, ap );
fprintf(fp, "\n");
fflush(fp);
fclose(fp);
va_end(ap);
//return;
*/

	// Check for logmaxbytes exceeded
	fseek(log_message_stream, 0, SEEK_END);
	length = ftell(log_message_stream);
	if (length > logMaxBytes)
	{
		fprintf(log_message_stream, "Maximum log size reached on file %s\n", logFileName);
		fflush(log_message_stream);
		struct stat file_stat;
		FILE *fpOld;
		int bytes;
		char *buf = (char *) malloc(1024);
		char *logOld = (char *) malloc(strlen(logFileName) + 10);
		strcpy(logOld, logFileName);
		strcat(logOld, ".old");
		if ((stat(logOld, &file_stat)) == 0)
		{
#ifdef WIN32
			if (DeleteFile(logOld) == 0)
#else
			if (unlink(logOld) != 0)
#endif
				/*exit(-1)*/ ;
		}
		if ((fpOld = fopen(logOld, "w")) == NULL)
			/*exit(-1)*/ ;
		else
		{
			fclose(log_message_stream);
			if ((log_message_stream = fopen(logFileName, "r")) == NULL)
			{
				exit(-1);
			}
			while ((bytes = fread(buf, sizeof(char), 1024, log_message_stream)) > 0)
				bytes = fwrite(buf, sizeof(char), bytes, fpOld);
			fclose(fpOld);
		}
		fclose(log_message_stream);
#ifdef WIN32
		if (DeleteFile(logFileName) == 0)
#else
		if (unlink(logFileName) != 0)
#endif
			/*exit(-1)*/ ;
		if ((log_message_stream = fopen(logFileName, "a")) == NULL)
		{
			log_message_stream = stderr;
			logMsg(LOGERROR, "Cant open log file - aborting");
			exit(-1);
		}
		free(buf);
		free(logOld);
	}

	// Timestamp
	char nowStr[80];
	time_t nowBin;
	const struct tm *nowStruct;
	(void) setlocale(LC_ALL, "");
	time(&nowBin);
	nowStruct = localtime(&nowBin);
	strftime(nowStr, 80 , "%d%b%y %H:%M:%S ", nowStruct);
	fprintf(log_message_stream, nowStr);

	va_start(ap, str);
	switch (type)
	{
		case LOGMICRO:
			fprintf(log_message_stream, "  Micro:  ");
			break;
		case LOGDEBUG:
			fprintf(log_message_stream, "  Debug:  ");
			break;
		case LOGINFO:
			fprintf(log_message_stream, "   Info:  ");
			break;
		case LOGWARN:
			fprintf(log_message_stream, "Warning:  ");
			break;
		case LOGERROR:
			fprintf(log_message_stream, "  Error:  ");
			break;
		case LOGFATAL:
			fprintf(log_message_stream, "  Fatal:  ");
			break;
		default:
			fprintf(log_message_stream, "(Unknown log type):  ");
			break;
	}
	vfprintf(log_message_stream, str, ap);
	fprintf(log_message_stream, "\n");
	fflush(log_message_stream);
	va_end(ap);
}

/*
int main() {
	printf("xxx");
	logMsg(LOGINFO, "SSSSSSS");
}
*/
