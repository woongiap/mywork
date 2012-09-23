//
//  MbMasterViewController.h
//  Shu
//
//  Created by Steven Yong on 9/23/12.
//  Copyright (c) 2012 Ngiap. All rights reserved.
//

#import <UIKit/UIKit.h>

@class MbDetailViewController;

#import <CoreData/CoreData.h>

@interface MbMasterViewController : UITableViewController <NSFetchedResultsControllerDelegate>

@property (strong, nonatomic) MbDetailViewController *detailViewController;

@property (strong, nonatomic) NSFetchedResultsController *fetchedResultsController;
@property (strong, nonatomic) NSManagedObjectContext *managedObjectContext;

@end
