<?php
$dbfile = "visitors.db"; // path to data file
$expire = 60; // average time in seconds to consider someone online before removing from the list

if (!file_exists($dbfile)) {
    die("Error: Data file " . $dbfile . " NOT FOUND!");
}

if (!is_writable($dbfile)) {
    die("Error: Data file " . $dbfile . " is NOT writable! Please CHMOD it to 666!");
}

function CountVisitors()
{
    global $dbfile, $expire;
    $cur_ip = getIP();
    $cur_time = time();
    $cur_page = $_GET['json'];
    //$cur_page = $_SERVER['REQUEST_URI'];
    $cur_agent = $_SERVER['HTTP_USER_AGENT'];
    $dbary_new = array();

    $dbary = unserialize(file_get_contents($dbfile));

    if (is_array($dbary)) {
        while (list($user_ip, $user_time) = each($dbary[$cur_page])) {
            if (($user_ip != $cur_ip) && (($user_time + $expire) > $cur_time)) {
                $dbary_new[$cur_page][$user_ip] = $user_time;
            }
        }
    }

    $dbary_new[$cur_page][$cur_ip] = $cur_time; // add record for current user
    //$dbary_new[$cur_page]['uri'] = $_SERVER['REQUEST_URI'];
    $fp = fopen($dbfile, "w");
    //fputs($fp, serialize($dbary_new));

    $dbary[$cur_page] = $dbary_new[$cur_page];
    fputs($fp, serialize($dbary));
    fclose($fp);

    //var_dump($dbary_new);

    //$out = sprintf("%03d", count($dbary_new)); // format the result to display 3 digits with leading 0's
    //return $out;
    return count($dbary_new[$cur_page]);
}

function getIP()
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = "0";
    }
    //Combine IP and browser and page caller
    $uid = $ip . "_" . md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REQUEST_URI']);
    return $uid;
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$visitors_online = CountVisitors();
echo "{\"Visitor\":\"" . $visitors_online . "\"}";
?>
