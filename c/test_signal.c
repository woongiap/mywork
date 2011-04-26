#define _XOPEN_SOURCE 500
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>

static int run;

static void handle_usr1(int sig)
{
	printf("I got this signal %d\n", sig);
	run = 0;
}

int main(int argc, char ** args)
{
	struct sigaction sa;
	int count = 1;  
	run = 1;
	memset(&sa, 0, sizeof(sa));
	sa.sa_handler = handle_usr1;
	sigaction(SIGUSR1, &sa, NULL);
	signal(SIGUSR2, SIG_IGN);
	// sigset is deprecated
	//sigset(SIGUSR1, handle_usr1);
	
	while (run) {
		fprintf(stdout, "counting %d...   \r", count++);
		fflush(stdout);
	}
	while (!run) {
		puts("after signal");
	}
	exit(0);
}

