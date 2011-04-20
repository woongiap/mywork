CC = gcc
EXE = hello
CFLAGS = -Wall -s
LDFLAGS =

hello : b.o c.o
	$(CC) -o $(EXE) $(CFLAGS) a.c b.o c.o $(LDFLAGS)

b.o : b.c
	$(CC) -c b.c $(CFLAGS)

c.o : c.c
	$(CC) -c c.c $(CFLAGS)

clean :
	rm $(EXE) b.o c.o
	

