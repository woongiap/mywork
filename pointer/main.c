#include <stdio.h>
#include <stdlib.h>

typedef struct {
    int age;
    char name[20];
} person_t;

int main(int argc, char **argv)
{
    int *dayp, day[] = {1, 2, 3, 4 ,5, 6, 7}, i;
    person_t *john;
    int c;

    printf("number of arguments: %d\n", argc);
    dayp = day;
    for (i = 0; i < 7; i++) {
        printf("day %d\t", *(dayp + i));
    }
    john = malloc(sizeof(john));
    if (!john) {
        perror("fail to create person");
        exit(1);
    }
    john->age = 28;
    printf("\nage of john: %d\n", john->age);
    printf("sizeof *john: %d\t sizeof john: %d\tsizeof person_t *: %d\n", sizeof(*john), sizeof(john), sizeof(person_t *));
    free(john);
    while ((c = getchar()) != EOF) {
        putchar(c);
    }
    puts("end\n");
    return 0;
}
