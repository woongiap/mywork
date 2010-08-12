#include <stdio.h>

int lib_do(int);

int main(int argc, char **argv)
{
  int age, year = 1977;

  scanf("%d", &year);
  age = 2010 - year;
  printf("my age is %d\n", age);

  fprintf(stdout, "lib_do int is %d\n", lib_do(5));
  return 0;
}
