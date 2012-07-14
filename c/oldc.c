// oldc.cpp : Defines the entry point for the console application.

#include <stdio.h>
#include <stdlib.h>

void change_param(int*);
void print_array(int[], int);

int main()
{
	int ia[3] = {1,2,3};
	int addr;
	print_array(ia, 3);
	ia[0] = 10;
	ia[1] = 20;
	ia[2] = 30;
	print_array(ia, 3);
	*ia = 100;
	*(ia + 1) = 200;
	printf("(ia + 1) : %d\n", (ia + 1));
	*(ia + 2) = 300;
	print_array(ia, 3);
	addr = ia; /* assign pointer to int */
	*(int*)addr = 1000;
	*(int*)(addr + 4) = 2000;
	printf("(addr + 4) : %d\n", (addr + 4));
	*(int*)(addr + 8) = 3000;
	print_array(ia, 3);

	printf("char size %d\n", sizeof(char));
	printf("short size %d\n", sizeof(short int));
	printf("int size %d\n", sizeof(int));
	printf("long size %d\n", sizeof(long int));
	printf("float size %d\n", sizeof(float));
	printf("double size %d\n", sizeof(double));
	printf("long double size %d\n", sizeof(long double));
	return 0;
}

int main_()
{
	int x1 = 1;
	int *p1 = &x1;

	printf("x1 : %d\t", x1);
	printf("*p1 : %d\n", *p1);

	(*p1)+=5;

	printf("x1 : %d\t", x1);
	printf("*p1 : %d\n", *p1);

	change_param(p1);

	printf("x1 : %d\t", x1);
	printf("*p1 : %d\n", *p1);

	change_param(&x1);

	printf("x1 : %d\t", x1);
	printf("*p1 : %d\n", *p1);

	int i_arr[3] = {10,20,30};
	printf("sizeof(i_arr) : %d\n", sizeof(i_arr));
	printf("i_arr : %d\n", i_arr);
	printf("i_arr+1 : %d\n", i_arr+1);

	print_array(i_arr, 3);
	change_param(&i_arr[0]);

	print_array(i_arr, 3);
	change_param(i_arr+1);

	print_array(i_arr, 3);
	change_param(i_arr+2);

	print_array(i_arr, 3);

#ifdef NGIAP
	printf("NGIAP is defiend!!!");
#endif
	int *ipp;
	ipp = (int*)0x12ff20; // assign the address of first element of the array to ipp
	int first_addr = i_arr;
	*(int*)first_addr = 0xA;
	*(int*)(first_addr+4) = 0xB;
	*(int*)(first_addr+8) = 0xC;

	print_array(i_arr, 3);

	getchar();
	return EXIT_SUCCESS;
}

void change_param(int *ip)
{
	static int no_of_call = 0;
	/* change value to 8888 */
	*ip = 8888;
	printf("has been called %d times\n", ++no_of_call);
}

void print_array(int to_print[], int size)
{
	int i;
	for (i=0; i<size; i++)
	{
		printf("array[%d] : %d\n", i,to_print[i]);
	}
}

