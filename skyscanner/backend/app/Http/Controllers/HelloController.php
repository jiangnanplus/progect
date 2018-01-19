<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HelloController extends Controller
{
    //
    public function index(Request $request){
        //渲染视图文件
        // 指定允许其他域名访问  
        header('Access-Control-Allow-Origin:*');  
        // 响应类型  
        header('Access-Control-Allow-Methods:GET');  
        // 响应头设置  
        header('Access-Control-Allow-Headers:x-requested-with,content-type');  
//        return view('hello');
//        $t = Input::get('t');
        $uri = $request->path();
        $t = $request->input('t');
        $s = $request->input('s');
        return '{
    "sites": [
        {
            "Name": "Google",
            "Url": "www.google.com",
            "Country": "USA"
        },
        {
            "Name": "Facebook",
            "Url": "www.facebook.com",
            "Country": "USA"
        },
        {
            "Name": "百米站bmz' . ',uri=' . $uri . ',t=' . $t . ',s=' . $s . '",
            "Url": "www.100mzhan.com",
            "Country": "CHN"
        }
    ]
}
';
    }
}
