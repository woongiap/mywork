int lib_done(int i)
{
	return 5;
}

/*
 * WARNING! this will overwrite the libc printf
 * lesson: never use the same signature as libc functions
 */
int printf_wrong_sig(const char *format, ...)
{
	return 100;
}
