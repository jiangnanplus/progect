<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodResult extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="express_cod_result";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    protected $guarded=[];


    /**
     * 添加采集数据
     * $coddata 数据
     * $task_id 任务id
     */
    public function create_cod_result($coddata,$task_id)
    {
        foreach($coddata as $k=>&$v){
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
                        $sum += $item['charge'];
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
