<?PHP
// Adapted for The Art of Web: www.the-art-of-web.com
// Please acknowledge use of this code by including this header.

//N.B.: ho aggiunto una sesta cifra modificando le linne 
//      @imagecreatetruecolor(120, 30) e 
//      for($x = 15; $x <= 95; $x += 20) {
session_start();

// initialise image with dimensions of 120 x 30 pixels
//$image = @imagecreatetruecolor(120, 30) or die("Cannot Initialize new GD image stream");
$image = @imagecreatetruecolor(140, 30) or die("Cannot Initialize new GD image stream");

// set background to white and allocate drawing colours
$background = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
imagefill($image, 0, 0, $background);
$linecolor = imagecolorallocate($image, 0xCC, 0xCC, 0xCC);
$textcolor = imagecolorallocate($image, 0x77, 0x77, 0x77);

// draw random lines on canvas
for($i=0; $i < 6; $i++) {
	imagesetthickness($image, rand(1,3));
	imageline($image, 0, rand(0,30), 120, rand(0,30), $linecolor);
}

// add random digits to canvas
$digit = '';
//for($x = 15; $x <= 95; $x += 20) {
for($x = 15; $x <= 115; $x += 20) {
	$digit .= ($num = rand(0, 9));
	imagechar($image, rand(3, 5), $x, rand(2, 14), $num, $textcolor);
}

if (isset($_GET['reqType'])) {
	
	$digitArr = isset($_SESSION['digitArr']) ? $_SESSION['digitArr'] : array();
	$digitArr[$_GET['reqType']] = $digit;
	
	// record digits in session variable
	$_SESSION['digitArr'] = $digitArr;

	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);
}
else{
	throw new Exception('Missin reqType param!!!');
}
?>