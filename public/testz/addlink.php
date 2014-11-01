<?

if (($_GET['pw'] != "greek") && ($_POST['pw'] != "greek")) die("<script>history.go(-1);</script>");

if ($_POST['Submit'] == "Submit") {
	mysql_connect("localhost","moria_mainsite","greek");
	mysql_select_db("moria_mainsite");
	
	$res = mysql_query("INSERT INTO `links` (`address`,`date`,`title`) VALUES ('{$_POST['url']}'," . time() . ",'{$_POST['title']}')");
	if ($res) {
		echo "Successful:<br />\n";
		$lres = mysql_query("SELECT * FROM `links` ORDER BY `id` DESC LIMIT 10");
		while($lrow = mysql_fetch_assoc($lres)) {
			?>
			<b>&nbsp;&middot;</b><a href="<?= $lrow['address'] ?>" target="_blank" title="Added on: <?= date("r",$lrow['date']) ?>">&nbsp;<?= $lrow['title'] ?>&nbsp;</a><br />
			<?
		}
	} else {
		echo "Failed: <br />
		<textarea rows=10 cols=80>" . mysql_error() . "</textarea>";
	}
	return;
}
?>
<div align="center">
	<form name="form1" method="post" action="">
		<b>URL: </b><input type="text" name="url" value="<?= $_GET['url'] ?>" size="110" /><br />
		<b>Title: </b><input type="text" name="title" value="<?= $_GET['title'] ?>" size="110" /><br />
		<input type="hidden" name="pw" value="<?= $_GET['pw'] ?>" />
		<input type="submit" name="Submit" value="Submit" />
	</form>
	<a href="#" onclick="history.go(-1); return false;" style="font-size: xx-large;">Back</a>
</div>