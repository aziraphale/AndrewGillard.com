/usr/local/php5
<ul><?

function dodir($path) {
	$dh = opendir($path);
	while($f=readdir($dh)) {
		if ($f=='.'||$f=='..') continue;
		echo "<li>$f";
		if (is_dir($path.'/'.$f)) {
			echo "<ul>\r\n";
			dodir($path."/".$f);
			echo "</ul>\r\n";
		}
		echo "</li>\r\n";
	}
}
dodir('/usr/local/php5');

?></ul>