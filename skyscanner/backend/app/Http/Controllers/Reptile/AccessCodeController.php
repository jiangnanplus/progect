<?php

namespace App\Http\Controllers\Reptile;

use DB;
use App\Models\SkyScannerModel\AccessCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;


class AccessCodeController extends Controller
{
    public function index()
    {
        //更新访问码
        $command = '/data/bin/access_code/access_code';
        $result = shell_exec($command);
        DB::table('access_code')
        ->where('id', 1)
        ->update(['access_code' => $result,'generate_date' => date("Y年m月d日",time()),'update_time' => time()]);
        DB::table('access_code_log')->insert(
            [
                'source' => 'system',
                'op_type' => 'generate',
                'op_log' => '生成访问码,编码:'.$result.',时间:'.date("Y年m月d日",time()),
                'op_time' => time(),
                'create_time' => time()
            ]
        );
    }

}
