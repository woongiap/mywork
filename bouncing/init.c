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
#define SCR_HEIGHT 800


void putpixel(SDL_Surface *surface, int x, int y, Uint32 pixel);
void die(char *err);
void *smalloc(size_t size);
void debug_surface(const SDL_Surface *sf);
const char *get_eventname(Uint8 type);

int main(int argc, char *argv[])
{
	char *cp, *s = "abcdefghijklmnopqrswxyz";
	SDL_Surface *screen, *image;
	/* Code to set a blue pixel at the center of the screen */
    	int x, y, n, quit = 0;
    	Uint32 blue;
    	SDL_Event event;
    	SDL_Rect rect, img_rect;;

	if (SDL_Init(SDL_INIT_VIDEO | SDL_INIT_AUDIO) == -1) {
        	die(SDL_GetError());
    	}    	
	DEBUG("SDL initialized");
	// hook this up at exit
    	atexit(SDL_Quit);
	/*
	 * Now, we want to setup our requested
	 * window attributes for our OpenGL window.
	 * We want *at least* 5 bits of red, green
	 * and blue. We also want at least a 16-bit
	 * depth buffer.
	 *
	 * The last thing we do is request a double
	 * buffered window. '1' turns on double
	 * buffering, '0' turns it off.
	 *
	 * Note that we do not use SDL_DOUBLEBUF in
	 * the flags to SDL_SetVideoMode. That does
	 * not affect the GL attribute state, only
	 * the standard 2D blitting setup.
	 */
	SDL_GL_SetAttribute( SDL_GL_RED_SIZE, 5 );
	SDL_GL_SetAttribute( SDL_GL_GREEN_SIZE, 5 );
	SDL_GL_SetAttribute( SDL_GL_BLUE_SIZE, 5 );
	SDL_GL_SetAttribute( SDL_GL_DEPTH_SIZE, 16 );
	SDL_GL_SetAttribute( SDL_GL_DOUBLEBUFFER, 1 );
    	//screen = SDL_SetVideoMode(SCR_WIDTH, SCR_HEIGHT, 16, SDL_HWSURFACE|SDL_RESIZABLE|SDL_ANYFORMAT);
	screen = SDL_SetVideoMode(SCR_WIDTH, SCR_HEIGHT, 16, SDL_OPENGL|SDL_FULLSCREEN);
    	if (!screen) die("fail to create framebuffer");
    	debug_surface(screen);
	
    	/* Map the color blue to this display (R=0x00, G=0x00, B=0xFF)
       	   Note: If the display is palettized, you must set the palette first.
    	*/
    	blue = SDL_MapRGB(screen->format, 0x00, 0x00, 0xff);
    	image = SDL_LoadBMP("img/robot.bmp");
    	if (image == NULL) die(SDL_GetError());

    	x = 0;
    	y = 0;

    	/* Lock the screen for direct access to the pixels */
    	if (SDL_MUSTLOCK(screen)) {
        	if (SDL_LockSurface(screen) < 0 ) {
            		fprintf(stderr, "Can't lock screen: %s\n", SDL_GetError());
            		die(SDL_GetError());
        	}
    	}
	// set bgcolor
	SDL_FillRect(screen, NULL, SDL_MapRGB(screen->format, 255, 255, 255));
	for (n = 0; n < 10; n++)
    		putpixel(screen, x + n, y + n, blue);
    	rect.x = rect.y = 30;
    	rect.w = 100;
    	rect.h = 20;
	SDL_SetClipRect(screen, &rect);    		
	SDL_FillRect(screen, &rect, blue);    		  	

    	if (SDL_MUSTLOCK(screen))
        	SDL_UnlockSurface(screen);

 	/* Blit onto the screen surface */
 	img_rect.x = img_rect.y = 60;
 	img_rect.w = image->w;
 	img_rect.h = image->h;
 	SDL_SetClipRect(screen, &img_rect);
    	if (SDL_BlitSurface(image, NULL, screen, &img_rect) < 0)
        	fprintf(stderr, "BlitSurface error: %s\n", SDL_GetError());
        	
    	/* Update just the part of the display that we've changed */
    	SDL_UpdateRect(screen, 0, 0, SCR_WIDTH, SCR_HEIGHT);

	/* Poll for events. SDL_PollEvent() returns 0 when there are no  */
  	/* more events on the event queue, our while loop will exit when */
  	/* that occurs.
  	*/
  	SDL_EnableUNICODE(1);
  	while (!quit) {
	  	while(SDL_PollEvent(&event)) {
			DEBUG(get_eventname(event.type));
	    		switch(event.type) {
	      		case SDL_KEYDOWN:
	      			if (event.key.keysym.sym == SDLK_q)
	      				quit = 1;
	      			else if (event.key.keysym.sym == SDLK_f) {
	      				SDL_WM_ToggleFullScreen(screen);
	      				debug_surface(screen);
	      			}
	      			break;
	      		case SDL_KEYUP:
				break;
	      		case SDL_QUIT:
				quit = 1;
				break;
	      		default:
				break;
	    		}
	  	}
  	}
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
    	/* Here p is the address to the pixel we want to set 
    	 *
    	 * <------ pitch ---------->
    	 * -------------------------
    	 * |0 |1 |2 |3 |4 |5 |6 |7 |
    	 * -------------------------
    	 * |8 |9 |10|11|12|13|14|15|
    	 * -------------------------
    	 */
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
		fprintf(stderr, "%d bytes of surface scanline\n", sf->pitch);
		fprintf(stderr, "%d width\n", sf->w);
		fprintf(stderr, "%d height\n", sf->h);
	}
}

struct event_map {
	Uint8 type;
	// longest SDL_MOUSEBUTTONDOWN
	char name[25];
};

const char *get_eventname(Uint8 type)
{
	static struct event_map map[] = {
		{1, "SDL_ACTIVEEVENT"},
		{2, "SDL_KEYDOWN"},
		{3, "SDL_KEYUP"},		
		{4, "SDL_MOUSEMOTION"},
		{5, "SDL_MOUSEBUTTONDOWN"},
		{6, "SDL_MOUSEBUTTONUP"},		
		{7, "SDL_JOYAXISMOTION"},
		{8, "SDL_JOYBALLMOTION"},
		{9, "SDL_JOYHATMOTION"},
		{10, "SDL_JOYBUTTONDOWN"},
		{11, "SDL_JOYBUTTONUP"},		
		{12, "SDL_QUIT "},
		{13, "SDL_SYSWMEVENT"},
		{14, "SDL_VIDEORESIZE"},
		{15, "SDL_VIDEOEXPOSE"},
		{16, "SDL_USEREVENT"},
		{-1, NULL}
	};
	int i;
	for (i = 0; map[i].type != -1; i++) {
		if (map[i].type == type)
			return map[i].name;
	}
	return NULL;
}
