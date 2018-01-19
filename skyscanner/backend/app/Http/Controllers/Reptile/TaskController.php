<?php

namespace App\Http\Controllers\Reptile;

use DB;
use App\Models\SkyScannerModel\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results    = new \results;
        $errors     = new \errors;
        //1.判断是否执行派单采集任务
        //$last_gather_time = "1509330005";
        $time = time();
        $setting = DB::table('setting')->where([["project_name","express"],["status","enable"]])->get();
        foreach ($setting as $k=>$v){
            if($time < $v->next_time){continue;}
            //执行任务
            switch ($v->worker_name)
            {
                case 'waybill':
                    $Waybill    = new WaybillController();
                    $Waybildata = $Waybill->worker_waybill($time,$v->gather_time,$v->interval_time);
                    if($Waybildata){
                        p($Waybildata);
                    }
                    break;

                case 'shipment':
                    //$data   = $this->worker_shipment($time,$v->gather_time);
                    $Shipment     = new ShipmentController();
                    $Shipmentdata = $Shipment->worker_shipment($time,$v->gather_time,$v->interval_time);
                    break;

                case 'cod':
                    $Cod     = new CodController();
                    $Coddata = $Cod->worker_cod($time,$v->gather_time,$v->interval_time);
                    if($Coddata){
                        p($Coddata);
                    }
                    break;

                default:
                    $results->error($errors::E_METHOD_ERROR);
                    break;
            }
        }
        p($setting);
    }

}
