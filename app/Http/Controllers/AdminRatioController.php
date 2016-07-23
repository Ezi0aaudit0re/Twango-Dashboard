<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Profile;
use App\Compatibility;
use App\College;
use App\Event;
use App\Ratio;
use App\EventPhoto;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Storage;


class AdminRatioController extends Controller
{
    public function __construct()
    {

    }


    public function getMaleFemaleRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['totalM','totalF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }

     public function getActiveMaleFemaleRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['activeM','activeF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }
     public function getNewMaleFemaleRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['newM','newF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }
     public function getMutualLikeRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['mutualLikeM','mutualLikeF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }
     public function getavTimeFirstMatchRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['avTimeFirstMatchM','avTimeFirstMatchF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }
     public function getavTimeMatchRatio()
    {
    	try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>Ratio::take(30)->get(['avTimeMatchM','avTimeMatchF', 'created_at'])]);
            //paginate(10,array('id','firstName','lastName','email','phone','role','dpUrl','status'))->items()]);
       }


       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }

}
