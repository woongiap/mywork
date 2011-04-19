#include <stdio.h>

int main()
{
	int i = 123;
	const int ci = 123;
	printf("ci initial value %d\n", ci);
	int* pi = &i;
	const int* pci = &ci;
	
	//ci = 8000;
	//*pci = 8000;	
	pi = (int*)pci;
	*pi = 8000; /* try to change ci value */
	printf("ci value %d\n", ci);
	return 0;
}

