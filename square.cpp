#include <cstdlib>
#include <string>
#include <iostream>
#include <fstream>
#include "square.h"
using namespace std;

// cl /EHsc hello.cpp
/*
A10: Programs that use the Standard C++ library must be compiled with C++ exception handling enabled. 
C++ exception handling can be enabled by:
-Selecting the Enable exception handling option in the C++ Language Category of the C/C++ tab in the Project Settings dialog box. -or-
-Using the /GX compiler switch.

http://support.microsoft.com/kb/154419
*/

int main(int argc, char *argv[])
{
	if (argc != 2) {
       cerr << "Usage: square <number>" << endl;
       return 1;
    }
    double n = strtod(argv[1], 0);
    cout << "The square of " << argv[1] << " is " << square(n) << endl;
    cout << "Press enter to view source\n";
    int number;
    cin >> number;    
    ifstream in("square.cpp");
    string line;
    while(getline(in, line))
    	cout << line << endl;
    return 0;
}

double square(double n)
{
   return n * n;
}
