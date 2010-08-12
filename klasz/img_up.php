<?php
// TODO: image format validation
header("Content-Type: text/html"); // the return type must be text/html
//if file has been sent successfully:
if (isset($_FILES['image']['tmp_name'])) {
	// open the file
	$img = $_FILES['image']['tmp_name'];
	$himage = fopen( $img, "r"); // read the temporary file into a buffer
	$image = fread( $himage, filesize($img) );
	fclose($himage);
	//if image can't be opened, either its not a valid format or even an image:
	if ($image === FALSE) {
		echo "{status:'Error Reading Uploaded File.'}";
		return;
	}
	// create a new random numeric name to avoid rewriting other images already on the server...
	$rand = uniqid().".";
	// define the uploading dir
	$path = "img/pi/";
	// join path and name
	$path = $path.$rand.'jpg';
	// copy the image to the server, alert on fail
	$hout = fopen($path, "w");
	fwrite($hout, $image);
	fclose($hout);
	//you'll need to modify the path here to reflect your own server.
	//$path = "img/" . $path;
	echo "{status:'UPLOADED', image_url:'$path'}";
} else {
	echo "{status:'No file was submitted'}";
}
?>