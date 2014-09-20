<?php

/**
 * @todo properly handle timeout errors
 * @todo use ajax to reload individual domains in groups
 * @todo IPv6 support would be nice
 * @todo prevent abuse
 * @todo stop using +short so we can properly notice timeouts
 */

$domain = isset($_GET['check']) ? $_GET['check'] : null;
$domains = explode("|", $domain);
$domains = array_map(function($d){
    return trim(preg_replace('#(^\s*(https?://)?(www\.)?)#i', '', $d), "\r\n\t\v .");
}, $domains);

$local_dns_servers = array('81.187.154.138', '81.187.154.129', '149.255.99.16', '8.8.8.8', '4.2.2.1');
$local_dns_server_blacklist = array();
$dns_servers = array(
//    'Root DNS C (France)' => '91.209.12.254',
//    'Root DNS D (UK)' => '80.252.121.2',
    'Google Public DNS' => '8.8.8.8',
    'OpenDNS' => '208.67.222.222',
    'Level3' => '209.244.0.3',
    'Verizon'=> '4.2.2.1',
//    'BT Internet (UK)' => '194.73.73.172',
//    'Zen Internet (UK)' => '212.23.8.1',
//    'Virgin Media (UK)' => '194.168.4.100',
//    'Comcast (USA)' => '68.87.66.196',
//    'Shaw Cable (Canada)' => '64.59.144.16',
    'Dnsadvantage' => '156.154.70.1',
    'Norton' => '198.153.192.1',
//    'ScrubIt' => '67.138.54.100',
//    'Securly' => '184.169.143.224',
    'Comodo' => '8.26.56.26',
//    'OpenNIC' => '69.164.208.50',
    'SmartViper' => '208.76.50.50',
);

define('MIN_DNS_REQUEST_COUNT', 4 + (2 * count($dns_servers)));
define('MAX_DNS_REQUEST_COUNT', (count($local_dns_servers) * 2) + (2 * 2) + (count($dns_servers) * 2));
define('HUB_IP', '46.38.173.64');
define('CNAME_SUFFIX', '.maxxhosted.net');

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

class DNS_Timeout_Exception extends Exception {}
function update() {
    static $first = true;
    if ($first) {
        echo str_repeat(' ', 4096); // need to chuck out a load of data first or browsers just ignore it
        $first = false;
    }
    ob_flush();
    flush();
    echo ' ';
}
function format_duration($secs) {
    if ($secs >= 345600) {
        return round($secs / 86400, 1) . " days";
    } elseif ($secs >= 7200) {
        return round($secs / 3600, 1) . " hours";
    } elseif ($secs >= 120) {
        return round($secs / 60, 1) . " minutes";
    } else {
        return $secs . " seconds";
    }
}
function check_dns_timeout($result) {
//    if (substr($result, 0, 2) == ';;') {
    if (preg_match('/timed?\s*out/i', $result, $m)) {
//        var_dump($result);var_dump($m);die();
        throw new DNS_Timeout_Exception();
    }
}
function run_multiserver_query($cmd, $servers) {
    static $blacklist = array();
    if (!is_array($servers)) {
        $servers = array($servers);
    }
    $result = false;
    $servers_to_check = array_diff($servers, $blacklist);
    if (!$servers_to_check) {
        $servers_to_check = $servers;
    }
    
    foreach ($servers_to_check as $srv) {
        $res = trim(shell_exec(sprintf($cmd, $srv)), "\r\n\t\v .");
        try {
            check_dns_timeout($res);
            $result = $res;
            break;
        } catch (DNS_Timeout_Exception $ex) {
            $blacklist[] = $srv;
        }
    }
//    if (!$result) {
//        throw new DNS_Timeout_Exception();
//    }
    return $result;
}
function run_local_query($cmd) {
    global $local_dns_servers;
    return run_multiserver_query($cmd, $local_dns_servers);
}

function is_valid_domain($domain) {
    return (bool) preg_match('/^[a-z0-9\-.]+\.[a-z0-9]+$/i', $domain);
}
function is_maxx_site($domain) {
    $result = run_local_query("dig @%s +short $domain.maxxhosted.net A");
    return strlen($result) > 0;
}
function find_nameservers(&$domain) {
    global $local_dns_servers;
    // andrew@hat:~$ dig +noall +answer crowcon.com NS
    // blog.arieso.com.        85792 IN        CNAME   blog.arieso.com.maxxhosted.net.
    // crowcon.com.            85752 IN        NS      ns2.mistral.co.uk.
    $result = run_local_query("dig @%s +noall +answer $domain NS");
    $lines = explode("\n", $result);
    $lines = array_map(function($v){
        return strtolower(trim($v, "\r\n\t\v ."));
    }, $lines);
    natsort($lines);
    
    $out = array();
    foreach ($lines as $l) {
        if (strlen($l)) {
            if (preg_match('/^\s*(\S+)\s+\d+\s+\S+\s+(NS|CNAME)\s+(\S+)\s*$/i', $l, $m)) {
                if (strcasecmp($m[2], 'NS') == 0) {
                    $out[] = $m[3];
                } elseif (strcasecmp($m[2], 'CNAME') == 0) {
                    if (preg_match('/\.maxxhosted\.net\.?\s*$/i', $m[3])) {
                        // CNAME to a maxxhosted domain, so probably ok
                        $temp_domain = "maxxhosted.net";
                        return find_nameservers($temp_domain);
                    } else {
                        $domain = $m[3];
                        return find_nameservers($domain);
                    }
                }
            }
        }
    }
    return $out;
}
function check_a_record($domain, $server, &$result=null, &$ttl=null) {
    // andrew@hat:/var/www$ dig @ns.mistral.co.uk +noall +answer crowcon.com A
    // crowcon.com.            86400   IN      A       212.113.135.146
    $r = run_multiserver_query("dig @%s +noall +answer $domain A", $server);
//    $r = trim(`dig @$server +noall +answer $domain A`, "\r\n\t\v .");
    check_dns_timeout($r);
    if (preg_match('/^\s*[a-z0-9\-\.]+\s+(\d+)\s+IN\s+A\s+([0-9.]+)\s*$/i', $r, $m)) {
        $result = $m[2];
        $ttl = $m[1];
    } else {
        $result = false;
    }
    return ($result == HUB_IP);
}
function check_cname_record($domain, $server, &$result=null, &$ttl=null) {
    // andrew@sek:~$ dig @ns2.mistral.co.uk +noall +answer www.mediafirst.co.uk CNAME
    // www.mediafirst.co.uk.   86400   IN      CNAME   mediafirst.co.uk.maxxhosted.net.
//    $r = trim(`dig @$server +noall +answer www.$domain CNAME`, "\r\n\t\v .");
    $r = run_multiserver_query("dig @%s +noall +answer www.$domain CNAME", $server);
    check_dns_timeout($r);
    if (preg_match('/^\s*[a-z0-9\-\.]+\s+(\d+)\s+IN\s+CNAME\s+([a-z0-9\.\-]+)\s*$/i', $r, $m)) {
        $result = $m[2];
        $ttl = $m[1];
    }
    return (strcasecmp($result, $domain . CNAME_SUFFIX) == 0);
}

?><!DOCTYPE html>
<html>
<head>
<title>Maxx DNS Checker</title>
<style>
body { font-family: sans-serif; }
.yes { color: green; }
.no { color: red; }
hr { margin: 30px 20px; }
abbr { border-bottom: 1px dotted; cursor: help; }
.notice { font-size: 0.9em; color: #666; }
</style>
</head>
<body>

<h1>Maxx DNS Checker</h1>
<p class="notice">Note that this script needs to perform up to <?=MIN_DNS_REQUEST_COUNT?>-<?=MAX_DNS_REQUEST_COUNT?> DNS queries per domain, which can sometimes take a significant amount of time, especially if some servers fail to respond, so please be patient.</p>

<?php function ttl($ttl) {
    ?><abbr title="The Time To Live (or TTL) is the amount of time that DNS servers are allowed to cache the record. Therefore it is also the maximum amount of time that it should take for DNS record changes to be visible on all servers.">TTL: <?=format_duration($ttl)?></abbr><?php
} ?>

<?php if ($domains): ?>
    <?php foreach ($domains as $k => $domain): ?>
        <?php if ($k > 0): ?>
            <hr>
        <?php endif; ?>
        
        <h2>Testing <?=$domain?></h2>
        
        <?php $failed = false; ?>
        <?php $waiting = false; ?>
        <?php $timeout = false; ?>
        <?php update(); ?>
        <ul>
            <li>
                <strong>Checking status...</strong>
                <?php update(); ?>
                <?php try { ?>
                    <?php if (!is_valid_domain($domain)): ?>
                        <em class="no">Failed! This does not appear to be a valid domain name!</em>
                        <?php $failed = true; ?>
                    <?php elseif (!is_maxx_site($domain)): ?>
                        <em class="no">Failed! Is this a domain for a site hosted by Maxx Design?</em>
                        <?php $failed = true; ?>
                    <?php else: ?>
                        <em class="yes">Passed</em>
                    <?php endif; ?>
                <?php } catch (DNS_Timeout_Exception $ex) { ?>
                    <strong class="no">No reachable DNS servers!</strong>
                        <?php $timeout = $failed = true; ?>
                <?php } ?>
            </li>
            <?php if (!$failed): ?>
                <li>
                    <strong>Determining nameservers...</strong>
                    <?php update(); ?>
                    <?php try { ?>
                        <?php if ($ns = find_nameservers($domain)): ?>
                            <em class="yes"><?=join('; ', $ns)?></em>
                        <?php else: ?>
                            <em class="no">[Unknown! Is this domain actually registered?]</em>
                            <?php $failed = true; ?>
                        <?php endif; ?>
                    <?php } catch (DNS_Timeout_Exception $ex) { ?>
                        <strong class="no">No reachable DNS servers!</strong>
                        <?php $timeout = $failed = true; ?>
                    <?php } ?>
                </li>
            <?php endif; ?>
            <?php if (!$failed): ?>
                <li>
                    <strong>Checking A record of <?=$domain?>...</strong>
                    <?php update(); ?>
                    <?php try { ?>
                        <?php if (check_a_record($domain, $ns, $res_a, $ttl_a)): ?>
                            <em class="yes">Correct: <?=$res_a?></em>
                        <?php else: ?>
                            <em class="no">
                                Incorrect:
                                <?php if (strlen($res_a)): ?>
                                    <?=$res_a?> [<?=ttl($ttl_a)?>]
                                <?php else: ?>
                                    [No A record set]
                                <?php endif; ?>
                                (Should be: <?=HUB_IP?>)
                            </em>
                            <?php $failed = true; ?>
                        <?php endif; ?>
                    <?php } catch (DNS_Timeout_Exception $ex) { ?>
                        <strong class="no">Nameserver(s) did not respond!</strong>
                        <?php $timeout = $failed = true; ?>
                    <?php } ?>
                </li>
                <li>
                    <strong>Checking CNAME record of www.<?=$domain?>...</strong>
                    <?php update(); ?>
                    <?php try { ?>
                        <?php if (check_cname_record($domain, $ns, $res_cname, $ttl_cname)): ?>
                            <em class="yes">Correct: <?=$res_cname?></em>
                        <?php else: ?>
                            <em class="no">
                                Incorrect:
                                <?php if ($res_cname): ?>
                                    <?=$res_cname?> [<?=ttl($ttl_cname)?>]
                                <?php else: ?>
                                    [No CNAME record set] 
                                <?php endif; ?>
                                (Should be:<?=$domain?><?=CNAME_SUFFIX?>)
                            </em>
                            <?php $failed = true; ?>
                        <?php endif; ?>
                    <?php } catch (DNS_Timeout_Exception $ex) { ?>
                        <strong class="no">Nameserver(s) did not respond!</strong>
                        <?php $timeout = $failed = true; ?>
                    <?php } ?>
                </li>
            <?php endif; ?>
            <?php if (!$failed): ?>
                <li>
                    <strong>Checking for DNS propagation...</strong>
                    <ul>
                        <?php foreach ($dns_servers as $dns_name => $dns_ip): ?>
                            <li>
                                <strong><abbr title="<?=$dns_ip?>"><?=$dns_name?></abbr>:</strong>
                                <?php update(); ?>
                                <?php try { ?>
                                    <?php if (($_a = check_a_record($domain, $dns_ip, $dns_a_res, $dns_a_ttl)) && ($_cn = check_cname_record($domain, $dns_ip, $dns_cn_res, $dns_cn_ttl))): ?>
                                        <em class="yes">Correct!</em>
                                    <?php else: ?>
                                        <?php if (!$_a && !$_cn): ?>
                                            <em class="no">Incorrect! (A: <?=$dns_a_res?> [<?=ttl($dns_a_ttl)?>]; CNAME: <?=$dns_cn_res?> [<?=ttl($dns_cn_ttl)?>])</em>
                                        <?php elseif (!$_a): ?>
                                            <em class="no">Partly Incorrect! (A: <?=$dns_a_res?> [<?=ttl($dns_a_ttl)?>])</em>
                                        <?php elseif (!$_cn): ?>
                                            <em class="no">Partly Incorrect! (CNAME: <?=$dns_cn_res?> [<?=ttl($dns_cn_ttl)?>])</em>
                                        <?php endif; ?>
                                        <?php $waiting = true; ?>
                                    <?php endif; ?>
                                <?php } catch (DNS_Timeout_Exception $ex) { ?>
                                    <strong class="no">Server did not respond!</strong>
                                    <?php $timeout = $waiting = true; ?>
                                <?php } ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
        
        <?php if ($failed): ?>
            <?php if ($timeout): ?>
                <h3 class="no">Some servers did not respond as expected so the configuration of this domain could not be fully checked. Please try again later.</h3>
            <?php else: ?>
                <h3 class="no">This domain is not correctly configured. Please refer to the problems above and the instructions you were provided with.</h3>
            <?php endif; ?>
        <?php elseif ($waiting): ?>
            <?php if ($timeout): ?>
                <h3 class="no">Some servers did not respond as expected so the global propagation of this domain could not be fully checked. Please try again later.</h3>
            <?php else: ?>
                <h3 class="yes">This domain appears to be correctly configured, however this configuration has not yet finished propagating to all of the DNS servers around the Internet. This process takes time and cannot be accelerated - please be patient!</h3>
            <?php endif; ?>
        <?php else: ?>
            <h3 class="yes">This domain appears to be correctly configured.</h3>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <form action="" method="get">
        <fieldset>
            <legend>Test a Domain</legend>
            <p>Enter the domain name that you wish to check the set-up of.</p>
            <label for="check">Domain:</label>
            <input type="text" id="check" name="check">
            <input type="submit" value="Check It!">
        </fieldset>
    </form>
<?php endif; ?>

</body>
</html>
