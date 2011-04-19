#include <stdio.h>
#include <stdlib.h>
#include <limits.h>

int g_int = 88;

void m1_f1();

int main() {
	/*
	float f_var;
	double d_var;
	long double ld_var;
	int i;
	i = 0;
	char a = CHAR_MIN;
	printf("fanherit to celsius\n");
	
	while (a != CHAR_MAX)
	{
		printf("%d | %c \n", a++, a);
	}
	*/

	printf("global int in main before ++: %d\n", g_int);
	m1_f1();
	printf("global int in main after ++: %d\n", g_int);
	exit(EXIT_SUCCESS);
}		
