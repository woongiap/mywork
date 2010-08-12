/* =======================================================================
    CFG.c       Generic configuration file handler.

                A. Reitsma, Delft, The Netherlands.
                v1.00  94-07-09  Public Domain.
 ---------------------------------------------------------------------- */

#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include "cfg.h"

#define LINE_LEN_MAX    80                  /* actual max line length  */
#define BUFFERSIZE      LINE_LEN_MAX+2      /* ... including \n and \0 */

enum RetVal
{
    NO_PROBLEMS, /* 0 */
    ERR_FOPEN, /* 1 */
    ERR_MEM, /* 2 */
};

int CfgRead(char *filename, struct cfg_entry *CfgInfo)
{
	char Buffer[BUFFERSIZE], *WorkPtr, *CfgName, *CfgData;    
	struct cfg_entry * Cfg;
	FILE *CfgFile;

	CfgFile = fopen(filename, "r");
	if(NULL == CfgFile)
		return ERR_FOPEN ;

	while(NULL != fgets(Buffer, BUFFERSIZE, CfgFile)) {
	        /* clip off optional comment tail indicated by a semi-colon
	        */
	        if(NULL != (WorkPtr = strchr(Buffer, '#')))
			*WorkPtr = '\0';
		else
			WorkPtr = Buffer + strlen(Buffer);

		/* clip off trailing and leading white space
        	*/
		WorkPtr--;
        	*WorkPtr = '\0'; /* steven:remove last \n */
        	/* steven: do not remove trailing white space
        	while( isspace( *WorkPtr ) && WorkPtr >= Buffer && *WorkPtr != 20)
            		*WorkPtr-- = '\0';
        	*/
        	WorkPtr = Buffer;
        	while(isspace(*WorkPtr))
            		WorkPtr++;
        	if(0 == strlen( WorkPtr))
            		continue;

        	CfgName = strtok(WorkPtr, "=");
        	if(NULL != CfgName) {
            		/* Condition the name (lower case required),
               		and strip leading white and a 'late' = from data part.
            		*/            
            		CfgData = strtok(NULL, "=");

            		/* look for matching 'name' */
            		Cfg = CfgInfo ;
            		while(NULL != Cfg->name && 0 != strcmp( Cfg->name, CfgName))
                		Cfg++;

            		/* duplicate the data if the name is found. */
            		if(NULL != Cfg->name) {
                		Cfg->data = strdup(CfgData); /* strdup is not ANSI    */
                                           /* memory leaks if Cfg->data */
                                           /* is malloc'ed already      */
                		if(NULL == Cfg->data) {
                    			fclose(CfgFile);
                    			return ERR_MEM;
                		}
            		}   	/* undetected error on failure should not be a problem  */
                		/* as configuration reading should be done early.       */
        	}     		/* but test and handle it anyway ...                    */
    	}
    	fclose(CfgFile);
    	return NO_PROBLEMS ;
}

/*
 * pass in "" for *prefix if intend to real all config entries
 */
int CfgReadFree(char *filename, struct cfg_entry *CfgInfo, char *prefix)
{
    	char Buffer[BUFFERSIZE], *WorkPtr, *CfgName, *CfgData;
    	struct cfg_entry *Cfg ;
    	FILE *CfgFile;

    	CfgFile = fopen(filename, "r");
    	if(NULL == CfgFile)
        	return ERR_FOPEN ;
    	Cfg = CfgInfo;
    	while(NULL != fgets(Buffer, BUFFERSIZE, CfgFile)) {
        	/* clip off optional comment tail indicated by a semi-colon
        	*/
        	if(NULL != (WorkPtr = strchr(Buffer, '#')))
            		*WorkPtr = '\0';
        	else
            		WorkPtr = Buffer + strlen(Buffer);

        	/* clip off trailing and leading white space
        	*/
        	WorkPtr--;
        	*WorkPtr = '\0'; /* steven:remove last \n */
        	/* steven: do not remove trailing white space
        	while( isspace( *WorkPtr ) && WorkPtr >= Buffer && *WorkPtr != 20)
            		*WorkPtr-- = '\0';
        	*/
        	WorkPtr = Buffer;
        	while(isspace(*WorkPtr))
            		WorkPtr++;
        	if(0 == strlen( WorkPtr))
            		continue;

        	CfgName = strtok(WorkPtr, "=");
        	//printf("prefix=%s,cfgname=%s,cpm=%d\n", prefix, CfgName, strncmp(CfgName, prefix, strlen(prefix)));
        	if(NULL != CfgName && !strncmp(CfgName, prefix, strlen(prefix))) {
            		CfgData = strtok(NULL, "=");
            		
            		Cfg->name = strdup(CfgName); 
            		Cfg->data = strdup(CfgData);            		
            		if(NULL == Cfg->data) {
	            		fclose(CfgFile);
                    		return ERR_MEM;
            		}
            		Cfg++;
        	}        	
    	}
    	fclose(CfgFile);
    	return NO_PROBLEMS ;
}

char * get_first_value(struct cfg_entry *CfgInfo, char *key)
{
	while (CfgInfo->name != NULL) {
		if (!strcmp(key, CfgInfo->name))
			return CfgInfo->data;
		CfgInfo++;
	}
	return NULL;
}

/* ==== CFG.c end ===================================================== */
