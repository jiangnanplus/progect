<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentResult extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="express_shipment_result";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    protected $guarded=[];


    /**
     * 添加采集数据
     * $shipmentdata 数据
     * $task_id 任务id
     */
    public function create_shipment_result($shipmentdata,$task_id)
    {
        foreach($shipmentdata as $k=>&$v){
            $tmp[$v['create_time']][] = $v;
        }
        foreach($tmp as $k=>$v){
            foreach($v as $kk=>$vv){
                $site[$k][$vv['site_code']][] = $vv;
            }
        }
        foreach($site as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $time_site_vendor[$k][$kk][$vvv['vendor_code']][] = $vvv;
                }
            }
        }
        foreach($time_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $sum = 0;
                    foreach($vvv as $item){
                        $sum += $item['total_charge'];
                    }
                    $data_by_time_site_vendor['task_id'] = $task_id;
                    $data_by_time_site_vendor['site_code'] = $kk;
                    $data_by_time_site_vendor['vendor_code'] = $kkk;
                    $data_by_time_site_vendor['quantity'] = count($vvv);
                    $data_by_time_site_vendor['charge'] = $sum;
                    $data_by_time_site_vendor['time'] = $k;
                    $data_by_time_site_vendor['create_time'] = time();
                    $this->insert($data_by_time_site_vendor);
                }
            }
        }

    }

}
