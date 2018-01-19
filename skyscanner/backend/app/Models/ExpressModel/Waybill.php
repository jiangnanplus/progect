<?php
namespace App\Models\ExpressModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Waybill extends Model
{
    //指定数据库
    protected $connection = 'mysql_express';
    //指定表名
    protected $table ="waybill";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;

    /**
     * 获取派件数据
     * $last_gather_time 上次采集时间
     */
    public function get_express_waybill_data_by_gather_time($last_gather_time)
    {
        //$arr = $this->where('create_time', '>', $last_gather_time)->get();
        $arr = $this->orderBy('id','desc')->paginate(100)->toArray()['data'];
        return $arr;
    }

}
