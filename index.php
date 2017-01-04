<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/ftp_plunderer.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$dir = getenv('FTP_DIRECTORY') . $_GET["dir"];

$plund = new \ftpgallery\plunderer;
$listings = $plund->listdir( $dir );

echo "<ol>";
foreach ($listings->Directories as $directory) {
	echo "<li><a href='?dir=". $_GET["dir"] . "/" . $directory ."'>" . $directory . "</a></li>\n";
}
echo "</ol>";

foreach ($listings->Files as $file) {
	echo "<p><img src=\"".$plund->imagepick($dir . "/" . $file)."\" width=\"500px\"/></p>";
}

