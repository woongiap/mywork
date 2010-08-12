#include <stdio.h>
#include <stdlib.h>
#include <string.h>


struct a_t
{
	char a[3];
	int b;
};

void strcpy1(char *dest, char *src)
{
	while (*src)
		*dest++ = *src++;
	*dest = '\0';
}

int main(void)
{
#ifdef __WINNT__
	printf("__WINNT__\n");
#elif
	printf("NO __WINNT__");
#endif

	char* hello = "hello";
	printf("strlen(hello) [%d]\n", strlen(hello));

	char* str = malloc(50);	
	memset(str, 0, 50);
	strcpy(str, "hello");
	strcat(str, " world");
	printf("result [%s]\n", str);
	free(str);
	
	// buffer overflow?
	struct a_t *att;
	strcpy(att->a, "1234567890");
	printf("a [%s] b[%x]\n", att->a, att->b);
	
	char *oh = "hello!!!", *h = malloc(sizeof(oh));
	strcpy1(h , oh);
	//h = oh;
	printf("h=[%s]\n", h);
	
	return 0;
}
