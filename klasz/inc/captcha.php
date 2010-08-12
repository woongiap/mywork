<?php session_start();
	define('CAPTCHA_NUM', 6);
	define('CAPTCHA_WIDTH', 100);
	define('CAPTCHA_HEIGHT', 25);
	define('CAPTCHA_FONT', 'couri.ttf');
	$pass_phrase = '';
	for ($i = 0; $i < CAPTCHA_NUM; $i++) {
		$pass_phrase .= chr(rand(97, 122));
	}
	$_SESSION[$pass_phrase] = $_GET['key'];
	$img = imagecreatetruecolor(CAPTCHA_WIDTH, CAPTCHA_HEIGHT);
	$bg_color = imagecolorallocate($img, 255, 255, 255);
	$fg_color = imagecolorallocate($img, 0, 0, 0);
	$g_color = imagecolorallocate($img, 64, 64, 64);			
	
	imagefilledrectangle($img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color);
	
	// sprinkle some random dots
	for ($i = 0; $i < 60; $i++) {
		imagesetpixel($img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $g_color);
	}
	
	// draw the pass-phrase
	imagettftext($img, 18, 0, 5, CAPTCHA_HEIGHT - 5, $fg_color, CAPTCHA_FONT, $pass_phrase);
	
	header('Content-Type: image/png');
	imagepng($img);
	
	imagedestroy($img);
 /* configure php like ngiap.com
./configure --enable-bcmath --enable-calendar --enable-discard-path --enable-exif --enable-ftp 
--enable-gd-native-ttf --enable-libxml --enable-magic-quotes --enable-mbstring --enable-pdo=shared 
--enable-sockets --enable-zip --with-apxs2=/usr/local/apache/bin/apxs --with-curl 
--with-curlwrappers --with-freetype-dir=/usr --with-gd --with-gettext --with-libxml2 
--with-mcrypt --with-mhash --with-mime-magic --with-mysql --with-mysqli --with-openssl 
--with-pdo-mysql=shared --with-ttf --with-xmlrpc --with-zlib
*/

//ls /usr/share/fonts/msttcorefonts
 ?>

 

