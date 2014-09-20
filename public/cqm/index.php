<?php
$monitors = [
    'maxx-zen'      => ['tbb', '8d58d4fd6149262621f6a6a7dc469259', "MAXX - Zen (Primary; VDSL2/FTTC; 80/20 Mbps; WAN2)"],
    'maxx-plusnet'  => ['tbb', '6952838ac6997ef14ac0b97714ffee26', "MAXX - PlusNet (Backup; ADSL2+; 16/1.1 Mbps; WAN1)"],
    'maxx-voip'     => ['tbb', 'dd99b65bef1d6489592d16da038f9567', "MAXX - VoIP (iHub-supplied; ADSL; VoIP use only)"],
#   'aa-bsk'        => ['a&a', 'C49101848035FB02545DCC8C7F948449896A9D87/BBEU02778114', "Home - A&A Basingstoke (4 Mbps ADSL2+; RG24)"],
    'higgins'       => ['tbb', 'f5a55677d0f1f6180e0ea03c5a66c666', "Higgins - OVH (100 Mbps OVH datacentre link)"],
#   'cf3-virgin'    => ['tbb', '68d849b627828ce9ddf426e0af6ed9e2', "Mum - Virgin (~100 Mbps Cable; CF3 postcode)"],
];
?><!DOCTYPE html>
<html>
<head lang="en">
<meta charset="utf-8">
<title>thinkbroadband.com's Constant Quality Monitoring</title>
<style>
body { font-family: sans-serif; }
img.graph { border: 0; }
img.tbb { width: 800px; height: 350px; border: 0; }
img.aa { height: 116px; }
.last-update { float: right; font-size: 10px; margin-left: 20px; }
h1 { font-size: 16px; margin: 5px 0 0 0; }
</style>
</head>
<body>

<aside class="last-update"><p>Last updated at <?=date('H:i:s; Y-m-d')?>.</p></aside>
<p>These graphs make use of <a href="http://www.thinkbroadband.com/">ThinkBroadband</a>'s <a href="http://www.firebrick.co.uk/">Firebrick</a>-supplied <a href="http://www.thinkbroadband.com/ping/">Quality Monitoring service</a>. See <a href="http://www.thinkbroadband.com/ping/">the ThinkBroadband page for more details</a> and <a href="http://www.thinkbroadband.com/faq/sections/bqm.html">their FAQ for a guide to reading these graphs</a>.</p>

<?php foreach ($monitors as $id => $m): list($type, $hash, $desc) = $m; ?>
    <h1 id="<?=$id?>"><?=htmlentities($desc)?></h1>
    <?php if ($type == 'tbb'): ?>
        <a href="http://www.thinkbroadband.com/ping/share/<?=$hash?>.html">
            <img src="http://www.thinkbroadband.com/ping/share-large/<?=$hash?>.png" class="tbb graph">
        </a>
    <?php elseif ($type == 'a&a'): ?>
        <img src="http://c.gormless.aa.net.uk/cqm/<?=$hash?>.png" class="aa graph">
    <?php endif; ?>
<?php endforeach; ?>

</body>
</html>
