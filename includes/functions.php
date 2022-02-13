<?php
$dir = __DIR__.'/../';


function MRVString($string) {
    if (preg_match("/^[ ؀-ۿa-zA-Z]*$/",$string)){
        return true;
    }else{
        return false;
    }
}
function MRVPassword($password){
    #must contain 8 characters, 1 uppercase, 1 lowercase and 1 number
    //return preg_match('/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/', $password);
    //return preg_match('/^[a-zA-Z1-9]{3,14}$/', $password);
    if (preg_match("/^[a-zA-Z0-9]*$/", $password)){
        return true;
    }else{
        return false;
    }
}

function MRVDate($date){
    #2009/12/11
    #2009-12-11
    #2009.12.11
    #09.12.11
    if (preg_match("/^[1-9][0-9]{3}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)){
        return true;
    }elseif (preg_match("/^[1-9][0-9]{3}\/([1-9]|1[0-2])\/([1-9]|[1-2][0-9]|3[0-1])$/", $date)){
        return true;
    }else{
        return false;
    }

}

function _clean($str){
    return is_array($str) ? array_map('_clean', $str) : str_replace('\\', '\\\\', strip_tags(trim(htmlspecialchars((get_magic_quotes_gpc() ? stripslashes($str) : $str), ENT_QUOTES))));
}


function addGetParams($url, $parameters) {
    $url_parts = parse_url($url);
    // If URL doesn't have a query string.
    if (isset($url_parts['query'])) { // Avoid 'Undefined index: query'
        parse_str($url_parts['query'], $params);
    } else {
        $params = array();
    }
    $params = array_merge($params, $parameters);
    $url_parts['query'] = http_build_query($params);
    return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];

}
