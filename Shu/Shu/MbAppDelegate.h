//
//  MbAppDelegate.h
//  Shu
//
//  Created by Steven Yong on 9/23/12.
//  Copyright (c) 2012 Ngiap. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface MbAppDelegate : UIResponder <UIApplicationDelegate>

@property (strong, nonatomic) UIWindow *window;

@property (readonly, strong, nonatomic) NSManagedObjectContext *managedObjectContext;
@property (readonly, strong, nonatomic) NSManagedObjectModel *managedObjectModel;
@property (readonly, strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

- (void)saveContext;
- (NSURL *)applicationDocumentsDirectory;

@end
