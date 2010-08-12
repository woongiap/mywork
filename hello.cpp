#include <iostream>
#include <fstream>
#include <string>
using namespace std;

int main(int argc, char *args[])
{
  cout << "hello c plus plus" << endl;
  int number;
  cin >> number;
  string line;
  ifstream in("hello.cpp");
  while (getline(in, line))
    cout << line << endl;
  return 0;
}
