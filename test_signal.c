#define _XOPEN_SOURCE 500
#include <stdio.h>
#include <stdlib.h>
#include <signal.h>

static int run;

void handle_usr1(int sig)
{
	printf("I got this signal %d\n", sig);
	run = 0;
}

int main(int argc, char ** args)
{
	run = 1;
	sigset(SIGUSR1, handle_usr1);
	while (run) {
		puts("running...");
	}
	while (!run) {
		puts("after signal");
	}
	exit(0);
}

