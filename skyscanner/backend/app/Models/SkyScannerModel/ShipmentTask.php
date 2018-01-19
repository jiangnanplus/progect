<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentTask extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="express_shipment_task";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    //开启白名单字段
    protected $fillable = ['task_id', 'gather_time', 'last_gather_time', 'create_time'];


    /**
     * task_id生成支付编号
     */
    function get_task_id_number()
    {
        $time = time();
        $data = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
        $rand_no = array_rand($data,1);
        return 'A1' . $data[$rand_no] . strtoupper(dechex($time));
    }

    /**
     * 新建采集任务
     * $last_gather_time 上次采集时间
     * $time 现在时间
     */
    public function create_shipment_task($last_gather_time)
    {
        //写入数据库
        $data = [
            'task_id'=>$this->get_task_id_number(),
            'gather_time'=>time(),
            'last_gather_time'=>$last_gather_time,
            'create_time'=>time(),
        ];
        $id = $this->create($data);
        return $id;
    }
}
