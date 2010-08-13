CC		= gcc
MAKE		= make
EXE		= freax.exe

main: sstring.o cfg.o
	@echo "building executable"
	@$(CC) -Wall -DDEBUG sstring.o cfg.o split.c -o $(EXE)

sstring.o:
	@echo "building objects"
	@$(CC) -Wall -c sstring.c -o sstring.o
	
cfg.o:
	@$(CC) -Wall -c cfg.c -o cfg.o

clean: clean-objects
	@echo "removing executable"
	@del $(EXE)

clean-objects:
	@echo "removing objects"
	@del *.o
