<?php
function fetch_proxies()
{
    $source = file_get_contents('http://www.sslproxies.org/');
    preg_match_all('/<tbody>(.*?)<\/tbody>/is', $source, $matches);
    preg_match_all('/<tr>(.*?)<\/tr>/is', $matches[1][0], $matches);
    $return = array();
    foreach ($matches[1] as $key => $val) {
        preg_match_all('/<td>(.*?)<\/td>/is', $val, $m);
        $return[] = "{$m[1][0]}:{$m[1][1]}";
    }
    return $return;
}