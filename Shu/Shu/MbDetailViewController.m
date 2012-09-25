//
//  MbDetailViewController.m
//  Shu
//
//  Created by Steven Yong on 9/23/12.
//  Copyright (c) 2012 Ngiap. All rights reserved.
//
#import "MbDetailViewController.h"

@interface MbDetailViewController () <UITextFieldDelegate>
@property (strong, nonatomic) UIPopoverController *masterPopoverController;
- (void)configureView;
@end

@implementation MbDetailViewController

#pragma mark - Managing the detail item
@synthesize isbn;

- (void)setDetailItem:(id)newDetailItem
{
    if (_detailItem != newDetailItem) {
        _detailItem = newDetailItem;
        
        // Update the view.
        [self configureView];
    }

    if (self.masterPopoverController != nil) {
        [self.masterPopoverController dismissPopoverAnimated:YES];
    }        
}

- (void)configureView
{
    // Update the user interface for the detail item.

    if (self.detailItem) {
        self.detailDescriptionLabel.text = [[self.detailItem valueForKey:@"timeStamp"] description];
    }
}

- (void)viewDidLoad
{
    [super viewDidLoad];
	// Do any additional setup after loading the view, typically from a nib.
    [self configureView];
    self->labelString = @"initial string";
    self.detailDescriptionLabel.text = self->labelString;
}

- (void)viewDidUnload
{
    [self setIsbn:nil];
    [super viewDidUnload];
    // Release any retained subviews of the main view.
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return YES;
}

#pragma mark - Split view

- (void)splitViewController:(UISplitViewController *)splitController willHideViewController:(UIViewController *)viewController withBarButtonItem:(UIBarButtonItem *)barButtonItem forPopoverController:(UIPopoverController *)popoverController
{
    barButtonItem.title = NSLocalizedString(@"Master", @"Master");
    [self.navigationItem setLeftBarButtonItem:barButtonItem animated:YES];
    self.masterPopoverController = popoverController;
}

- (void)splitViewController:(UISplitViewController *)splitController willShowViewController:(UIViewController *)viewController invalidatingBarButtonItem:(UIBarButtonItem *)barButtonItem
{
    // Called when the view is shown again in the split view, invalidating the button and popover controller.
    [self.navigationItem setLeftBarButtonItem:nil animated:YES];
    self.masterPopoverController = nil;
}

// implement this if you don't want first child of split view to be hidden in portrait
/*
- (BOOL)splitViewController:(UISplitViewController *)svc
                shouldHideViewController:(UIViewController *)vc
                inOrientation:(UIInterfaceOrientation)orientation
{
}
*/

- (IBAction)loadBook:(id)sender {
    NSString *url =
        [NSString stringWithFormat:@"http://openlibrary.org/api/books?bibkeys=ISBN:%@&jscmd=data&format=json", self.isbn.text];
    NSLog(@"url is %@]", url);
    NSURLRequest *theRequest =
        [NSURLRequest requestWithURL:[NSURL URLWithString:url] cachePolicy:NSURLRequestUseProtocolCachePolicy
                                          timeoutInterval:60.0];
    NSURLConnection *theConnection = [[NSURLConnection alloc] initWithRequest:theRequest delegate:self];
    if (!theConnection) {
        NSLog(@"Error is %@\n", @"could not load");
    }
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    if (!self->urlData)
        self->urlData = [[NSMutableData alloc] init];
    [self->urlData appendData:[data copy]];
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    NSError *error = nil;
    NSDictionary *dict = [NSJSONSerialization JSONObjectWithData:self->urlData
                options:NSJSONReadingMutableContainers error:&error];
    NSString *key = [NSString stringWithFormat:@"ISBN:%@", self.isbn.text];
    NSDictionary *d = [dict valueForKey:key];
    NSArray *allkeys = [d allKeys];
    for (NSString *key in allkeys) {
        NSLog(@"key %@ [%@]---", key, [d valueForKey:key]);
    }
    NSLog(@"COUNT OF %d \n", [d count]);
    self.detailDescriptionLabel.text = [d valueForKey:@"title"];
    self->urlData = nil;
}

- (BOOL)textFieldShouldReturn:(UITextField *)f
{
    if (f == self.isbn) {
        [f resignFirstResponder];
    }
    return YES;
}
@end
