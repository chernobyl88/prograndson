<?php
	$font = __DIR__ . '/../font/Arial.ttf';


	$bg = imagecolorallocate($image, 255, 255, 255);
	imagefilledrectangle($image, 0, 0, 75, 50, $bg);
	
	$text_color = imagecolorallocate($image, rand(50, 255), rand(50, 255), rand(50, 255));
	imagettftext($image, 30, 12, 5, 45, $text_color, $font, substr(rand(), 0, 4));
	
	$text_color = imagecolorallocate($image, rand(50, 255), rand(50, 255), rand(50, 255));
	imagettftext($image, 30, 340, 0, 31, $text_color, $font, substr(rand(), 0, 4));
	
	$text_color = imagecolorallocate($image, 0, 0, 0);
	imagettftext($image, 29, 10, -2, 42, $text_color, $font, $code);
?>
