<?

ob_start("ob_gzhandler");

require "./inc/functions.php";

if (empty($_POST['Username']) || empty($_POST['Password']))
	die('User/Password Error');

$Username = $_POST['Username'];
$Password = $_POST['Password'];
if ($Username != 'moria' || md5($Password) != 'fa5cca4a225bbc66d06943e6ece245fb')
	die('User/Password Error');

if (empty($_POST['Content']))
	die('No Text Entered');

$Category = $_POST['Category'];
$Content = isset($_POST['Content']) ? $_POST['Content'] : '';
$Title = isset($_POST['Title']) ? $_POST['Title'] : '';

$Title = myaddslashes($Title);
$Content = myaddslashes($Content);
$res = query("INSERT INTO `news` (`date`,`title`,`body`,`poster`,`show`) VALUES ('" . time() . "','$Title','$Content',1,0)");
if ($res && mysql_affected_rows() > 0)
	echo 'Entry Submission Successfull';
else
	header('HTTP/1.0 500 Internal Server Error', true, 500);

ob_end_flush();

?>