<?php
namespace App\Models\SkyScannerModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    //指定数据库
    protected $connection = 'mysql';
    //指定表名
    protected $table ="setting";
    //指定id
    protected $primaryKey = "id";
    //表明模型是否应该被打上时间戳
    public $timestamps = false;
    //开启白名单字段
    protected $fillable = [ 'gather_time', 'update_time'];
    //protected $guarded=[];

}
