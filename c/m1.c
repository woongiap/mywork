#include <stdio.h>

extern int g_int;

void m1_f1()
{
	printf("global int in m1_f1 : %d\n", g_int++);
}

