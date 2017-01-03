<?php

echo "Started execution.\n\n";

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/ftp_plunderer.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$hello = new \ftpgallery\plunderer;
echo "<pre>";
var_dump( $hello->listdir(getenv('FTP_DIRECTORY')) );
echo "</pre>";

echo( "<img src=\"".$hello->imagepick(getenv('FTP_DIRECTORY') . '/file.png')."\" width=\"500px\"/>" );

echo "\n\nFinished execution.";