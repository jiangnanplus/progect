<?php
namespace App\Models\ExpressModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cod extends Model
{
    //指定数据库
    protected $connection = 'mysql_express';
    //指定表名
    protected $table ="waybill_cod";
    //指定id
    protected $primaryKey = "id";

    /**
     * 获取派件数据
     * $last_gather_time 上次采集时间
     */
    public function get_express_cod_data_by_gather_time($last_gather_time)
    {
        //$arr = $this->where('create_time', '>', $last_gather_time)->get();
        $arr = $this->whereIn('status', ['uncollected','full_amount'])->orderBy('id','desc')->get();
        return $arr;
    }

}
