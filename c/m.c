#include <stdio.h>
#include <stdlib.h>

void print_hello();
void accept_function(void (*func)(void));

/*
 * this is main method
 */
int main()
{
	void (*fp)(void);
	float version = 0.1f;
	int in_c;
	
	fp = print_hello;
	accept_function(print_hello);
	accept_function(fp);	
	printf("ANSI C program version %f\ntype something:", version);
	in_c = getc(stdin);
	printf("\nyou pressed %c", in_c);
	return 0;
}

void print_hello()
{
	static int s_int = 0;
	printf("print_hello() %d\n", s_int);	
}

void accept_function(void (*func)(void))
{
	func();
}



