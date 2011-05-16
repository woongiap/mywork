#include <SDL.h>
#include <stdio.h>
#include <stdlib.h>

#ifndef _DEBUG_ON
#define _DEBUG_ON 0
#else
#define _DEBUG_ON 1
#endif

#define DEBUG(s) if (_DEBUG_ON) fprintf(stderr, "%s\n", s);

#define SCR_WIDTH 400
#define SCR_HEIGHT 400

void putpixel(SDL_Surface *surface, int x, int y, Uint32 pixel);
void die(char *err);
void *smalloc(size_t size);
void debug_surface(const SDL_Surface *sf);

int main(int argc, char *argv[])
{
	char *cp, *s = "abcdefghijklmnopqrswxyz";
	SDL_Surface *screen;
	/* Code to set a blue pixel at the center of the screen */
    	int x, y, n;
    	Uint32 blue;

	DEBUG("Trying to initialize SDL");
	if (SDL_Init(SDL_INIT_VIDEO | SDL_INIT_AUDIO) == -1) {
        	die(SDL_GetError());
    	}    	
	DEBUG("SDL initialized");
	// hook this up at exit
    	atexit(SDL_Quit);
    	screen = SDL_SetVideoMode(SCR_WIDTH, SCR_HEIGHT, 16, SDL_HWSURFACE|SDL_NOFRAME|SDL_ANYFORMAT);
    	if (!screen) die("fail to create framebuffer");
    	debug_surface(screen);
	
    	/* Map the color blue to this display (R=0x00, G=0x00, B=0xFF)
       	   Note:  If the display is palettized, you must set the palette first.
    	*/
    	blue = SDL_MapRGB(screen->format, 0x00, 0x00, 0xff);

    	x = screen->w / 2;
    	y = screen->h / 2;

    	/* Lock the screen for direct access to the pixels */
    	if (SDL_MUSTLOCK(screen)) {
        	if (SDL_LockSurface(screen) < 0 ) {
            		fprintf(stderr, "Can't lock screen: %s\n", SDL_GetError());
            		die(SDL_GetError());
        	}
    	}

	for (n = 0; n < 50; n++) {
    		putpixel(screen, x + n, y + n, blue);
    	}

    	if (SDL_MUSTLOCK(screen))
        	SDL_UnlockSurface(screen);
    
    	/* Update just the part of the display that we've changed */
    	SDL_UpdateRect(screen, 0, 0, SCR_WIDTH, SCR_HEIGHT);

	while(1);
	// test smalloc
	cp = smalloc(8);
	cp = s;
	puts(cp);
	
    	exit(0);
}

/*
 * Set the pixel at (x, y) to the given value
 * NOTE: The surface must be locked before calling this!
 */
void putpixel(SDL_Surface *surface, int x, int y, Uint32 pixel)
{
    	int bpp = surface->format->BytesPerPixel;
    	/* Here p is the address to the pixel we want to set */
    	Uint8 *p = (Uint8 *)surface->pixels + y * surface->pitch + x * bpp;

    	switch(bpp) {
    	case 1:
        	*p = pixel;
        	break;
    	case 2:
        	*(Uint16 *)p = pixel;
        	break;
    	case 3:
        	if(SDL_BYTEORDER == SDL_BIG_ENDIAN) {
            		p[0] = (pixel >> 16) & 0xff;
            		p[1] = (pixel >> 8) & 0xff;
            		p[2] = pixel & 0xff;
        	} else {
            		p[0] = pixel & 0xff;
            		p[1] = (pixel >> 8) & 0xff;
            		p[2] = (pixel >> 16) & 0xff;
        	}
        	break;
    	case 4:
        	*(Uint32 *)p = pixel;
        	break;
    	}
}

void die(char *err)
{
	fprintf(stderr, "%s\n", err);
	exit(1);
}

/*
 * _safe_ malloc
 */
void *smalloc(size_t size)
{
	void *ret = malloc(size);
	if (!ret)
		die("could not allocate memory");
	memset(ret, 0, size);
	return ret;
}

void debug_surface(const SDL_Surface *sf) 
{
	if (_DEBUG_ON) {
		fprintf(stderr, "%d bits-per-pixel mode\n", sf->format->BitsPerPixel);
		fprintf(stderr, "%d bytes-per-pixel mode\n", sf->format->BytesPerPixel);
	}
}
