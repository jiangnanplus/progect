<?php

function check_not_null($param)
{
    return ((isset($param) && !empty($param)) ? (true) : (false));
}

function check_null($param)
{
    return ((!isset($param) || empty($param)) ? (true) : (false));
}

function check_true($param)
{
    return (($param == true) ? (true) : (false));
}

function check_false($param)
{
    return (($param == false) ? (true) : (false));
}

function check_exist($key, $array)
{
    return array_key_exists($key, $array);
}

function check_not_exist($key, $array)
{
    return !array_key_exists($key, $array);
}

function check_equal($param, $match)
{
    return ((isset($param) && $param == $match) ? (true) : (false));
}

function check_not_equal($param, $match)
{
    return ((isset($param) && $param != $match) ? (true) : (false));
}

function check_equal_nocase($param, $match)
{
    return ((isset($param) && isset($match) && strtoupper($param) == strtoupper($match)) ? (true) : (false));
}

function check_not_equal_nocase($param, $match)
{
    return ((isset($param) && isset($match) && strtoupper($param) != strtoupper($match)) ? (true) : (false));
}

function check_digit($param)
{
    if(preg_match('/^\d+$/i', $param)) {
        return true;
    }
    return false;
}


//自定义打印函数
if(!function_exists('p')){
    function p($var){
        echo '<pre style="padding: 5px;
            background: #ccc;
            border: 1px solid grey;
            border-radius: 5px;">';
        print_r($var);
        echo '</pre>';
    }
}
//修改
function get_now_time_long()
{
    // return time(); //用 $_SERVER['REQUEST_TIME']效率更高
    return $_SERVER['REQUEST_TIME'];
}

function get_time_format($time)
{
    // time 是自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数。
    return date('Y-m-d H:i:s', $time);
}

function get_time_hi_format($time)
{
    // time 是自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数。
    return date('Y-m-d H:i', $time);
}

function get_now_time_format()
{
    // time() 返回自从 Unix 纪元（格林威治时间 1970 年 1 月 1 日 00:00:00）到当前时间的秒数。
    return date('Y-m-d H:i:s', time());
}

function rand_num($len=8, $format='ALL'){
    $is_abc = $is_numer = 0;
    $number = $tmp = '';
    switch($format){
        case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
        case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            break;
        case 'NUMBER':
            $chars='0123456789';
            break;
        default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            break;
    }
    mt_srand((double)microtime() * 1000000* getmypid());
    while(strlen($number)<$len){
        $tmp =substr($chars, (mt_rand()%strlen($chars)), 1);
        if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
            $is_numer = 1;
        }
        if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
            $is_abc = 1;
        }
        $number .= $tmp;
    }
    if($is_numer <> 1 || $is_abc <> 1 || empty($number) ){
        $number = rand_num($len,$format);
    }
    return $number;
}

function get_millisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

function get_token()
{
    // token = strtoupper(md5(millisecond . rand_num));
    $millisecond = get_millisecond();
    $rand_num =  rand_num(8);
    $token = strtoupper(md5($millisecond . $rand_num));
//    echo $millisecond . ',' . $rand_num . ',' . $token . ',';
    return $token;
}

/**
 * +----------------------------------------------------------
 * 获取真实IP地址
 * +----------------------------------------------------------
 */
function get_ip() {
    static $ip;
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
    }

    if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $ip)) {
        return $ip;
    } else {
        return '127.0.0.1';
    }
}

function dd($data){
    echo "<pre>";
    var_dump($data);exit;
}

function df($data){
    echo json_encode($data);exit;
}

/**
 * 对象转数组
 * @author 佚名
 * @param object $obj
 * @return array
 */
function object_to_array($obj)
{
    $arr = array();
    if (isset($obj)) {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
            $arr[$key] = $val;
        }
    }
    return $arr;
}

/**
 * 打印出HTML页面源代码（不做页面解析）
 */
function echo_html_code($html_content)
{
    echo htmlentities($html_content);
}

// 获取附近站点列表
// site_list_all 所有站点列表
// longitude   当前位置经度，数字，必须
// latitude    当前位置纬度，数字，必须
// maxDistance 最大距离（单位：米），数字，必须
function get_near_site_list($site_list_all, $longitude, $latitude, $max_distance)
{
    if (check_null($site_list_all) || check_null($longitude) || check_null($latitude)) {
        return array();
    }
    if (check_null($max_distance))
        $max_distance = 0;

    $site_list = array();
    foreach ($site_list_all as $site) {
        $lng = $site['longitude'];
        $lat = $site['latitude'];
        if (check_null($lng) || check_null($lat)) {
            continue;
        }
        $distance = cal_distance($longitude, $latitude, $lng, $lat, 1);
        if ($distance <= $max_distance) {
            array_push($site_list, $site);
        }
    }
    return $site_list;
}

/**
 * 计算两点地理坐标之间的距离
 * @param  Decimal $longitude1 起点经度
 * @param  Decimal $latitude1  起点纬度
 * @param  Decimal $longitude2 终点经度
 * @param  Decimal $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return Decimal
 */
function cal_distance($longitude1, $latitude1, $longitude2, $latitude2, $unit=2, $decimal=2){

    $EARTH_RADIUS = 6370.996; // 地球半径系数
    $PI = 3.1415926;

    $radLat1 = $latitude1 * $PI / 180.0;
    $radLat2 = $latitude2 * $PI / 180.0;

    $radLng1 = $longitude1 * $PI / 180.0;
    $radLng2 = $longitude2 * $PI /180.0;

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $distance = $distance * $EARTH_RADIUS * 1000;

    if($unit==2){
        $distance = $distance / 1000;
    }

    return round($distance, $decimal);

}

/**
 * 将字符串参数变为数组
 * @param $query
 * @return array array (size=10)
'm' => string 'content' (length=7)
'c' => string 'index' (length=5)
'a' => string 'lists' (length=5)
'catid' => string '6' (length=1)
'area' => string '0' (length=1)
'author' => string '0' (length=1)
'h' => string '0' (length=1)
'region' => string '0' (length=1)
's' => string '1' (length=1)
'page' => string '1' (length=1)
 */
function convertUrlQuery($query)
{
    $queryParts = explode('&', $query);
    $params = array();
    foreach ($queryParts as $param) {
        $item = explode('=', $param);
        $params[$item[0]] = $item[1];
    }
    return $params;
}
/**
 * 将参数变为字符串
 * @param $array_query
 * @return string string 'm=content&c=index&a=lists&catid=6&area=0&author=0&h=0®ion=0&s=1&page=1' (length=73)
 */
function getUrlQuery($array_query)
{
    $tmp = array();
    foreach($array_query as $k=>$param)
    {
        $tmp[] = $k.'='.$param;
    }
    $params = implode('&',$tmp);
    return $params;
}

function generate_nonce($length = 8)
{
    $str = substr(md5(time()), 0, $length);
    return $str;
}

function generate_password($length = 8)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';

    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
}

// 修改日期格式，从2017年3月1日变为整型时间戳
function modify_date_format_to_timestamp($date)
{
    $date = str_replace('年', '-',$date);
    $date = str_replace('月', '-',$date);
    $date = str_replace('日', '',$date);
    return strtotime($date);
}

/**
 * 数组指定位置插入元素
 */
function insertAt($items, $index, $value) {
    array_splice($items, $index, 0, $value);
    return $items;
}

/**
 * 十进制数值转十六进制数值
 */
function dec2hex($dec) {
    return dechex($dec);
}

/**
 * 十进制数组转十六进制数组
 */
function dec_arr2hex_arr($dec_arr) {
    for ($i=0; $i<count($dec_arr); $i++) {
        $hex_arr[] = strtoupper(dechex($dec_arr[$i]));
    }
    return $hex_arr;
}

/**
 * 十六进制数组转十进制数组
 */
function hex_arr2dec_arr($hex_arr) {
    for ($i=0; $i<count($hex_arr); $i++) {
        $dec_arr[] = hexdec($hex_arr[$i]);
    }
    return $dec_arr;
}

?>