#include <stdio.h>

int a (int* i) {
  int abc = *i;
  return abc;
}
int main(void) {
  printf("hello number is %d\n", 2008);
  int* pi = 0;
  int i = a(pi);
  return 0;
}
