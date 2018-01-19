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
    protected $table ="waybill_statistic";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;


    /**
     * 添加采集数据
     * $waybilldata 数据
     * $task_id 任务id
     */
    public function create_waybill_statistic($waybilldata,$task_id)
    {
        /*$arr = (new Waybill)->orderBy('id','desc')->paginate(300)->toArray()['data'];
        p($arr);die;*/
        $res = "";
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
            $res = $this->get_waybill_statistic_by_time_site_vendor("year",$k,"","","","","");
            $data_by_year['quantity'] = $v['rows'];
            if($res){//判断当前年份的数据是否已经存在
                $data_by_year['update_time'] = time();
                $this->where('id','=',"$res")->update($data_by_year);
            }else{
                $data_by_year = [
                    'time_by' => "year",
                    'dimension_site' => "no",
                    'dimension_vendor' => 'no',
                    'year' => $k,
                    'create_time' => time()
                ];
                $this->insert($data_by_year);
            }

        }
        //统计每月的总数量
        foreach($this->array_counts($waybilldata,"month") as $k=>$v){
            $res = $this->get_waybill_statistic_by_time_site_vendor("month",explode("-",$k)[0],explode("-",$k)[1],"","","","");
            $data_by_month['quantity'] = $v['rows'];
            if($res){//判断当前月份的数据是否已经存在
                $data_by_month['update_time'] = time();
                $this->where('id','=',"$res")->update($data_by_month);
            }else{
                $data_by_month = [
                    'time_by' => "month",
                    'dimension_site' => "no",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'create_time' => time()
                ];
                $this->insert($data_by_month);
            }
        }
        //统计每周的总数量
        foreach($this->array_counts($waybilldata,"week") as $k=>$v){
            $res = $this->get_waybill_statistic_by_time_site_vendor("week",explode("-",$k)[0],"",explode("-",$k)[1],"","","");
            $data_by_week['quantity'] = $v['rows'];
            if($res){//判断当前月份的数据是否已经存在
                $data_by_week['update_time'] = time();
                $this->where('id','=',"$res")->update($data_by_week);
            }else {
                $data_by_week = [
                    'time_by' => "week",
                    'dimension_site' => "no",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'week' => explode("-",$k)[1],
                    'create_time' => time()
                ];
                $this->insert($data_by_week);
            }
        }
        //统计每日的总数量
        foreach($this->array_counts($waybilldata,"day") as $k=>$v){
            $res = $this->get_waybill_statistic_by_time_site_vendor("day",explode("-",$k)[0],explode("-",$k)[1],"",explode("-",$k)[2],"","");
            $data_by_day['quantity'] = $v['rows'];
            if($res){//判断当前月份的数据是否已经存在
                $data_by_day['update_time'] = time();
                $this->where('id','=',"$res")->update($data_by_day);
            }else {
                $data_by_day = [
                    'time_by' => "day",
                    'dimension_site' => "no",
                    'dimension_vendor' => 'no',
                    'year' => explode("-",$k)[0],
                    'month' => explode("-",$k)[1],
                    'day' => explode("-",$k)[2],
                    'create_time' => time()
                ];
                $this->insert($data_by_day);
            }
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
            //(new Waybill)->orderBy('id','desc')->groupBy('site_code')->paginate(300)->toArray()['data'];
            foreach($v as $kk=>$vv){
                $res = $this->get_waybill_statistic_by_time_site_vendor("year",$k,"","","",$kk,"");
                $data_by_year_site['quantity'] = count($vv);
                if($res){//判断当前年份的数据是否已经存在
                    $data_by_year_site['update_time'] = time();
                    $this->where('id','=',"$res")->update($data_by_year_site);
                }else {
                    $data_by_year_site = [
                        'time_by' => "year",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'no',
                        'year' => $k,
                        'site_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_year_site);
                }
            }
        }
        //统计每月的每个站点的总数量
       foreach($sitemonth as $k=>$v){
            foreach($v as $kk=>$vv){
                $res = $this->get_waybill_statistic_by_time_site_vendor("month",explode("-",$k)[0],explode("-",$k)[1],"","",$kk,"");
                $data_by_month_site['quantity'] = count($vv);
                if($res){//判断当前年份的数据是否已经存在
                    $data_by_month_site['update_time'] = time();
                    $this->where('id','=',"$res")->update($data_by_month_site);
                }else {
                    $data_by_month_site = [
                        'time_by' => "month",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'no',
                        'year' => explode("-",$k)[0],
                        'month' => explode("-",$k)[1],
                        'site_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_month_site);
                }
            }
        }
        //统计每周每个站点的总数量
        foreach($siteweek as $k=>$v){
            foreach($v as $kk=>$vv) {
                $res = $this->get_waybill_statistic_by_time_site_vendor("week", explode("-", $k)[0],"", explode("-", $k)[1], "", $kk, "");
                $data_by_week_site['quantity'] = count($vv);
                if ($res) {//判断当前月份的数据是否已经存在
                    $data_by_week_site['update_time'] = time();
                    $this->where('id', '=', "$res")->update($data_by_week_site);
                } else {
                    $data_by_week_site = [
                        'time_by' => "week",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'no',
                        'year' => explode("-",$k)[0],
                        'week' => explode("-",$k)[1],
                        'site_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_week_site);
                }
            }
        }
        //统计每日每个站点的总数量
        foreach($siteday as $k=>$v){
            foreach($v as $kk=>$vv) {
                $res = $this->get_waybill_statistic_by_time_site_vendor("day",explode("-",$k)[0],explode("-",$k)[1],"",explode("-",$k)[2],$kk,"");
                $data_by_day_site['quantity'] = count($vv);
                if ($res) {//判断当前月份的数据是否已经存在
                    $data_by_day_site['update_time'] = time();
                    $this->where('id', '=', "$res")->update($data_by_day_site);
                } else {
                    $data_by_day_site = [
                        'time_by' => "day",
                        'dimension_site' => "yes",
                        'dimension_vendor' => 'no',
                        'year' => explode("-",$k)[0],
                        'month' => explode("-",$k)[1],
                        'day' => explode("-",$k)[2],
                        'site_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_day_site);
                }
            }
        }
        //统计每年的每个快递公司的总数量
        foreach($vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                $res = $this->get_waybill_statistic_by_time_site_vendor("year",$k,"","","","","$kk");
                $data_by_year_vendor['quantity'] = count($vv);
                if($res){//判断当前年份的数据是否已经存在
                    $data_by_year_vendor['update_time'] = time();
                    $this->where('id','=',"$res")->update($data_by_year_vendor);
                }else {
                    $data_by_year_vendor = [
                        'time_by' => "year",
                        'dimension_site' => "no",
                        'dimension_vendor' => 'yes',
                        'year' => $k,
                        'vendor_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_year_vendor);
                }
            }
        }
        //统计每月的每个快递公司的总数量
        foreach($vendormonth as $k=>$v){
            foreach($v as $kk=>$vv){
                $res = $this->get_waybill_statistic_by_time_site_vendor("month",explode("-",$k)[0],explode("-",$k)[1],"","","",$kk);
                $data_by_month_vendor['quantity'] = count($vv);
                if($res){
                    $data_by_month_vendor['update_time'] = time();
                    $this->where('id','=',"$res")->update($data_by_month_vendor);
                }else {
                    $data_by_month_vendor = [
                        'time_by' => "month",
                        'dimension_site' => "no",
                        'dimension_vendor' => 'yes',
                        'year' => explode("-",$k)[0],
                        'month' => explode("-",$k)[1],
                        'vendor_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_month_vendor);
                }
            }
        }
        //统计每周的每个快递公司的总数量
        foreach($vendorweek as $k=>$v){
            foreach($v as $kk=>$vv){
                $res = $this->get_waybill_statistic_by_time_site_vendor("week",explode("-",$k)[0],"",explode("-",$k)[1],"","",$kk);
                $data_by_week_vendor['quantity'] = count($vv);
                if($res){
                    $data_by_week_vendor['update_time'] = time();
                    $this->where('id','=',"$res")->update($data_by_week_vendor);
                }else {
                    $data_by_week_vendor = [
                        'time_by' => "week",
                        'dimension_site' => "no",
                        'dimension_vendor' => 'yes',
                        'year' => explode("-",$k)[0],
                        'week' => explode("-",$k)[1],
                        'vendor_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_week_vendor);
                }
            }
        }
        //统计每日的每个快递公司的总数量
        foreach($vendorday as $k=>$v){
            foreach($v as $kk=>$vv) {
                $res = $this->get_waybill_statistic_by_time_site_vendor("day",explode("-",$k)[0],explode("-",$k)[1],"",explode("-",$k)[2],"",$kk);
                $data_by_day_vendor['quantity'] = count($vv);
                if ($res) {//判断当前月份的数据是否已经存在
                    $data_by_day_vendor['update_time'] = time();
                    $this->where('id', '=', "$res")->update($data_by_day_vendor);
                } else {
                    $data_by_day_vendor = [
                        'time_by' => "day",
                        'dimension_site' => "no",
                        'dimension_vendor' => 'yes',
                        'year' => explode("-",$k)[0],
                        'month' => explode("-",$k)[1],
                        'day' => explode("-",$k)[2],
                        'vendor_code' => $kk,
                        'create_time' => time()
                    ];
                    $this->insert($data_by_day_vendor);
                }
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
                    $res = $this->get_waybill_statistic_by_time_site_vendor("year", $k, "", "", "", $kk,$kkk);
                    $data_by_year_site_vendor['quantity'] = count($vvv);
                    if ($res) {//判断当前年份的数据是否已经存在
                        $data_by_year_site_vendor['update_time'] = time();
                        $this->where('id', '=', "$res")->update($data_by_year_site_vendor);
                    } else {
                        $data_by_year_site_vendor = [
                            'time_by' => "year",
                            'dimension_site' => "yes",
                            'dimension_vendor' => 'yes',
                            'year' => $k,
                            'site_code' => $kk,
                            'vendor_code' => $kkk,
                            'create_time' => time()
                        ];
                        $this->insert($data_by_year_site_vendor);
                    }
                }
            }
        }
        //统计每月的每个站点的每个快递公司的总数量
        foreach($month_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $res = $this->get_waybill_statistic_by_time_site_vendor("month", explode("-", $k)[0], explode("-", $k)[1], "", "", $kk,$kkk);
                    $data_by_month_site_vendor['quantity'] = count($vvv);
                    if ($res) {
                        $data_by_month_site_vendor['update_time'] = time();
                        $this->where('id', '=', "$res")->update($data_by_month_site_vendor);
                    } else {
                        $data_by_month_site_vendor = [
                            'time_by' => "month",
                            'dimension_site' => "yes",
                            'dimension_vendor' => 'yes',
                            'year' => explode("-", $k)[0],
                            'month' => explode("-", $k)[1],
                            'site_code' => $kk,
                            'vendor_code' => $kkk,
                            'create_time' => time()
                        ];
                        $this->insert($data_by_month_site_vendor);
                    }
                }
            }
        }
        //统计每周的每个站点的每个快递公司的总数量
        foreach($week_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv){
                foreach($vv as $kkk=>$vvv) {
                    $res = $this->get_waybill_statistic_by_time_site_vendor("week", explode("-", $k)[0],  "", explode("-", $k)[1],"", $kk,$kkk);
                    $data_by_week_site_vendor['quantity'] = count($vvv);
                    if ($res) {
                        $data_by_week_site_vendor['update_time'] = time();
                        $this->where('id', '=', "$res")->update($data_by_week_site_vendor);
                    } else {
                        $data_by_week_site_vendor = [
                            'time_by' => "week",
                            'dimension_site' => "yes",
                            'dimension_vendor' => 'yes',
                            'year' => explode("-", $k)[0],
                            'week' => explode("-", $k)[1],
                            'site_code' => $kk,
                            'vendor_code' => $kkk,
                            'create_time' => time()
                        ];
                        $this->insert($data_by_week_site_vendor);
                    }
                }
            }
        }
        //统计每日的每个站点的每个快递公司的总数量
        foreach($day_site_vendor as $k=>$v){
            foreach($v as $kk=>$vv) {
                foreach($vv as $kkk=>$vvv) {
                    $res = $this->get_waybill_statistic_by_time_site_vendor("day", explode("-", $k)[0], explode("-", $k)[1], "", explode("-", $k)[2],$kk,$kkk);
                    $data_by_day_site_vendor['quantity'] = count($vvv);
                    if ($res) {//判断当前月份的数据是否已经存在
                        $data_by_day_site_vendor['update_time'] = time();
                        $this->where('id', '=', "$res")->update($data_by_day_site_vendor);
                    } else {
                        $data_by_day_site_vendor = [
                            'time_by' => "day",
                            'dimension_site' => "yes",
                            'dimension_vendor' => 'yes',
                            'year' => explode("-", $k)[0],
                            'month' => explode("-", $k)[1],
                            'day' => explode("-", $k)[2],
                            'site_code' => $kk,
                            'vendor_code' => $kkk,
                            'create_time' => time()
                        ];
                        $this->insert($data_by_day_site_vendor);
                    }
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

}
