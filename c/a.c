#include <stdio.h>

int lib_do(int);
int lib_done(int);

int a(int* i)
{
	int abc;
	if (i)
		abc = *i;
	else
		abc = lib_do(0);
  	return abc;
}

int main(int argc, char** args)
{	
	int *pi = NULL;
	//pi = &argc;
	printf("result number is %d %d\n", a(pi), lib_done(0));
	return 0;
}

