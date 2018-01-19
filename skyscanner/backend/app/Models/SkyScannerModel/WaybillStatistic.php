<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ExpressModel\Waybill;

class WaybillStatistic extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="experss_waybill_statistics";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    //开启白名
    //protected $fillable = ['time_by','dimension_site','dimension_vendor','year','month', 'week','site_code','vendor_code','quantity','day','create_time','update_time','charge'];
    //过滤名单
    protected $guarded=[];


    /**
     * 添加采集数据
     * $waybilldata 数据
     * $task_id 任务id
     */
    public function create_waybill_statistic($waybilldata,$task_id)
    {
        p(date("Y-m-d H:i:s",time()));
        foreach($waybilldata as $k=>&$v){
            $v['year'] =  date("Y",$v['create_time']);
            $v['month'] =  date("Y-m",$v['create_time']);
            $v['week'] =  date("Y-W",$v['create_time']);
            $v['day'] =  date("Y-m-d",$v['create_time']);
            $tmp[$v['year']][] = $v;
            $tmpmonth[$v['month']][] = $v;
            $tmpweek[$v['week']][] = $v;
            $tmpday[$v['day']][] = $v;
        }
        /* p($this->array_counts($waybilldata,"year"));die;
        p($this->get_adte($waybilldata,"year"));die;*/
        //统计每年的总数量
        foreach($this->array_counts($waybilldata,"year") as $k=>$v){
            $data_by_year = [
                'time_by' => "year",
                'quantity' => $v['rows'],
                'dimension_site' => "no",
                'dimension_vendor' => 'no',
                'year' => $k,
                'create_time' => time()
            ];
            $this->updateOrCreate(['year' => $k,'time_by'=>'year','dimension_site'=>'no','dimension_vendor'=>'no'],  $data_by_year);
        }
        //统计每月的总数量
        foreach($this->array_counts($waybilldata,"month") as $k=>$v){
            $data_by_month = [
                'time_by' => "month",
                'quantity' => $v['rows'],
                'dimension_site' => "no",
                'dimension_vendor' => 'no',
                'year' => explode("-",$k)[0],
                'month' => explode("-",$k)[1],
                'create_time' => time()
            ];
            $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'month','dimension_site'=>'no','dimension_vendor'=>'no','month'=>explode("-",$k)[1]],  $data_by_month);
        }
        //统计每周的总数量
        foreach($this->array_counts($waybilldata,"week") as $k=>$v){
            $data_by_week = [
                'time_by' => "week",
                'quantity' => $v['rows'],
                'dimension_site' => "no",
                'dimension_vendor' => 'no',
                'year' => explode("-",$k)[0],
                'week' => explode("-",$k)[1],
                'create_time' => time()
            ];
            $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'week','dimension_site'=>'no','dimension_vendor'=>'no','week'=>explode("-", $k)[1]],  $data_by_week);
        }
        //统计每日的总数量
        foreach($this->array_counts($waybilldata,"day") as $k=>$v){
            $data_by_day = [
                'time_by' => "day",
                'quantity' => $v['rows'],
                'dimension_site' => "no",
                'dimension_vendor' => 'no',
                'year' => explode("-",$k)[0],
                'month' => explode("-",$k)[1],
                'day' => explode("-",$k)[2],
                'create_time' => time()
            ];
            $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'day','dimension_site'=>'no','dimension_vendor'=>'no','month'=>explode("-", $k)[1],'day'=>explode("-", $k)[2]],  $data_by_day);
        }
        /**
         * 同时根据两个条件判断
         */
        foreach($tmp as $k=>$v){
            foreach($v as $kk=>$vv){
                $site[$k][$vv['site_code']][] = $vv;
                $vendor[$k][$vv['vendor_code']][] = $vv;
            }
        }
        foreach($tmpmonth as $k=>$v){
            foreach($v as $kk=>$vv){
                $sitemonth[$k][$vv['site_code']][] = $vv;
                $vendormonth[$k][$vv['vendor_code']][] = $vv;
            }
        }
        foreach($tmpweek as $k=>$v){
            foreach($v as $kk=>$vv){
                $siteweek[$k][$vv['site_code']][] = $vv;
                $vendorweek[$k][$vv['vendor_code']][] = $vv;
            }
        }
        foreach($tmpday as $k=>$v){
            foreach($v as $kk=>$vv){
                $siteday[$k][$vv['site_code']][] = $vv;
                $vendorday[$k][$vv['vendor_code']][] = $vv;
            }
        }
        foreach($site as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $year_site_vendor[$k][$kk][$vvv['vendor_code']][] = $vvv;
                }
            }
        }
        foreach($sitemonth as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $month_site_vendor[$k][$kk][$vvv['vendor_code']][] = $vvv;
                }
            }
        }
        foreach($siteweek as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $week_site_vendor[$k][$kk][$vvv['vendor_code']][] = $vvv;
                }
            }
        }
        foreach($siteday as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $day_site_vendor[$k][$kk][$vvv['vendor_code']][] = $vvv;
                }
            }
        }
        //统计每年的每个站点的总数量
        foreach($site as $k=>$v){
            foreach($v as $kk=>$vv){
                $data_by_year_site = [
                    'time_by' => "year",
                    'quantity' => count($vv),
                    'dimension_site' => "yes",
                    'dimension_vendor' => 'no',
                    'year' => $k,
                    'site_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => $k,'time_by'=>'year','dimension_site'=>'yes','dimension_vendor'=>'no','site_code'=>$kk],  $data_by_year_site);
            }
        }
        //统计每月的每个站点的总数量
       foreach($sitemonth as $k=>$v){
            foreach($v as $kk=>$vv){
                $data_by_month_site = [
                    'time_by' => "month",
                    'quantity' => count($vv),
                    'dimension_site' => "yes",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'site_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'month','dimension_site'=>'yes','dimension_vendor'=>'no','month'=>explode("-", $k)[1],'site_code'=>$kk],  $data_by_month_site);
            }
        }
        //统计每周每个站点的总数量
        foreach($siteweek as $k=>$v){
            foreach($v as $kk=>$vv) {
                $data_by_week_site = [
                    'time_by' => "week",
                    'quantity' => count($vv),
                    'dimension_site' => "yes",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'week' => explode("-",$k)[1],
                    'site_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'week','dimension_site'=>'yes','dimension_vendor'=>'no','week'=>explode("-", $k)[1],'site_code'=>$kk],  $data_by_week_site);

            }
        }
        //统计每日每个站点的总数量
        foreach($siteday as $k=>$v){
            foreach($v as $kk=>$vv) {
                $data_by_day_site = [
                    'time_by' => "day",
                    'quantity' => count($vv),
                    'dimension_site' => "yes",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'day' => explode("-",$k)[2],
                    'site_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'day','dimension_site'=>'yes','dimension_vendor'=>'no','month'=>explode("-", $k)[1],'day'=>explode("-", $k)[2],'site_code'=>$kk],  $data_by_day_site);
            }
        }
        //统计每年的每个快递公司的总数量
        foreach($vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                $data_by_year_vendor = [
                    'time_by' => "year",
                    'dimension_site' => "no",
                    'dimension_vendor' => 'yes',
                    'year' => $k,
                    'vendor_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => $k,'time_by'=>'year','dimension_site'=>'no','dimension_vendor'=>'yes','vendor_code'=>$kk],  $data_by_year_vendor);
            }
        }
        //统计每月的每个快递公司的总数量
        foreach($vendormonth as $k=>$v){
            foreach($v as $kk=>$vv){
                $data_by_month_vendor = [
                    'time_by' => "month",
                    'quantity' => count($vv),
                    'dimension_site' => "no",
                    'dimension_vendor' => 'yes',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'vendor_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'month','dimension_site'=>'no','dimension_vendor'=>'yes','month'=>explode("-", $k)[1],'vendor_code'=>$kk],  $data_by_month_vendor);
            }
        }
        //统计每周的每个快递公司的总数量
        foreach($vendorweek as $k=>$v){
            foreach($v as $kk=>$vv){
                $data_by_week_vendor = [
                    'time_by' => "week",
                    'quantity' => count($vv),
                    'dimension_site' => "no",
                    'dimension_vendor' => 'yes',
                    'year' => explode("-",$k)[0],
                    'week' => explode("-",$k)[1],
                    'vendor_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'week','dimension_site'=>'no','dimension_vendor'=>'yes','week'=>explode("-", $k)[1],'vendor_code'=>$kk],  $data_by_week_vendor);
            }
        }
        //统计每日的每个快递公司的总数量
        foreach($vendorday as $k=>$v){
            foreach($v as $kk=>$vv) {
                $data_by_day_vendor = [
                    'time_by' => "day",
                    'quantity' => count($vv),
                    'dimension_site' => "no",
                    'dimension_vendor' => 'yes',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'day' => explode("-",$k)[2],
                    'vendor_code' => $kk,
                    'create_time' => time()
                ];
                $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'day','dimension_site'=>'no','dimension_vendor'=>'yes','month'=>explode("-", $k)[1],'day'=>explode("-", $k)[2],'vendor_code'=>$kk],  $data_by_day_vendor);
            }
        }
        /**
         * 同时根据三个条件判断
         */

        //echo "每年的每个站点的每加快递公司";p(date("Y-m-d H:i:s",time()));
        //统计每年的每个站点的每加快递公司的总数量
        foreach($year_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $data_by_year_site_vendor = [
                        'time_by' => "year",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'yes',
                        'quantity' => count($vvv),
                        'year' => $k,
                        'site_code' => $kk,
                        'vendor_code' => $kkk,
                        'create_time' => time()
                    ];
                    $this->updateOrCreate(['year' => $k,'time_by'=>'year','dimension_site'=>'yes','dimension_vendor'=>'yes','site_code'=>$kk,'vendor_code'=>$kkk],  $data_by_year_site_vendor);
                }
            }
        }
        //统计每月的每个站点的每个快递公司的总数量
        foreach($month_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $data_by_month_site_vendor = [
                        'time_by' => "month",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'yes',
                        'quantity' => count($vvv),
                        'year' => explode("-", $k)[0],
                        'month' => explode("-", $k)[1],
                        'site_code' => $kk,
                        'vendor_code' => $kkk,
                        'create_time' => time()
                    ];
                    $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'month','dimension_site'=>'yes','dimension_vendor'=>'yes','month'=>explode("-", $k)[1],'site_code'=>$kk,'vendor_code'=>$kkk],  $data_by_month_site_vendor);
                }
            }
        }
        //统计每周的每个站点的每个快递公司的总数量
        foreach($week_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $data_by_week_site_vendor = [
                        'time_by' => "week",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'yes',
                        'quantity' => count($vvv),
                        'year' => explode("-", $k)[0],
                        'week' => explode("-", $k)[1],
                        'site_code' => $kk,
                        'vendor_code' => $kkk,
                        'create_time' => time()
                    ];
                    $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'week','dimension_site'=>'yes','dimension_vendor'=>'yes','week'=>explode("-", $k)[1],'site_code'=>$kk,'vendor_code'=>$kkk],  $data_by_week_site_vendor);
                }
            }
        }
        //统计每日的每个站点的每个快递公司的总数量
        foreach($day_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv) {
                foreach($vv as $kkk=>$vvv) {
                    $data_by_day_site_vendor = [
                        'time_by' => "day",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'yes',
                        'quantity' => count($vvv),
                        'year' => explode("-", $k)[0],
                        'month' => explode("-", $k)[1],
                        'day' => explode("-", $k)[2],
                        'site_code' => $kk,
                        'vendor_code' => $kkk,
                        'create_time' => time()
                    ];
                    $this->updateOrCreate(['year' => explode("-",$k)[0],'time_by'=>'day','dimension_site'=>'yes','dimension_vendor'=>'yes','month'=>explode("-", $k)[1],'day'=>explode("-", $k)[2],'site_code'=>$kk,'vendor_code'=>$kkk],  $data_by_day_site_vendor);
                }
            }
        }
        p(date("Y-m-d H:i:s",time()));

    }

    /**
     * 查询数据
     * $type type类型(年，月，周，日)
     * $year  年份
     * $month  月份
     * $week  周份
     * $day  日份
     * $site_code  站点编号
     * $vendor  快递公司
     */
    function get_waybill_statistic_by_time_site_vendor($type,$year="",$month="",$week="",$day="",$site_code="",$vendor="")
    {
        return $this->where(['time_by'=>$type,'year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>$site_code,'vendor_code'=>$vendor])->first()['id'];
    }

    function array_counts($arr,$key,$data = 'rows')
    {
        /*if(is_string($key)) $key = trim($key,',');
        if(strstr($key,',')) $key = explode(',',$key);*/
        foreach($arr as $item) {
            $item['rows'] =1;
            if (!isset($res[$item[$key]])) {
                $res[$item[$key]] = $item;
            }else{
                $res[$item[$key]][$data] +=$item['rows'] ;
            }
        }
        return $res;
    }


    public function get_adte($arr,$time){
        $temp=array();
        foreach($arr as $v){
            $temp[]=$v[$time];
        }
        $brarr=array_count_values($temp);
        $brandCount=count($brarr);
        return $brandCount;
    }

    /**
     * @return array
     * 派件查询summary
     * $summary_time 具体时间(2017年10月12日)
     * $time_by      时间类型(年月周日)
     * $dimensions  站点编号,快递公司
     */
    public function get_express_waybill_data_by_summary($summary_time,$time_by,$dimensions)
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
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>'','vendor_code'=>""])->first();
            }else{
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>explode("/",$dimensions)[1],'vendor_code'=>""])->first();
            }
        }elseif("vendor" == explode("/",$dimensions)[0]){
            if("all" == explode("/",$dimensions)[1]){
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>"",'vendor_code'=>""])->first();
            }else{
                $res = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>'','vendor_code'=>explode("/",$dimensions)[1]])->first();
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
     * 派件查询rank
     * $rank_time 具体时间
     * $time_by   时间类型
     * $dimensions  站点编号,快递公司
     * $type quantity-快件数量 charge-快件运费number-人员人数
     * $order_by 排序方式
     */
    public function get_express_waybill_data_by_rank($rank_time,$time_by,$dimensions,$type,$order_by)
    {
        //判断时间
        $year = substr($rank_time,0,strpos($rank_time, '年'));
        if(!strpos($rank_time, '周')){
            if(strpos($rank_time, '月')){
                $day    = mb_substr($rank_time,strpos($rank_time, '月')-1,2,'utf-8');
            }else{
                $day    = "";
            }
            $month  = mb_substr($rank_time,strpos($rank_time, '年')+1,2,'utf-8');
            $week   ="";
        }else{
            $month  = "";
            $day    = "";
            $week   = mb_substr($rank_time,strpos($rank_time, '年')+1,2,'utf-8');
        }
        //判断数组长度
        if("2" == count($dimensions)){//同时存在站点和快递公司两个条件
            if("site" == $dimensions[0]['key']){
                //获取量
                $total = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'site_code'=>$dimensions[0]['value']])->value($type);
            }else{
                $total = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,'vendor_code'=>$dimensions[0]['value']])->value($type);
            }
            $list = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','dimension_vendor'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day,$dimensions[0]['key'].'_code'=>$dimensions[0]['value']])->orderBy("$type",$order_by)->get();
        }elseif("1" == count($dimensions)){
            $total = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day])->value($type);

            if("site" == $dimensions[0]['key']){
                $list = $this->where(['time_by'=>$time_by,'dimension_site'=>'yes','dimension_vendor'=>'no','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day])->orderBy("$type",$order_by)->get();
            }else{
                $list = $this->where(['time_by'=>$time_by,'dimension_site'=>'no','dimension_vendor'=>'yes','year'=>$year,'month'=>$month,'week'=>$week,'day'=>$day])->orderBy("$type",$order_by)->get();
            }
        }
        $res = array("total"=>$total,"list"=>$list?$list:"");
        p($res);
        return  $res;
    }

}
