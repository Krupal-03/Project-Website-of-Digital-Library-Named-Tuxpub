<?php
// Simple favicon generator - creates a basic favicon
header('Content-Type: image/x-icon');

// Create a 32x32 image
$im = imagecreate(32, 32);

// Colors
$background = imagecolorallocate($im, 52, 152, 219); // Blue background
$text_color = imagecolorallocate($im, 255, 255, 255); // White text

// Add a simple "T" letter
imagestring($im, 5, 12, 8, 'T', $text_color);

// Output as ICO
imageico($im);

// Save to file
imageico($im, 'favicon.ico');

imagedestroy($im);
echo "Favicon generated!";
?>