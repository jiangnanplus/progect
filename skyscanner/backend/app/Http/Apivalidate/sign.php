<?php

function check_sign($params)
{
    $items = $params;
    $model = new Model();
    if (RUN_ENV == 'dev') {
        if (check_exist('show_sign', $items)) {
            $show_sign = $items['show_sign'];
            unset($items['show_sign']);
        } else {
            $show_sign = 'false';
        }
    } else {
        $show_sign = 'false';
    }
    //根据app_key获取对应的秘钥值
    $app_secret =$model->get_sign_by_app_id($items['app_id']);
    //p($app_secret);

    // 获取sign
    $sign = $items['sign'];

    // 删除sign，sign不参与签名计算
    unset($items['sign']);

    // 数组排序
    ksort($items);

    // 计算签名
    $origin_str = '';
    foreach ($items as $key => $value)
    {
        // 拼字符串
        $origin_str .= $key . '=' . $value . '&';
    }
    // 拼接秘钥值
    $origin_str .= $app_secret;
    // 计算MD5
    $sign_str = strtoupper(md5($origin_str));
    p($sign_str);
    // 判断$sign_str是否跟传入的$sign相同
    if (check_not_equal($sign, $sign_str)) {
        MyLog::info('MXP_API_SIGN_ERROR ===> ' . $sign_str . ' ===> ', $params);
        if (check_equal($show_sign, 'true')) {
            echo $sign_str;
        }
        results::error(errors::E_SIGN_ERROR);
        exit(0);
    }
    MyLog::info('MXP_API_SIGN_SUCCESS ===> ' . $sign_str . ' ===> ', $params);
    return true;
}