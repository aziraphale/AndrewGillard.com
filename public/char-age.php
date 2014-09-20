<?
if (isset($_REQUEST['age'])) {
	if (preg_match('/^(\w+) .+ (\w+) (\d+) (\d\d):(\d\d):(\d\d) (\d{4})/', $_REQUEST['age'], $m)) {
		switch ($m[2]) {
case 'Jan': $mon=1;break;
case 'Feb': $mon=2;break;
case 'Mar': $mon=3;break;
case 'Apr': $mon=4;break;
case 'May': $mon=5;break;
case 'Jun': $mon=6;break;
case 'Jul': $mon=7;break;
case 'Aug': $mon=8;break;
case 'Sep': $mon=9;break;
case 'Oct': $mon=10;break;
case 'Nov': $mon=11;break;
case 'Dec': $mon=12;break;
default:echo "<h1>Invalid month ({$m[2]})!</h1>";$mon=1;
		}
		$creation = gmmktime($m[4], $m[5], $m[6], $mon, $m[3], $m[7]);
		$now = time();
		$diff = $now-$creation;
		
		$days = round($diff / (60*60*24),2);
		$isare = ($m[1] == 'You' ? 'are' : 'is');
		echo "<h1>$days days have passed since ";
		if ($m[1] == 'You') {
			echo "your character";
		} else {
			echo $m[1];
		}
		echo " was created.</h1>";
	} else {
		echo "<h1>\"{$_REQUEST['age']}\" does not appear to be a valid character creation date line.</h1>";
	}
}
?>
<form action="" method="post">
<p>Copy the output of the <strong>first line</strong> of the "age &lt;player&gt;" (or "age" for yourself) command into this box:</p>
<p><input type="text" name="age" size="100" /><br />
e.g. Pinkfish first logged on Mon Dec 30 13:50:00 1991 [GMT].</p>
<input type="submit" value="Submit" />
</form>

<p>Note: Due to sheer laziness, this script assumes that all dates passed are in GMT. This will only make a few hours' difference either way, so shouldn't really affect things much.</p>
