<?php

namespace App\Http\Controllers\Reptile;

use DB;
use App\Models\ExpressModel\Waybill;
use App\Models\SkyScannerModel\WaybillTask;
use App\Models\SkyScannerModel\WaybillResult;
use App\Models\SkyScannerModel\WaybillStatistic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class WaybillController extends Controller
{
    //派件查询
    public function worker_waybill($time,$last_gather_time,$interval_time)
    {
        return "开始采集派件数据";
        $WaybillTaskModel = new WaybillTask;
        //1.新建采集任务
        $waybilltask = $WaybillTaskModel->create_waybill_task($time,$last_gather_time);
        if($waybilltask){
            //2.获取上次采集到现在的数据
            $waybilldata = (new Waybill)->get_express_waybill_data_by_gather_time($last_gather_time);
            //3. 数据分析
            //3.1写入采集结果表
            $waybillresult = (new WaybillResult)->create_waybill_result($waybilldata,$waybilltask['task_id']);
            //3.2写入采集统计表
            $waybillstatistic = (new WaybillStatistic)->create_waybill_statistic($waybilldata,$waybilltask['task_id']);
            //4.更新采集参数配置表
            /*$data['gather_time'] = $waybilltask['gather_time'];
            $data['update_time'] = time();
            $arr =(new Setting)->where('worker_name','=',"waybill")->update($data);*/
            return $waybilldata;
        }

    }
}
