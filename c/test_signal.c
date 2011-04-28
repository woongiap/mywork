#define _XOPEN_SOURCE 500
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>

static int run;
struct man
{
	int age;
	char name[20];
	float money;
};
struct man me;

static void handle_usr1(int sig)
{
	printf("\nGot this signal %d\n", sig);
	run = 0;
}

int main(int argc, char *args[])
{
	struct sigaction sa;
	int count = 1;
	printf("run is %d\n", run);
	run = 1;
	memset(&sa, 0, sizeof(sa));
	//memset(&me, 0, sizeof(me)); // do this if 'me' is local
	sa.sa_handler = handle_usr1;
	sigaction(SIGUSR1, &sa, NULL);
	signal(SIGUSR2, SIG_IGN);
	// sigset is deprecated
	//sigset(SIGUSR1, handle_usr1);
	fprintf(stdout, "me age %d name %s money %f\n", me.age, me.name, me.money);
	while (run) {
		fprintf(stdout, "counting %d...   \r", count++);
		fflush(stdout);
	}
	while (!run) {
		fprintf(stdout, "continue counting %d...\r", count++);
		fflush(stdout);
	}
	exit(0);
}

