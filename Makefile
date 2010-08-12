hello : a.o b.o
	gcc -o hello a.o b.o

b.o : b.c
	gcc -c b.c

a.o : a.c
	gcc -c a.c

clean :
	rm hello a.o b.o
