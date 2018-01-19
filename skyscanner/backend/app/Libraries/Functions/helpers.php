<?php

use HyperDown\Parser;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

if (!function_exists('p')) {
	// 传递数据以易于阅读的样式格式化后输出
	function p($data, $toArray = true)
	{
		// 定义样式
		$str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
		// 如果是 boolean 或者 null 直接显示文字；否则 print
		if (is_bool($data)) {
			$show_data = $data ? 'true' : 'false';
		} elseif (is_null($data)) {
			// 如果是null 直接显示null
			$show_data = 'null';
		} elseif (is_object($data) && in_array(get_parent_class($data), ['Illuminate\Support\Collection', 'App\Models\Base']) && $toArray) {
			// 把一些集合转成数组形式来查看
			$data_array = $data->toArray();
			$show_data = '这是被转成数组的Collection:<br>' . print_r($data_array, true);
		} elseif (is_object($data) && in_array(get_class($data), ['Maatwebsite\Excel\Readers\LaravelExcelReader']) && $toArray) {
			// 把一些集合转成数组形式来查看
			$data_array = $data->toArray();
			$show_data = '这是被转成数组的Collection:<br>' . print_r($data_array, true);
		} elseif (is_object($data) && in_array(get_class($data), ['Illuminate\Database\Eloquent\Builder'])) {
			// 直接调用dd 查看
			dd($data);
		} else {
			$show_data = print_r($data, true);
		}
		$str .= $show_data;
		$str .= '</pre>';
		echo $str;
	}
}

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
function check_method($method, $part_name)
{
	$method_parts = explode('.', $method);
	$package_name = $method_parts[0];
	$class_name = $method_parts[1];
	$method_name = $method_parts[2];
	//if (check_not_equal($package_name, 'user')) results::error(errors::E_METHOD_ERROR);
	if (check_equal($part_name, 'class_name')) {
		return $class_name;
	}
	if (check_equal($part_name, 'method_name')) {
		return $method_name;
	}
	if (check_equal($part_name, 'version_name') && count($method_parts == 4)) {
		$version_name = $method_parts[3];
		return $version_name;
	}
	return $method;
}

function get_absolute_url($url)
{
	$absolute_url = configs::pic_host . $url;
	return $absolute_url;
}
