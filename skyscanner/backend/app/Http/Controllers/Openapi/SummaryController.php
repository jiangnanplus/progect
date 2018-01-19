<?php

namespace App\Http\Controllers\Openapi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\SkyScannerModel\WaybillStatistic;
use App\Models\SkyScannerModel\UserStatistics;
use App\Models\SkyScannerModel\CodStatistics;
use App\Models\SkyScannerModel\ShipmentStatistics;

class SummaryController extends CommonController
{

    //获取汇总数据
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
    //获取快递项目数据
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
        //获取派发数据
        $waybillsummarydata      = (new WaybillStatistic)->get_express_waybill_data_by_summary($data['summary_time'],$data['time_by'],$data['dimensions']);
        //获取派发其他数据
        $waybillsummaryotherdata = (new WaybillStatistic)->get_express_waybill_data_by_summary($data['other_summary_time'],$data['other_time_by'],$data['dimensions']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'time_by' => $data['time_by'],
            'summary_time' => $data['time_stamp'],
            'refresh_time' => $data['time_stamp'],
            'title' => "实时汇总件数",
            'sub_title' => "大成国际中心",
            'unit' => "件",
            'remark' => "备注",
            'summerys' => $waybillsummarydata?$waybillsummarydata:"",
            'other_time_by' => $data['other_time_by'],
            'other_summary_time' => $data['other_summary_time'],
            'other_summarys' => "1",
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
        //获取揽件数据
        $shipmentsummarydata        = (new ShipmentStatistics)->get_express_shipment_data_by_summary($data['summary_time'],$data['time_by'],$data['dimensions']);
        //获取揽件其他数据
        $shipmentsummaryotherdata   = (new ShipmentStatistics)->get_express_shipment_data_by_summary($data['other_summary_time'],$data['other_time_by'],$data['dimensions']);
        //获取电子面单数据
        $electronicsummarydata      = (new ShipmentStatistics)->get_express_shipment_electronic_data_by_summary($data['summary_time'],$data['time_by'],$data['dimensions']);
        //获取电子面单其他数据
        $electronicsummaryotherdata = (new ShipmentStatistics)->get_express_shipment_electronic_data_by_summary($data['other_summary_time'],$data['other_time_by'],$data['dimensions']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'time_by' => $data['time_by'],
            'summery_time' => $data['time_stamp'],
            'refresh_time' => $data['time_stamp'],
            'title' => "实时汇总件数",
            'sub_title' => "大成国际中心",
            'unit' => "件",
            'remark' => "备注",
            'summerys' => $shipmentsummarydata,
            'other_time_by' => $data['other_time_by'],
            'other_summery_time' => $data['other_summery_time'],
            'other_summerys' => "1",
        ];
        p($res);
        $results->success_data($res);
    }
    //获取洗车数据
    public function getwash($data)
    {

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
        //获取新增用户
        $usersummarydata       = (new UserStatistics)->get_user_data_by_summary($data['summary_time'],$data['time_by'],$data['dimensions']);
        //获取新增用户其他数据
        $usersummaryotherdata  =  (new UserStatistics)->get_user_data_by_summary($data['other_summary_time'],$data['other_time_by'],$data['dimensions']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'time_by' => $data['time_by'],
            'summery_time' => $data['time_stamp'],
            'refresh_time' => $data['time_stamp'],
            'title' => "实时汇总件数",
            'sub_title' => "大成国际中心",
            'unit' => "件",
            'remark' => "备注",
            'summerys' => $usersummarydata,
            'other_time_by' => $data['other_time_by'],
            'other_summery_time' => $data['other_summery_time'],
            'other_summerys' => "1",
        ];
        $results->success_data($res);
    }
    //用户新增量
    public function get_user_new($data)
    {
        $results = new \results;
        $errors  = new \errors;
        //获取新增用户
        $usersummarydata       = (new UserStatistics)->get_user_data_by_summary($data['summary_time'],$data['time_by'],$data['dimensions']);
        //获取新增用户其他数据
        $usersummaryotherdata  =  (new UserStatistics)->get_user_data_by_summary($data['other_summary_time'],$data['other_time_by'],$data['dimensions']);
        $res = [
            'project' => $data['project'],
            'category' => $data['categ_ory'],
            'dimensions' =>
                [
                    'key' => explode("/",$data['dimensions'])[0],
                    'value' => explode("/",$data['dimensions'])[1],
                ],
            'time_by' => $data['time_by'],
            'summery_time' => $data['time_stamp'],
            'refresh_time' => $data['time_stamp'],
            'title' => "实时汇总件数",
            'sub_title' => "大成国际中心",
            'unit' => "件",
            'remark' => "备注",
            'summerys' => $usersummarydata,
            'other_time_by' => $data['other_time_by'],
            'other_summery_time' => $data['other_summery_time'],
            'other_summerys' => "1",
        ];
        $results->success_data($res);
    }
    //获取工作人员数据
    public function getstaff($data)
    {

    }

}
