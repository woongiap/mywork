#import "Number.h"

@implementation Number

- (void)printNum
{
    printf("%s is %d\n", "Result", number);
}

-(void)fun:(int)a p2:(int)n
{
	/*
	do nothing
	*/
}

@end

int main(int argc, const char *argv[])
{
    Number *myNumber = [[Number alloc] init];
    myNumber->number = 6;
    [myNumber printNum];

    return 0;
}
