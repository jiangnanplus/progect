<?php

namespace App\Http\Controllers\Reptile;

use DB;
use App\Models\ExpressModel\Shipment;
use App\Models\SkyScannerModel\ShipmentTask;
use App\Models\SkyScannerModel\Setting;
use App\Models\SkyScannerModel\ShipmentResult;
use App\Models\SkyScannerModel\ShipmentStatistics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class ShipmentController extends Controller
{
    //揽件查询
    public function worker_shipment($time,$last_gather_time,$interval_time)
    {
        //return "开始采集揽件数据";
        $ShipmentTaskModel = new ShipmentTask;
        //1.新建采集任务
        $shipmenttask = $ShipmentTaskModel->create_shipment_task($time,$last_gather_time);
        if($shipmenttask){
            //2.获取上次采集到现在的数据
            $shipmentdata = (new Shipment)->get_express_shipment_data_by_gather_time($last_gather_time);
            //3. 数据分析
            //3.1写入采集结果表
            //$shipmentresult = (new ShipmentResult)->create_shipment_result($shipmentdata,$shipmenttask['task_id']);
            //3.2写入采集统计表
            $shipmentstatistics = (new ShipmentStatistics)->create_shipment_statistics($shipmentdata,$shipmenttask['task_id']);
            //4.更新采集参数配置表
            $data['gather_time'] = $time;
            $data['next_time'] = $time + $interval_time;
            $data['update_time'] = time();
            $arr =(new Setting)->where('worker_name','=',"shipment")->update($data);
            return $shipmentdata;
        }
    }


}
