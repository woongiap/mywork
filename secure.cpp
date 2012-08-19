#include <iostream>
#include <climits>

#define NO_VAL 10
using namespace std;

struct data {
	int vals[NO_VAL];
	int expect_result;
};

int avg(int, int*);

int main(int argc, char** args)
{
	int i;
	data dataset[] = {
			{{1,  2,  3, 4, 5, 6, 7, 8, 9, 10}, 5},
			{{10, -2, 0, -4, 30, 50, 20, 80, 90, 100}, 38},
			{{1, 1, 1, 1, 1, 1, 1, 1, 1, 1}, 1},
			{{2, 1, 1, 1, 1, 1, 1, 1, 1, 10}, 2},
			};
	int j = sizeof(dataset)/sizeof(data);
	for (i = 0; i < j; i++) {
		if (dataset[i].expect_result == avg(NO_VAL, dataset[i].vals)) {
			cout << "PASS" << endl;
		} else {
			cout << "FAIL" << endl;
		}
	}
	int *a = NULL;
	cout << "-ve case" << ((-1 == avg(2, a))?"pass":"fail");
	//cin.get();
	return 0;
}

int avg(int n, int* no)
{
	int sum, temp_n, temp;
	if (n < 1 || no == NULL)
	    return -1;
    	temp_n = n;
	for (sum = 0; temp_n >= 1; temp_n--) {
		if ((temp = *(no++)) >= 0)
			sum += temp;
	}
	return sum/n;
}
