#include <SDL.h>
#include <stdio.h>

int main(int argc, char *argv[])
{
    printf("initializing sdl...\n");

    if (SDL_Init(SDL_INIT_VIDEO | SDL_INIT_AUDIO) == -1) {
        printf("couldn't initialize sdl: %s\n", SDL_GetError());
        exit(-1);
    }

    printf("sdl initialized\n");

    printf("quitting sdl\n");
    SDL_Quit();

    exit(0);
}
