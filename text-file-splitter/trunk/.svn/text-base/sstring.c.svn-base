#include <string.h>
#include <stdio.h>
#include <ctype.h>
#include "sstring.h"

/* return NULL if error */
char * trim_both(const char *source, char *result)
{
	if (source == NULL || strlen(source) == 0)
		return NULL;
		
	unsigned int start, index, end;
	/* from start */
	for (index = 0; index < strlen(source); index++) {
		if ( !isspace(source[index]) ) {
			start = index;
			break;
		}
	}

	/* from end */
	for (index = strlen(source) - 1; index >= 0; index--) {
		if ( !isspace(source[index]) ) {
			end = index;
			break;
		}		
	}
	for (index = start; index <= end; index++) {
		*result++ = source[index];
	} 
	*result = '\0';
	return result;		
}

/* return NULL if error */
char * trim_left(const char *source, char *result)
{
	if (source == NULL || strlen(source) == 0)
		return NULL;
		
	unsigned int start = 0, index, source_len = strlen(source), end = source_len - 1;
	
	/* from left */
	for (index = 0; index < source_len; index++) {
		if ( !isspace(source[index]) ) {
			start = index;
			break;
		}
	}
	for (index = start; index <= end; index++) {
		*result++ = source[index];
	} 
	*result = '\0';
	return result;		
}

/* return NULL if error */
char * trim_right(const char *source, char *result)
{
	if (source == NULL || strlen(source) == 0)
		return NULL;
		
	int start = 0, index, source_len = strlen(source);
	int end;
	/* from right */
	for (index = source_len - 1; index >= 0; index--) {
		if (!isspace(source[index])) {
			end = index;
			break;
		}		
	}
	for (index = start; index <= end; index++) {
		*result++ = source[index];
	} 
	*result = '\0';
	return result;		
}

char * get_key_value(const char *line, char *key_value, int key_len, int key_value_len)
{
	/* pattern: TRADER: T02   Name of T02 */	
	/* todo: change to strcpy */
	memcpy(key_value, line + key_len, key_value_len);	
	key_value[key_value_len] = 0; /* '\0' also can :-) */
	return key_value;
}

void change_path(char *path)
{
	/* do nothing on non-windows */
#ifdef	__WINNT__
	/* must be /a/b/c/ */
	char *ptr, delim = '\\';
	ptr = strchr(path, '/');
	while (ptr != NULL) {
		memset(ptr, delim, 1);
		ptr = strchr(ptr+1, '/');
	}
#endif
}

int smain(void)
{
	FILE *f = fopen("a.txt", "r");	
	char str_ptr[255];
	fgets(str_ptr, 1024, f);
	fclose(f);
	printf("str ptr=[%s]\n", str_ptr);
	char str[255];
	strcpy(str, str_ptr);
	printf("str=[%s]\n", str);
	change_path(str);
	printf("str=[%s]\n", str);	
	return 0;
}

