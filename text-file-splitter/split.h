#ifndef __STDC__
      #error  "This compiler does not conform to the ANSI C standard."
#endif

#ifndef SPLIT_H
#define SPLIT_H

#include "cfg.h"

#define PROGRAM_VERSION "0.01"
#define PROGRAM_NAME "freax"

#define RPT_NAME_LEN 80
#define RPT_DATE_LEN 8
#define RPT_NAME_STOP "AS AT"
#define USER_MAP_CFG "trader-user.conf"

#define SKEY_IND_IDX 0
#define SKEY_LEN_IDX 1
#define SKEY_DEF_IDX 2
#define SKEY_DEF2_IDX 3
#define COL_MAX_IDX 4
#define ROW_MAX_IDX 5
#define OWNER_IDX 6
#define GROUP_IDX 7
#define OUT_DIR_IDX 8
#define TITLE_LINE_IDX 9
#define TITLE_START_COL_IDX 10

struct cfg_entry g_fix_cfg[] =
{
    { "skey.ind", NULL },    
    { "skey.len", NULL },
    { "skey.def", NULL },
    { "skey.def.2", NULL },
    { "col.max", NULL },
    { "row.max", NULL }, 
    { "owner", NULL },
    { "group", NULL },
    { "out.dir", NULL }, 
    { "title.line", NULL }, 
    { "title.start.col", NULL }, 
    { NULL, NULL }         /* array terminator. REQUIRED !!! */
};

#define USERMAP_SIZE 50
struct cfg_entry g_user_map[USERMAP_SIZE];

typedef struct cfg_entry meta_entry_t;
#define META_SIZE 10
meta_entry_t g_meta_cfg[META_SIZE*2]; /* global metadata applies to all files */

typedef struct doc_prop
{
	char *owner;
	char *users[5];
	char *groups[5];
	meta_entry_t *meta[5];
} prop_t;

void die(const char * str);
void debug(const char *str);
void split_master_file(FILE* master_file, char *file_id);
int cfgread(const char* file_id);
int prepare_dynamic_meta(char *cfg);
int profile_generate(const char* doc_name, prop_t *prop);
char * get_file_id(const char* file_name, char* file_id);
void get_report_name(const char *line);
void get_report_date(const char *line);
int load_usermap();

#endif /* SPLIT_H */

