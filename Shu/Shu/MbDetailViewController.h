//
//  MbDetailViewController.h
//  Shu
//
//  Created by Steven Yong on 9/23/12.
//  Copyright (c) 2012 Ngiap. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <Foundation/NSURLConnection.h>

@interface MbDetailViewController : UIViewController <UISplitViewControllerDelegate,
                                                    NSURLConnectionDelegate>
{
    NSMutableData *urlData;
    NSString *labelString;
}
@property (strong, nonatomic) id detailItem;
@property (weak, nonatomic) IBOutlet UILabel *detailDescriptionLabel;
@property (weak, nonatomic) IBOutlet UITextField *isbn;

- (IBAction)loadBook:(id)sender;

@end
