<?php

namespace App\Http\Controllers\Openapi;

use Illuminate\Http\Request;

use App\Models\SkyScannerModel\WaybillStatistic;
use App\Models\SkyScannerModel\UserStatistics;
use App\Models\SkyScannerModel\CodStatistics;
use App\Models\SkyScannerModel\ShipmentStatistics;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class RankController extends CommonController
{
    //获取排名数据
    public function get(Request $request)
    {
        $results    = new \results;
        $errors     = new \errors;
        switch ($request->input('project'))
        {
            case 'express':
                $data = $this->getexpress($request->all());
                break;

            case 'wash':
                $data = $this->getwash($request->all());
                break;

            case 'user':
                $data = $this->getuser($request->all());
                break;

            case 'staff':
                $data = $this->getstaff($request->all());
                break;

            default:
                $results->error($errors::E_METHOD_ERROR);
                break;
        }

    }
    //获取快递项目排名数据
    public function getexpress($data)
    {
        $results    = new \results;
        $errors     = new \errors;
        switch ($data['categ_ory'])
        {
            case 'express_waybill':
                $data = $this->get_express_waybill($data);
                break;

            case 'express_shipment':
                $data = $this->get_express_shipment($data);
                break;

            case 'express_cod':
                $data = $this->get_express_cod($data);
                break;

            default:
                $results->error($errors::E_METHOD_ERROR);
                break;
        }
    }
    //派件数据
    public function get_express_waybill($data)
    {
        $results = new \results;
        $errors  = new \errors;

        $dimensions = explode(",",$data['dimensions']);
        foreach($dimensions as $k=>$v){
            $a[$k] = explode("/",$v);
        }
        foreach($a as $k=>$v){
            $dimensions_new[$k]['key'] = $v[0];
            $dimensions_new[$k]['value'] = $v[1];
        }
        //获取派发数据
        $waybilldata = (new WaybillStatistic)->get_express_waybill_data_by_rank($data['rank_time'],$data['time_by'],$dimensions_new,$data['type'],$data['order_by']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'type' => $data['type'],
            'dimensions' =>$dimensions_new,
            'order_by' => $data['order_by'],
            'time_by' => $data['time_by'],
            'rank_time' => $data['rank_time'],
            'refresh_time' => $data['time_stamp'],
            'title' => "",
            'sub_title' => "",
            'total' => $waybilldata['total'],
            'unit' => "件",
            'remark' => "备注",
            'ranks' => $waybilldata['list'],
        ];
        p($res);
        //echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $results->success_data($res);
    }

    //揽件数据
    public function get_express_shipment($data)
    {
        $results = new \results;
        $errors  = new \errors;

        $dimensions = explode(",",$data['dimensions']);
        foreach($dimensions as $k=>$v){
            $a[$k] = explode("/",$v);
        }
        foreach($a as $k=>$v){
            $dimensions_new[$k]['key'] = $v[0];
            $dimensions_new[$k]['value'] = $v[1];
        }
        $shipmentdata = (new ShipmentStatistics)->get_express_shipment_data_by_rank($data['rank_time'],$data['time_by'],$dimensions_new,$data['type'],$data['order_by']);
        p($shipmentdata);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'type' => $data['type'],
            'dimensions' =>$dimensions_new,
            'order_by' => $data['order_by'],
            'time_by' => $data['time_by'],
            'rank_time' => $data['rank_time'],
            'refresh_time' => $data['time_stamp'],
            'title' => "",
            'sub_title' => "",
            'total' => $shipmentdata['total'],
            'unit' => "件",
            'remark' => "备注",
            'ranks' => $shipmentdata['list'],
        ];
        p($res);
        //echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $results->success_data($res);
    }

    //到付代收
    public function get_express_cod($data)
    {
        $results = new \results;
        $errors  = new \errors;

        $dimensions = explode(",",$data['dimensions']);
        foreach($dimensions as $k=>$v){
            $a[$k] = explode("/",$v);
        }
        foreach($a as $k=>$v){
            $dimensions_new[$k]['key'] = $v[0];
            $dimensions_new[$k]['value'] = $v[1];
        }
        //获取到付代收数据
        $coddata = (new CodStatistics)->get_express_cod_data_by_rank($data['rank_time'],$data['time_by'],$dimensions_new,$data['type'],$data['order_by']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'type' => $data['type'],
            'dimensions' =>$dimensions_new,
            'order_by' => $data['order_by'],
            'time_by' => $data['time_by'],
            'rank_time' => $data['rank_time'],
            'refresh_time' => $data['time_stamp'],
            'title' => "",
            'sub_title' => "",
            'total' => $coddata['total'],
            'unit' => "件",
            'remark' => "备注",
            'ranks' => $coddata['list'],
        ];
        p($res);
        //echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $results->success_data($res);
    }

    //获取用户数据
    public function getuser($data)
    {
        $results    = new \results;
        $errors     = new \errors;
        switch ($data['categ_ory'])
        {
            case 'user_total':
                $data = $this->get_user_total($data);
                break;

            case 'user_new':
                $data = $this->get_user_new($data);
                break;

            default:
                $results->error($errors::E_METHOD_ERROR);
                break;
        }
    }

    //用户总量
    public function get_user_total($data)
    {
        $results = new \results;
        $errors  = new \errors;
        $userdata = (new UserStatistics)->get_user_data_by_rank($data['rank_time'],$data['time_by'],$data['order_by'],$data['categ_ory']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'type' => $data['type'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'order_by' => $data['order_by'],
            'time_by' => $data['time_by'],
            'rank_time' => $data['rank_time'],
            'refresh_time' => $data['time_stamp'],
            'title' => "",
            'sub_title' => "",
            'total' => $userdata['total'],
            'unit' => "人",
            'remark' => "",
            'ranks' => $userdata['list'],
        ];
        p($res);
        $results->success_data($res);
    }

    //用户总量
    public function get_user_new($data)
    {
        $results = new \results;
        $errors  = new \errors;
        $userdata = (new UserStatistics)->get_user_data_by_rank($data['rank_time'],$data['time_by'],$data['order_by'],$data['categ_ory']);

        /*$a = explode(",",$data['dimensions']);
        foreach($a as $k=>$v){
            $a[$k] = explode("/",$v);
        }
        $b = array();
        foreach($a as $k=>$v){
            $b[$k]['key'] = $v[0];
            $b[$k]['value'] = $v[1];
        }
        echo json_encode($b);//编码为JSON字符串*/
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'type' => $data['type'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'order_by' => $data['order_by'],
            'time_by' => $data['time_by'],
            'rank_time' => $data['rank_time'],
            'refresh_time' => $data['time_stamp'],
            'title' => "",
            'sub_title' => "",
            'total' => $userdata['total'],
            'unit' => "人",
            'remark' => "",
            'ranks' => $userdata['list'],
        ];
        $results->success_data($res);
    }

}
