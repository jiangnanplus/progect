<?php

namespace App\Http\Controllers\Openapi;

use Illuminate\Http\Request;
use Cache;

class IndexController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results    = new \results;
        $errors     = new \errors;
        $method     = $request->input('method');
        $class_name = check_method($method, 'class_name');
        switch ($class_name)
        {
            case 'image_verify_code':
                return redirect()->action('Openapi\LoginController@code',$request->all());
                break;

            case 'access_code':
                return redirect()->action('Openapi\LoginController@verify',$request->all());
                break;

            case 'summary':
                return redirect()->action('Openapi\SummaryController@get',$request->all());
                break;

            case 'rank':
                return redirect()->action('Openapi\RankController@get',$request->all());
                break;

            default:
                $results->error($errors::E_METHOD_ERROR);
                break;
        }
    }

}
