// Executable goes in the site's /jam/cgi/

#include <stdio.h>
#include <malloc.h>
#include <sys/stat.h>
#include <limits.h>
#include <string.h>

#define MAXBUFLEN 1000000
#define JAM_PATH "/home/dev/src/jam/cgi/jam"

main(int argc, char *argv[]) {
	char *source = (char *) calloc(1, MAXBUFLEN + 1);
	FILE *fp = fopen("runjam.conf", "r");
	if (fp != NULL) {
		size_t len = fread(source, sizeof(char), MAXBUFLEN, fp);
		if (len == 0)
			fputs("Error reading file", stderr);
		else
			source[++len] = '\0';
		fclose(fp);
	} else
		strcpy(source, JAM_PATH);

	// Only run things that end in 'jam'
	char tmp[4];
	strcpy(tmp, &source[strlen(source) - 3]);
	if (!strcmp(tmp, "jam"))
		execv(source, argv);
	free(source);
}
