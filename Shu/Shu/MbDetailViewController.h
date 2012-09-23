//
//  MbDetailViewController.h
//  Shu
//
//  Created by Steven Yong on 9/23/12.
//  Copyright (c) 2012 Ngiap. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MbDetailViewController : UIViewController <UISplitViewControllerDelegate>

@property (strong, nonatomic) id detailItem;

@property (weak, nonatomic) IBOutlet UILabel *detailDescriptionLabel;
@end
