#include <stdio.h>
#include <string.h>

int main(void)
{
	FILE *new_file = fopen("a.txt", "w");
	perror("error");
	fprintf(new_file, "%s\n", "new file");
	perror("error");
	fclose(new_file);
	perror("error");

	rename("a.txt", "folder\\new-a.txt");
	perror("error");
	
	char str1[10];
	strcpy(str1, "12345678");
	strcpy(str1, "a");
	printf("str1[%s]\n", str1);
	return 0;
}
