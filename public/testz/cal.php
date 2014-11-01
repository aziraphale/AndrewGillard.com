<?








function CalCallback($Day, $Month, $Year, $Today) {
	$Output = $Day;
	
	if ($Today)
		$Output = '<b>'.$Output.'</b>';
	
	return $Output;
}

require '../../inc/smarty/Smarty.class.php';

$smarty = new Smarty;
$smarty->template_dir = '../../inc/templates';
$smarty->compile_dir = '../../inc/templates_c';

$smarty->display('cal.tpl');

?>