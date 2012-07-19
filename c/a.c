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

struct address {
	char street[5];
};

struct person
{
	char name[10];	/* 10 bytes */
	int age;	/* 4 bytes */
	short s1;	/* 2 bytes */
	long l1;	/* 4 bytes */
	struct address *add1;	/* 4 bytes */
	struct address *add2;	/* 4 bytes */
	union {
		int n;
		char *a;
	} u;
};

int main(int argc, char** args)
{	
	int *pi = NULL;
	//pi = &argc;
	printf("result number is %d %d\n", a(pi), lib_done(0));
	printf("sizeof(struct person) : %d\n", sizeof(struct person));
	printf("sizeof(struct person *) : %d\n", sizeof(struct person *));
	printf("sizeof(short) : %d\n", sizeof(short));
	printf("sizeof(char) : %d\n", sizeof(char));
	printf("sizeof(int) : %d\n", sizeof(int));
	printf("sizeof(long) : %d\n", sizeof(long));
	return 0;
}

