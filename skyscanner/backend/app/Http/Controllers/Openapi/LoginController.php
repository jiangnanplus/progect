<?php

namespace App\Http\Controllers\Openapi;

use App\Models\SkyScannerModel\AccessCode;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

require_once '../resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    //登录接口
    public function verify()
    {
        $results = new \results;
        $errors = new \errors;
        $code = new \Code;
        $_code = $code->get();
        p($_code);
        //判断验证码是否正确
        if($_GET){
            if(!$_GET['image_verify_code']){
                $results->error($errors::E_ABSENT_IMAGES_CODE);
            }else{
                $image_verify_code = $_GET['image_verify_code'];
            }
            if(strtoupper($image_verify_code)!=$_code){
                $results->error($errors::E_IMAGES_CODE_NOT_SAME_EXCEPT);
            }
            //判断$token是否在缓存里面
            if( Redis::get($_GET['token'])){
                $data['token'] = $_GET['token'];
                $results->success_data($data);die;
            }
            $user = User::first();
            if($user->access_code != $_GET['access_code']){
                $results->error($errors::E_PASSWORD_ERROR);
            }
            //生成token值写入redis
            $data['token'] = md5($user->access_code);
            //获取当前时间到下下周周日最后一秒的时间
            $end_time = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y')) -3600*8;
            Redis::set($data['token'],time());
            Redis::expire($data['token'], $end_time-time()+604800);
            //登陆成功
            $results->success_data($data);
        }else{
            $results->error($errors::E_ERROR);
        }
    }

    public function getlogin()
    {
        if($input = Input::all()){
            $code = new \Code;
            $_code = $code->get();
            if(strtoupper($input['code'])!=$_code){
                return back()->with('msg','验证码错误！');
            }
            $user = User::first();
            if($user->access_code != $input['user_name']){
                return back()->with('msg','访问码错误！');
            }
            //登陆成功
            session(['user'=>$user]);
            return redirect('openapi/index');
        }else {
            return view('openapi/login');
        }
    }

    //退出
    public function quit()
    {
        session(['user'=>null]);
        return redirect('openapi/login');
    }

    //验证码接口
    public function code()
    {
        $code = new \Code;
        $code->make();
    }

}
