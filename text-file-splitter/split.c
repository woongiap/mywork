#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include "sstring.h"
#include "cfg.h"
#include "split.h"

#define DOT_C '.'
#define DOT_S "."

unsigned int g_fileid_len, g_title_line;
char g_out_dir[255], *g_owner, *g_group, g_title[RPT_NAME_LEN+1], g_date_str[RPT_DATE_LEN+1];

int main(int argc, char** argv)
{
	if (argc < 2) {		
		fprintf(stderr, "usage: %s <file name>\n", PROGRAM_NAME);
		die(NULL);
	}

	FILE* master_file = fopen(argv[1], "r");
	if (master_file == NULL) die("failed to open file");
	
	load_usermap();
	
	char *real_name;
#ifdef __WINNT__
	real_name = strrchr(argv[1], '\\');
#else
	real_name = strrchr(argv[1], '/');
#endif
	if (real_name)
		real_name++;
	else
		real_name = argv[1];

	g_fileid_len = strcspn(real_name, DOT_S);
	
	char file_id[g_fileid_len+1];
	memset(file_id, 0, g_fileid_len+1);
	get_file_id(real_name, file_id);

	if (cfgread(file_id) != 0) die("read config failed");
	strcpy(g_out_dir, g_fix_cfg[OUT_DIR_IDX].data);	
	change_path(g_out_dir);
	g_owner = g_fix_cfg[OWNER_IDX].data;
	g_group = g_fix_cfg[GROUP_IDX].data;
	g_title_line = atoi(g_fix_cfg[TITLE_LINE_IDX].data);
	split_master_file(master_file, file_id);
	
	printf("report name=[%s]\n", g_title);
	
	fclose(master_file); /* ignore error */	
	
	/* move master file to out_dir for profile as well */
	char *dest = malloc(strlen(g_out_dir) + strlen(real_name)+1);
	strcpy(dest, g_out_dir);
	strcat(dest, real_name);
	rename(argv[1], dest);	
	
	prop_t prop;
	prop.meta[0] = malloc(sizeof(meta_entry_t));
	char *ldefkey = g_fix_cfg[SKEY_DEF_IDX].data;
	prop.owner = g_owner;
	prop.users[0] = NULL;
        prop.groups[0] = g_group;
	prop.meta[0]->name = ldefkey;
	prop.meta[0]->data = "master";
	prop.meta[1] = NULL;
	
	profile_generate(dest, &prop);
	
	free(dest);
	free(prop.meta[0]);
						
	exit(EXIT_SUCCESS);
}

void split_master_file(FILE* master_file, char *file_id)
{
	const int COL_MAX = atoi(g_fix_cfg[COL_MAX_IDX].data);
	const int KEY_VALUE_LEN = atoi(g_fix_cfg[SKEY_LEN_IDX].data);
	const char *INDEX_KEY = g_fix_cfg[SKEY_IND_IDX].data;
	
	char *ldefkey = g_fix_cfg[SKEY_DEF_IDX].data;
	char *ldefkey2 = g_fix_cfg[SKEY_DEF2_IDX].data;
	char line[COL_MAX];
	char key_value[KEY_VALUE_LEN  + 1]; /* add 1 for nullchar */
	char current_key_value[KEY_VALUE_LEN + 1]; /* add 1 for nullchar */

	char full_path[255], hdr_full_path[255];;
	strcpy(hdr_full_path, g_out_dir);
	strcat(hdr_full_path, "header");
	FILE *child_file = fopen(hdr_full_path, "w");
	prop_t prop;
	
	unsigned int rpt_ln = 0, title_set = 0;
	int has_rpt = 0; /* is new report? */
	const int title_row = 3; /* do not hardcode this row of report title */
	char header[3][255]; /* 3-line header */
	const char *end_rpt = "END OF REPORT";
	int end_rpt_set = 0;
	while (fgets(line, COL_MAX, master_file ) != NULL) {
		rpt_ln++;
		if (strstr(line, end_rpt) != NULL)
		{
			end_rpt_set = 1;
		}
		if (strstr(line, INDEX_KEY) != NULL) {
		        /* if not previous key, open new file */
		        if (strcmp(key_value, get_key_value(line, 
		        				current_key_value, 
		        				strlen(INDEX_KEY), 
		        				KEY_VALUE_LEN))) {
		        	end_rpt_set = 0;
		                if (child_file != NULL)
		                        fclose(child_file);
		                strcpy(full_path, g_out_dir);
		                strcat(full_path, g_title);
		                strcat(full_path, "-");
		                strcat(full_path, current_key_value);		                
		                printf("[%s]\n", full_path);
		                child_file = fopen(full_path, "w");
		                /* merge header with previous report */		                
		                int q = 0;
		                if (!has_rpt)
		                {
			                // read header to buffer
			        	FILE *header_file = fopen(hdr_full_path, "r");
			        	while(fgets(header[q], COL_MAX, header_file) != NULL || q < title_row)
			        	{
				        	q++;
			        	}
			        	fclose(header_file);
		                }
			        // merge right away
			        q = 0;
			        while (q < title_row)
			        {
		                	fputs(header[q], child_file);
		                	q++;
	                	}
		                prop.owner = g_owner;
		                prop.users[0] = get_first_value(g_user_map, current_key_value);
		                prop.groups[0] = g_group;
		                prop.meta[0] = malloc(sizeof(meta_entry_t));
				prop.meta[0]->name = ldefkey;
				prop.meta[0]->data = current_key_value;
				prop.meta[1] = malloc(sizeof(meta_entry_t));
				prop.meta[1]->name = ldefkey2;				
				prop.meta[1]->data = prop.users[0];
				profile_generate(full_path, &prop);		                
		                strcpy(key_value, current_key_value);
		                free(prop.meta[0]);
				free(prop.meta[1]);
				has_rpt = 1;		                
		        }			
		}
		if (!title_set && rpt_ln == g_title_line) {
			/* here is where report title appear */
			get_report_name(line);
			get_report_date(line);
			title_set = 1;
		}
		fputs(line, child_file);
	}
	if (!end_rpt_set)
	{
		char err_full_path[255];
		strcpy(err_full_path, g_out_dir);
		strcat(err_full_path, "error-");
		strcat(err_full_path, g_title);
		FILE *err_file = fopen(err_full_path, "w");
		fputs("This report doesn't have 'END OF REPORT'\n", err_file);
		fclose(err_file);
		prop_t prop;
		prop.meta[0] = malloc(sizeof(meta_entry_t));
		char *ldefkey = g_fix_cfg[SKEY_DEF_IDX].data;
		prop.owner = g_owner;
		prop.users[0] = NULL;
        	prop.groups[0] = g_group;
		prop.meta[0]->name = ldefkey;
		prop.meta[0]->data = "master";
		prop.meta[1] = NULL;	
		profile_generate(err_full_path, &prop);
		free(prop.meta[0]);
	}
}

int cfgread(const char* file_id)
{
	const static char *CFG_SFX = ".conf";
        /* read config from <fileid>.conf */
        unsigned int fileid_len = strlen(file_id);
        char cfg_fname[fileid_len + strlen(CFG_SFX)+1]; /* +'\0' */
        memset(cfg_fname, 0, fileid_len + strlen(CFG_SFX)+1); // zero fill
        //strcat(cfg_fname, file_id); // cause serious error on linux
        strcpy(cfg_fname, file_id);
        strcat(cfg_fname, CFG_SFX);	
	int rc = CfgRead(cfg_fname, g_fix_cfg);
	if (rc) /* not 0 return */
		return rc;
	return prepare_dynamic_meta(cfg_fname);
}

int prepare_dynamic_meta(char *cfg)
{
	return CfgReadFree(cfg, g_meta_cfg, "key.");
}

int profile_generate(const char* doc_name, prop_t *prop)
{
	/* abc.txt -> abc.txt.properties */
	if (doc_name == NULL || prop == NULL)
		die("error producing properties");
	
	unsigned int filename_len = strlen(doc_name) + 12;
	char* prop_file_name = (char*)malloc(filename_len);
	memset(prop_file_name, 0, filename_len); // zero fill
	strcpy(prop_file_name, doc_name);	
	strcat(prop_file_name, ".properties");	
	debug(prop_file_name);
	FILE* prop_file = fopen(prop_file_name, "w");
	
	if (prop_file != NULL) {		
		fputs("# document properties\n", prop_file);
		fprintf(prop_file, "owner=%s\n", prop->owner);
		if (prop->users[0] != NULL)
			fprintf(prop_file, "users=%s\n", prop->users[0]);
		if (prop->groups[0] != NULL)
			fprintf(prop_file, "groups=%s\n", prop->groups[0]);
		fprintf(prop_file, "create.date=%s\n", g_date_str);
		int i;
		/*
		for (i=0; prop->meta[i]!=NULL; i++) {
			fprintf(prop_file, "%s=%s\n", prop->meta[i]->name, prop->meta[i]->data);
		}
		*/
		fprintf(prop_file, "%s=%s\n", prop->meta[0]->name, prop->meta[0]->data);
		if (prop->meta[1] != NULL)
			fprintf(prop_file, "%s=%s\n", prop->meta[1]->name, prop->meta[1]->data);		
		/*
			Sample:
			key.1.def=def_name
			key.1.val=Def Value
		*/		
		char *num_arr[10] = {"0","1","2","3","4","5","6","7","8","9"};
		static char name[10] = "key.x.def"; /* hold config name */ 
		static char *def = "def", *val = "val";
		char *key, * value;
		for (i=0; i<10; i++) {
			strncpy(name+4, num_arr[i], 1);
			strncpy(name+6, def, 4);
			key = get_first_value(g_meta_cfg, name);
			if (key != NULL) {
				strncpy(name+6, val, 4);
				value =  get_first_value(g_meta_cfg, name);
				if (value != NULL)
					fprintf(prop_file, "%s=%s\n", key, value);					
			}
		}
		
		fclose(prop_file);
	}
	free(prop_file_name);
	return 0; /* no error */
}

char* get_file_id(const char* file_name, char* file_id)
{
	char *from_dot = strchr(file_name, DOT_C);
	strncpy(file_id, file_name, from_dot - file_name);
	file_id[strlen(file_id)] = 0;
	return file_id;
}

void get_report_name(const char *line)
{
	char line_dup[strlen(line) + 1];
	strcpy(line_dup, line);
	char temp_name[RPT_NAME_LEN + 1];
	memset(temp_name, 0, RPT_NAME_LEN + 1);
	unsigned int start_pos = atoi(g_fix_cfg[TITLE_START_COL_IDX].data);
	char *start_ptr = line_dup + start_pos;
	strncpy(temp_name, start_ptr, RPT_NAME_LEN);
	char *end = strstr(temp_name, RPT_NAME_STOP);
	char report_name[end - temp_name + 1];
	memset(report_name, 0, sizeof(report_name));
	strncpy(report_name, temp_name, end-temp_name);
	trim_both(report_name, g_title);
}

void get_report_date(const char *line)
{
	char *date_str = strstr(line, RPT_NAME_STOP); /* RPT_NAME_STOP="AS AT" */
	date_str = date_str + strlen(RPT_NAME_STOP) + 1; /* add a space */
	printf("date_str=%s\n", date_str);
	strncpy(g_date_str, date_str, RPT_DATE_LEN);
}

int load_usermap()
{
	/*
	int i;
	for (i=0;g_user_map[i].name!=NULL;i++) {
		printf("name(%s),data(%s)\n", g_user_map[i].name, g_user_map[i].data);
	}
	printf("D08 is {%s}\n", get_first_value(g_user_map, "D08"));
	*/
	return CfgReadFree(USER_MAP_CFG, g_user_map, "");
}

void debug(const char* str)
{
#ifdef DEBUG
	if (str != NULL)
		fprintf(stderr,"%s\n",str);
#endif	
}

void die(const char* str)
{
	if (str != NULL)
		fprintf(stderr,"%s\n",str);
	exit(EXIT_FAILURE);
}

