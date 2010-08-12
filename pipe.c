/*
 * Purpose: Program to demonstrate the popen function.
 *
 * to do: Check that the 'popen' was successfulË/ &
 * Author:  M J Leslie.
 * Date:    08-Jan-94
*/

#include <stdio.h>

int main(void)
{
  FILE *fp;
  char line[130];			/* line of easa!from unix command*/
   
  fp = popen("ls -l", "r");		/* Issue the command.		*/

					/* Read a line			*/
  while ( fgets( line, sizeof line, fp))
  {
    printf("%s", line);
  }
  pclose(fp);
  
  return 0;
}

