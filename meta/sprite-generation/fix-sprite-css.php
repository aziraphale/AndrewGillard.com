<?php

if (isset($argv[1]) && file_exists($argv[1]) && substr($argv[1], -4) == '.css') {
    $filename = $argv[1];
} else {
    die("The first argument must be the filename of the .css file to fix.");
}

echo "Processing: 0%";
$c = file_get_contents($filename);
$count = 0;

echo "\r\nProcessing: 20%";
$c = preg_replace('/width:16px;height:16px;/i', "", $c, -1, $count);
echo " [$count replacements]";

echo "\r\nProcessing: 40%";
$c = preg_replace('/,\r\n/im', " a:before,\r\n", $c, -1, $count);
echo " [$count replacements]";

echo "\r\nProcessing: 60%";
$c = preg_replace('/\{/i', " a:before{", $c, -1, $count);
echo " [$count replacements]";

echo "\r\nProcessing: 80%";
$c = preg_replace('/background-image:url\((.+?)\);background-repeat:no-repeat/i', "
    content: \"\";
    float: left;
    width: 16px;
    height: 16px;
    background-image: url(../img/$1);
    background-repeat: no-repeat;
", $c, -1, $count);
echo " [$count replacements]";

echo "\r\nProcessing: 100%";
file_put_contents($filename, $c);

echo "\r\nFinished!\r\n";
