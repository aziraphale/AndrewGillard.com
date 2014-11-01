<?

//require 'flist.inc';

//header("Content-Type: text/plain");

//$kinks = FlistApicore::callMethod('character-info', array('name'=>'Badgerkitty'));
//var_dump($kinks);

$start = microtime(true);
$users = array(
    "Squigma",
    "Badgerkitty",
    "Mizzycub",
    "Nimble",
    "ForcedToDoThis",
    "Synopsis",
    "TomCattish",
    "Unspoken",
    "Colton Wulfpaws",
    "Number Six",
    "Loopy",
    "Lucabear",
    "yaffles",
);

$groupNames = array(
    'General'=>"General",
    'AnalSex'=>"Anal Sex",
    'Species'=>"Species",
    'Kinky'=>"Kinky",
    'Hardcore'=>"Hardcore",
    'BodyPreferences'=>"Body Preferences",
    'VaginalStraight'=>"Vaginal/Straight",
    'Cumrelated'=>"Cum-related",
    'InflationGrowthShrinking'=>"Inflation/Growth/Shrinking",
    'VoreUnbirth'=>"Vore/Unbirth",
    'OralSex'=>"Oral Sex",
    'BDSMampRelated'=>"BDSM & Related",
    'WatersportsScat'=>"Watersports/Scat",
    'BloodampGoreTortureDeath'=>"Blood & Gore/Torture/Death",
    'ThemesAndScenery'=>"Themes and Scenery",
    'RoleplaySpecifics'=>"Roleplay Specifics",
    'Genders'=>"Genders",
);

function html($s){return htmlspecialchars($s);}

function loadData() {
    $filename = "tabledata.cache";
    if (file_exists($filename) && filesize($filename) && filemtime($filename) > (time() - 60*60*1)) {
//        return json_decode(file_get_contents($filename));
        return unserialize(file_get_contents($filename));
    }
    
//    echo "Loading data...";
    $k = json_decode(file_get_contents('http://www.f-list.net/api/get/kinklist/'));
    $kinks = $k->kinks;
    //var_dump($kinks);

    $userKinks = array();
    foreach ($GLOBALS['users'] as $u) {
        $d = json_decode(file_get_contents('http://www.f-list.net/api/get/kinks/?name='.urlencode($u)));
        if (empty($d->kinks)) {
            echo "UNABLE TO LOAD KINK DATA FOR $u!<br>\r\n";
        } else {
            $userKinks[$u] = array('yes'=>$d->kinks->yes, 'fave'=>$d->kinks->fave, 'maybe'=>$d->kinks->maybe, 'no'=>$d->kinks->no);
        }
    }
    
    $out = array($kinks, $userKinks);
//    file_put_contents($filename, json_encode($out));
    file_put_contents($filename, serialize($out));
    return $out;
}
list($kinks, $userKinks) = loadData();

$uf = $uy = $um = $un = array_combine(array_keys($userKinks), array_pad(array(), count($userKinks), 0));

?><!DOCTYPE html>
<html>
<head>
<title>Table of Fetishes!</title>
<style>
body {
    font-size: 0.8em;
    font-family: sans-serif;
}
tr.header {
    background-color: #ccc;
    font-size: 1.5em;
}
th {
    padding: 0px 4px;
}
td.f {
    background-color: #008080;
}
td.y {
    background-color: #008000;
}
td.m {
    background-color: #808000;
}
td.n {
    background-color: #800000;
}
tbody tr:not(.header):not(.userrow):hover {
    background-color: #dfdfdf;
}
tbody tr:not(.header):not(.userrow):hover td.f {
    background-color: #006060;
}
tbody tr:not(.header):not(.userrow):hover td.y {
    background-color: #006000;
}
tbody tr:not(.header):not(.userrow):hover td.m {
    background-color: #606000;
}
tbody tr:not(.header):not(.userrow):hover td.n {
    background-color: #600000;
}
span.f {
    color: #008080;
}
span.y {
    color: #008000;
}
span.m {
    color: #808000;
}
span.n {
    color: #800000;
}
</style>
</head>
<body>

<table border="1" cellspacing="0" style="border-collapse:collapse;">
    <thead>
        <? function printHeader() { ?>
            <? global $users; ?>
            <tr class="userrow">
                <th></th>
                <? foreach ($users as $u) { ?>
                    <th><?=html($u)?></th>
                <? } ?>
                <th>TOTALS</th>
            </tr>
        <? } printHeader(); ?>
    </thead>
    <tbody>
        <? $lastGroupName = ''; ?>
        <? foreach ($kinks as $group => $gkinks) { ?>
            <? if ($group != $lastGroupName) { ?>
                <tr class="header">
                    <th colspan="<?=count($users)+2?>"><?=html($groupNames[$group])?></th>
                </tr>
                <? if ($lastGroupName) { ?>
                    <? printHeader(); ?>
                <? } ?>
                <? $lastGroupName = $group; ?>
            <? } ?>
            <? foreach ($gkinks as $kink) { ?>
                <? $f = $y = $m = $n = 0; ?>
                <tr>
                    <th><?=html($kink)?></th>
                    <? foreach ($userKinks as $u => $ukinks) { ?>
                        <? if (in_array($kink, $ukinks['fave'])) { ?>
                            <td class="f"></td>
                            <? ++$f; ++$uf[$u]; ?>
                        <? } elseif (in_array($kink, $ukinks['yes'])) { ?>
                            <td class="y"></td>
                            <? ++$y; ++$uy[$u]; ?>
                        <? } elseif (in_array($kink, $ukinks['maybe'])) { ?>
                            <td class="m"></td>
                            <? ++$m; ++$um[$u]; ?>
                        <? } elseif (in_array($kink, $ukinks['no'])) { ?>
                            <td class="n"></td>
                            <? ++$n; ++$un[$u]; ?>
                        <? } else { ?>
                            <td></td>
                        <? } ?>
                    <? } ?>
                    <th>
                        <span class="f"><?=$f?></span>;
                        <span class="y"><?=$y?></span>;
                        <span class="m"><?=$m?></span>;
                        <span class="n"><?=$n?></span>
                    </th>
                </tr>
            <? } ?>
        <? } ?>
        <tr>
            <th>TOTALS</th>
            <? foreach ($users as $u) { ?>
                <th>
                    <span class="f"><?=$uf[$u]?></span>;
                    <span class="y"><?=$uy[$u]?></span>;
                    <span class="m"><?=$um[$u]?></span>;
                    <span class="n"><?=$un[$u]?></span>
                </th>
            <? } ?>
            <th>
                <span class="f"><?=array_sum($uf)?></span>;
                <span class="y"><?=array_sum($uy)?></span>;
                <span class="m"><?=array_sum($um)?></span>;
                <span class="n"><?=array_sum($un)?></span>
            </th>
        </tr>
    </tbody>
</table>

<p>Generated in: <?=round(microtime(true)-$start, 4)?> secs</p>

</body>
</html>