<?php

$fileList = new DirectoryIterator('./d/');
$filenameRegex = <<<'EOREGEX'
    /
        ^QuasselDroid
        (?:[_ ](?P<hash>[0-9a-f]{4,}))?
        (?:[_ ](?P<date>
            (?P<date_y>\d{4})\D*
            (?P<date_m>\d{2})\D*
            (?P<date_d>\d{2})
        ))?
        (?:[_ ](?P<time>
            (?P<time_h>\d{2})\D*
            (?P<time_m>\d{2})
        ))?
        \.apk$
    /ixS
EOREGEX;

$files = array();
foreach ($fileList as $file) {
    if (preg_match($filenameRegex, $file->getFilename(), $matches)) {
        $f = array('filename'=>$file->getFilename());

        if (!empty($matches['date']) && !empty($matches['time'])) {
            $f['date'] = mktime($matches['time_h'], $matches['time_m'], 0, $matches['date_m'], $matches['date_d'], $matches['date_y']);
            $f['has_time'] = true;
        } elseif (!empty($matches['date'])) {
            $f['date'] = mktime(12, 0, 0, $matches['date_m'], $matches['date_d'], $matches['date_y']);
            $f['has_time'] = false;
        }

        if (!empty($matches['hash'])) {
            $f['hash'] = $matches['hash'];
        }

        $f['md5'] = md5_file($file->getPathname());
        $f['size'] = $file->getSize();
        $f['sizeKb'] = $file->getSize() / 1024;
        $f['sizeMb'] = $file->getSize() / (1024 * 1024);

        $files[] = $f;
    }
}

usort($files, function($a,$b){
    return ($a['date'] > $b['date']) ? 0 : 1;
});

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>aziraphale/QuasselDroid Downloads</title>
<style>
body { font-family: sans-serif; }
a, a:link { text-decoration: none; color: #009BC1; font-weight: bold; }
a:hover { background-color: #FFFFC0; }
.filename { display: inline-block; width: 27.5em; font-family: "Droid Sans Mono", monospace; }
.date { display: inline-block; width: 9.0em; padding-right: 0.3em; /*margin-left: 0.5em;*/ font-family: "Droid Sans Mono", monospace; text-align: right; font-size: 0.9em; font-weight: normal; }
.time { display: inline-block; width: 4.5em; /*margin-left: 0.5em;*/ font-family: "Droid Sans Mono", monospace; font-size: 0.9em; font-weight: normal; }
.hash { display: inline-block; width: 5.5em; /*margin-left: 0.5em;*/ font-family: "Droid Sans Mono", monospace; font-size: 0.8em; font-weight: normal; }
.size { display: inline-block; width: 4.5em; /*margin-left: 0.5em;*/ font-family: "Droid Sans Mono", monospace; font-size: 0.8em; }
.md5  { display: inline-block; width: 20.5em; /*margin-left: 0.5em;*/ font-family: "Droid Sans Mono", monospace; font-size: 0.6em; }
</style>
<link href='http://fonts.googleapis.com/css?family=Droid+Sans+Mono' rel='stylesheet'>
</head>
<body>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46672755-2', 'andrewgillard.com');
  ga('send', 'pageview');
</script>

<h1>aziraphale/QuasselDroid Downloads</h1>
<h2>Quassel Client for Android Devices</h2>
<p>Downloadable .apk files of different build versions of <a href="https://github.com/aziraphale/QuasselDroid/">Aziraphale's fork of QuasselDroid</a> (forked from <a href="http://quasseldroid.iskrembilen.com/">sandsmark's original project</a>).</p>

<p>Note that you will have to enable the installation of apps from "unknown sources" within your phone's settings before you will be able to install these packages. The process for doing this is highly variable based on the manufacturer of your phone and the version of Android that it's running, so please check Google if you don't already know how to do that.</p>

<ul class="filelist">
    <?php foreach ($files as $file): ?>
        <li>
            <a href="./d/<?=$file['filename']?>">
                <span class="filename"><?=$file['filename']?></span>
                <?php if (isset($file['date'])): ?>
                    <span class="date"><?=date('jS M Y', $file['date'])?></span>
                    <?php if ($file['has_time']): ?>
                        <span class="time"><?=date('H:i', $file['date'])?></span>
                    <?php else: ?>
                        <span class="time"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </a>
            <?php if (isset($file['hash'])): ?>
                <a href="https://github.com/aziraphale/QuasselDroid/commit/<?=$file['hash']?>"><span class="hash"><?=@substr($file['hash'], 0, 7)?></span></a>
            <?php else: ?>
                <span class="hash"></span>
            <?php endif; ?>
            <span class="size" title="<?=number_format($file['size'], 0, '.', ',')?> bytes"><?=number_format(round($file['sizeMb'], 1), 1)?> MB</span>
            <span class="md5"><?=$file['md5']?></span>
        </li>
    <?php endforeach; ?>
</ul>

</body>
<!-- Generation time: <?=(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'])?> secs -->
</html>
