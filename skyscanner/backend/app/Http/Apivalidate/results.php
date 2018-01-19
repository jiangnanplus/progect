<?php

include_once('errors.php');

class results
{

	public function __construct()
	{
	}

	public static function result($result, $code, $message)
	{
		$container = array();
		$container['result'] = $result;
		$container['code'] = $code;
		$container['msg'] = $message;
		echo json_encode($container, JSON_UNESCAPED_UNICODE);
		exit(0);
	}

	public static function result_data($result, $code, $message, $data)
	{
		$container = array();
		$container['result'] = $result;
		$container['code'] = $code;
		$container['msg'] = $message;
		$container['data'] = $data;
		echo json_encode($container, JSON_UNESCAPED_UNICODE);
		exit(0);
	}

	public static function success()
	{
		results::result('success', errors::E_SUCCESS['code'], errors::E_SUCCESS['msg']);
	}

	public static function error($error)
	{
		results::result('error', $error['code'], $error['msg']);
	}

	public static function success_data($data)
	{
		results::result_data('success', errors::E_SUCCESS['code'], errors::E_SUCCESS['msg'], $data);
	}

	public static function error_data($error, $data)
	{
		results::result_data('error', $error['code'], $error['msg'], $data);
	}

}