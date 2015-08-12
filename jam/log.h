#ifndef LOG_H_INCLUDED
#define LOG_H_INCLUDED

#include <stdio.h>

#ifdef __cplusplus
#define C_FILE_START extern "C" {
#define C_FILE_END }
#else
#define C_FILE_START
#define C_FILE_END
#endif

C_FILE_START

#define GOOD 0
#define BAD -1
#define UGLY 1

#define LOGFINE 1
#define LOGDEBUG 2
#define LOGINFO  3
#define LOGWARN 4
#define LOGERROR 5
#define LOGFATAL 6

extern int logLevel;
extern FILE *log_message_stream;
extern char *logFileName;
extern long logMaxBytes;

// Protos
int logStart(char *argv);
void logMsg(int type, char *str, ...);

C_FILE_END

#endif
