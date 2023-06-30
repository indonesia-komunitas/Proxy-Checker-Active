<?php
$host   = 'https://indonesia-komunitas.com';
$proxys = array_unique(explode('|', preg_replace("/\r\n/", "|", file_get_contents('proxy.txt'))));
$count  = 0;
foreach ($proxys as $proxy) {
    $percentage = round($count / count($proxys) * 100, 3);
    echo host_checker($host, $proxy) . " | $percentage%" . PHP_EOL;
    ;
    $count++;
}
function host_checker($host, $proxy)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result   = curl_exec($ch);
    if ($httpcode == 200) {
        $result = "Proxy Bisa Digunakan! ====> $proxy";
        file_put_contents('logs-live.txt', $result . PHP_EOL, FILE_APPEND | LOCK_EX);
        return $result;
    } elseif (curl_error($ch)) {
        $result = "Proxy Tidak Bisa Digunakan! > $proxy";
        return $result;
    } else {
        $result = "Checking Proxy Active! ==> $proxy";
        file_put_contents('Proxy Checker.txt', $result . PHP_EOL, FILE_APPEND | LOCK_EX);
        return $result;
    }
    curl_close($ch);
}
?>