<?php

namespace App\Http\Controllers\Reptile;

use DB;
use App\Models\ExpressModel\Cod;
use App\Models\SkyScannerModel\CodTask;
use App\Models\SkyScannerModel\Setting;
use App\Models\SkyScannerModel\CodResult;
use App\Models\SkyScannerModel\CodStatistics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class CodController extends Controller
{
    //揽件查询
    public function worker_cod($time,$last_gather_time,$interval_time)
    {
        return "开始采集到付代收数据";
        $CodTaskModel = new CodTask;
        //1.新建采集任务
        $codtask = $CodTaskModel->create_cod_task($time,$last_gather_time);
        if($codtask){
            //2.获取上次采集到现在的数据
            $coddata = (new Cod)->get_express_cod_data_by_gather_time($last_gather_time);
            //3. 数据分析
            //3.1写入采集结果表
            $codresult = (new CodResult)->create_cod_result($coddata,$codtask['task_id']);
            //3.2写入采集统计表
            $codstatistic = (new CodStatistics)->create_cod_statistic($coddata,$codtask['task_id']);
            //4.更新采集参数配置表
            $data['gather_time'] = $time;
            $data['next_time'] = $time + $interval_time;
            $data['update_time'] = time();
            $arr =(new Setting)->where('worker_name','=',"cod")->update($data);
            return $coddata;
        }
    }


}
