// Handling Keyboard Notifications

- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
 
    // Register for the keyboard notifications
    [[NSNotificationCenter defaultCenter] addObserver:self
                        selector:@selector(keyboardWillShow:)
                        name:UIKeyboardWillShowNotification
                        object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self
                        selector:@selector(keyboardWillHide:)
                        name:UIKeyboardWillHideNotification
                        object:nil];
}

- (void)viewWillDisappear:(BOOL)animated {
    [super viewWillDisappear:animated];
 
    // Unregister for the keyboard notifications.
    [[NSNotificationCenter defaultCenter] removeObserver:self
          name:UIKeyboardWillShowNotification
          object:nil];
    [[NSNotificationCenter defaultCenter] removeObserver:self
          name:UIKeyboardWillHideNotification
          object:nil];
}

- (void)keyboardWillShow:(NSNotification*)aNotification {
    NSDictionary* info = [aNotification userInfo];
    CGRect kbSize = [[info objectForKey:UIKeyboardFrameEndUserInfoKey]
                             CGRectValue];
    double duration = [[info objectForKey:UIKeyboardAnimationDurationUserInfoKey]
                             doubleValue];
 
    UIEdgeInsets insets = self.textView.contentInset;
    insets.bottom += kbSize.size.height;
 
    [UIView animateWithDuration:duration animations:^{
        self.textView.contentInset = insets;
    }];
}

- (void)keyboardWillHide:(NSNotification*)aNotification {
    NSDictionary* info = [aNotification userInfo];
    double duration = [[info objectForKey:UIKeyboardAnimationDurationUserInfoKey]
                                doubleValue];
 
    // Reset the text view's bottom content inset.
    UIEdgeInsets insets = self.textView.contentInset;
    insets.bottom = 0;
 
    [UIView animateWithDuration:duration animations:^{
        self.textView.contentInset = insets;
    }];
}