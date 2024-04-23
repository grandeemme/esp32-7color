
<?php

$files = glob("images/*.bmp");

$fileId = array_rand($files, 1);

$filename = $files[$fileId];

$fp = fopen($filename, 'rb');

// send the right headers
header("Content-Type: image/bmp");
header("Content-Length: " . filesize($filename));

// dump the picture and stop the script
fpassthru($fp);
exit;

