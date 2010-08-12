/* =======================================================================
    CFG.h       Configuration file handler.
                A. Reitsma, Delft, The Netherlands.
                v1.00  94-07-09  Public Domain.
 ---------------------------------------------------------------------- */
#ifndef CFG_H
#define CFG_H
struct cfg_entry
{
    char *name ;
    char *data ;
};

int CfgRead(char *filename, struct cfg_entry *CfgInfo);
/* pass in "" as prefix if no prefix */
int CfgReadFree(char *filename, struct cfg_entry *CfgInfo, char *prefix);
char * get_first_value(struct cfg_entry *CfgInfo, char *key);

#endif
/* ==== CFG.h end ===================================================== */
