<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStatistics extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="system_user_statistics";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    //过滤字段
    protected $guarded=[];


    /**
     * @return array
     * 用户查询summary
     * $summary_time 具体时间
     * $time_by   时间类型
     * $dimensions 站点类型
     */
    public function get_user_data_by_summary($summary_time,$time_by,$dimensions)
    {
        $year = substr($summary_time,0,strpos($summary_time, '年'));
        if(!strpos($summary_time, '周')){
            $month  = mb_substr($summary_time,strpos($summary_time, '年')+1,2,'utf-8');
            $day    = mb_substr($summary_time,strpos($summary_time, '月')-1,2,'utf-8');
            $week   ="";
        }else{
            $month  = "";
            $day    = "";
            $week   = mb_substr($summary_time,strpos($summary_time, '年')+1,2,'utf-8');
        }
        if("site" == explode("/",$dimensions)[0]){
            if("all" == explode("/",$dimensions)[1]){
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>''])->first();
            }else{
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>explode("/",$dimensions)[1]])->first();
            }
        }
        if($res){
            return  $res->toArray();
        }else{
            return  $res;
        }

    }

    /**
     * @return array
     * 用户查询rank
     * $rank_time 具体时间
     * $time_by   时间类型
     * $order_by 排序方式
     * $status user_total总量 user_new新增
     */
    public function get_user_data_by_rank($rank_time,$time_by,$order_by,$status)
    {
        $year = substr($rank_time,0,strpos($rank_time, '年'));
        if(!strpos($rank_time, '周')){
            $month  = mb_substr($rank_time,strpos($rank_time, '年')+1,2,'utf-8');
            $day    = mb_substr($rank_time,strpos($rank_time, '月')-1,2,'utf-8');
            $week   = "";
        }else{
            $month  = "";
            $day    = "";
            $week   = mb_substr($rank_time,strpos($rank_time, '年')+1,2,'utf-8');
        }
        if($status == "user_total"){
            //获取量
            $total = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>''])->first()['total_quantity'];
            //获取列表
            $list = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day])->orderBy('total_quantity',$order_by)->get();
        }else{
            $total = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>''])->first()['new_quantity'];
            $list = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day])->orderBy('new_quantity',$order_by)->get();
        }
        $res = array("total"=>$total,"list"=>$list);
        return  $res;
    }
}
