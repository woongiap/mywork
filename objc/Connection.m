// Create NSURLConnection and NSURLRequest
// Create the request.
NSURLRequest *theRequest=[NSURLRequest requestWithURL:[NSURL URLWithString:@"http://www.apple.com/"]
                        cachePolicy:NSURLRequestUseProtocolCachePolicy
                    timeoutInterval:60.0];
// create the connection with the request
// and start loading the data
NSURLConnection *theConnection=[[NSURLConnection alloc] initWithRequest:theRequest delegate:self];
if (theConnection) {
    // Create the NSMutableData to hold the received data.
    // receivedData is an instance variable declared elsewhere.
    receivedData = [[NSMutableData data] retain];
} else {
    // Inform the user that the connection failed.
}