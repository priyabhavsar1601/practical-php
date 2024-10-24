<?php
session_start();

// Generate a random CAPTCHA code
$captcha_code = substr(md5(rand()), 0, 6);
$_SESSION['captcha'] = $captcha_code;

// Create an image
$image = imagecreatetruecolor(100, 38);
$background_color = imagecolorallocate($image, 255, 255, 255); // White background
$text_color = imagecolorallocate($image, 0, 0, 0); // Black text
imagefilledrectangle($image, 0, 0, 100, 38, $background_color);

// Draw the CAPTCHA code on the image
imagestring($image, 5, 10, 10, $captcha_code, $text_color); // 5 is the font size

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
?>
