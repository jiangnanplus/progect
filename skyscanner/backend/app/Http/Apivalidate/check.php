<?php

function check_method($method, $part_name)
{
    $method_parts = explode('.', $method);
    $package_name = $method_parts[0];
    $class_name = $method_parts[1];
    $method_name = $method_parts[2];
    if (check_not_equal($package_name, 'message')) results::error(errors::E_METHOD_ERROR);
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