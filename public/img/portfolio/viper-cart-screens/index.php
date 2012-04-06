<?php

//define('GALLERY_URL', 'http://www.andrewgillard.com/gallery-demo/gallery.inc');
define('GALLERY_URL', 'http://www.lorddeath.net/gallery-demo/gallery.inc');
define('CACHED_SCRIPT_TIMEOUT', 3600);
define('USE_DATA_URIS', true);

$tempFile = sys_get_temp_dir() . '/gallery.php';

if (!file_exists($tempFile) || filemtime($tempFile) < time() - CACHED_SCRIPT_TIMEOUT) {
    if (function_exists('curl_init')) {
        $c = curl_init(GALLERY_URL);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($c, CURLOPT_TIMEOUT, 5);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

        file_put_contents($tempFile, curl_exec($c));
    }
}

if (file_exists($tempFile)) {
    include $tempFile;
} else {
    if (function_exists('curl_init')) {
        die("Unable to fetch gallery script (cURL exists, but failed to fetch the file).");
    } else {
        die("Unable to fetch gallery script (cURL is not enabled).");
    }
}

?>