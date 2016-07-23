<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use DB;
use App\AdminUser;
use App\Profile;
use App\Like;
use App\Compatibility;
use App\PsychometricResponse;
use App\Message;
use App\ProfilePhoto;
use App\Guy;
use App\Girl;
use App\College;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Jobs\CalculateCompatibility;
use App\Jobs\ActivateProfile;
use App\Jobs\CloudMessaging;
use Redis;
use App\EventUser;
use App\Event;
class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function __construct()
   {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it

       $this->middleware('jwt.auth',['except'=>['login','signUp']]);
   }



    public function login(Request $request)
    {
      if($request->has('email')&&$request->has('password'))
      {
        try{

        $user=User::where('email',$request->get('email'))->where('status','1')->where('password',$request->get('password'))->where('role','admin')->first();
        if(sizeof($user))
        {



          try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::fromUser($user)) {

            return response()->json(['message'=> 'invalid_credentials','code'=> 401,'response'=>'']);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['message'=> 'could_not_create_token', 'code'=>500,'response'=>'']);
        }

                return response()->json(['message'=> 'success', 'code'=>200,'response'=>['token'=>$token,'user'=>$request->get('email')]]);
        }
        return response()->json(['code'=>400,'message'=>'invalid_credentials','response'=>'']);
        }
        catch (JWTException $e) {
            // something went wrong
            return response()->json(['message'=> 'could_not_create_token', 'code'=>500,'response'=>'']);
        }
        }
                    return response()->json(['message'=> 'could_not_create_token', 'code'=>500,'response'=>'']);


    }

    public function signUp(Request $request)
    {
      if($request->has('email')&&$request->has('password')&&$request->has('phone')&&$request->has('firstName')&&$request->has('lastName'))
      {
        try{

        User::create(['email'=>$request->get('email'),'password'=>$request->get('password'),'phone'=>$request->get('phone'),'firstName'=>$request->get('firstName'),'lastName'=>$request->get('lastName'),'status'=>'0','role'=>'admin']);
        return response()->json(['code'=>200,'message'=>'Done!','response'=>'']);
        }
        catch (exception $e)
        {
          return response()->json(['code'=>500,'message'=>'Sorry Request Not completed!','response'=>'']);
        }
      }
      else{
      return response()->json(['code'=>404,'message'=>'Credentials Not Present','response'=>'']);
   }

    }
   public function getDisapprovedUsers(Request $request)
  {

      try
       {
          return  response()->json(['code'=>200,'message'=>"success",'response'=>User::where('isApproved',0)->where('role','user')->whereIn('status',[1,3,4,5])->with(array('profile'=>function($query){
            $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
          ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status','isApproved'))]);

       }
       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
   }

    public function disapproveUser(Request $request)
    {
      $profile = Profile::where('userId',$request->get('id'))->first(['deviceOs','deviceId','photoScore','gender']);
      try{
        User::where('id',$request->get('id'))->update(['status'=>2,'reason'=>$request->get('reason'),'isApproved'=>2]);
        Profile::where('userId',$request->get('id'))->update(['isApproved'=>2]);
        if($profile->gender=='Male'){
          Guy::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //   Redis::sadd('compatibleProfileSeen', $request->get('id'));
        }else{
          Girl::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //   Redis::sadd('compatibleProfileSeen', $request->get('id'));
        }
        ProfilePhoto::where('userId',$request->get('id'))->delete();
        // $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>2,'isApproved'=>2,'photoScore'=>$profile->photoScore,'reason'=>$request->get('reason'),'isPush'=>1)));
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

    public function approveUser(Request $request)
    {

      //$token = JWTAuth::getToken();

      //$user = JWTAuth::toUser($token);
      $id = $request->get('id');
      try{
        $user=User::where('id',$id)->first(['status']);
        $profile=Profile::where('userId',$id)->first(['currentCity','deviceOs','deviceId','gender','isApproved']);
        $event = Event::where('date','>=',date('Y-m-d'))->first(['id','venueCity']);
        if(sizeof($event)==1){
        $eventId = $event->id;
          if((stripos($profile->currentCity,'delhi')!==false)||(stripos($profile->currentCity,'gurgoan'))){
            $check = EventUser::where('userId',$id)->where('eventId',$eventId)->first(['eventId']);
              if(sizeof($check)==0){
                 EventUser::create(array('userId' => $id,'eventId'=>$eventId,'status'=>1));
              }
          }
        }

        if($user->status==7)
        {
          User::where('id',$id)->update(['status'=>8,'isApproved'=>1]);
          Profile::where('userId',$id)->update(['isApproved'=>1,'photoScore'=>$request->get('photoScore')]);
          if($profile->gender=='Male'){
             Guy::create(array('userId'=>$id,'reason'=>'1'));
          }else{
             Girl::create(array('userId'=>$id,'reason'=>'1'));
          }
          if($profile->deviceId){
        //   $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>8,'isApproved'=>1,'photoScore'=>$request->get('photoScore'),'reason'=>'no reason','isPush'=>0)));
          }
        }
        else if($user->status==5||$user->status==6)
        {
          if($profile->gender=='Male'){
           Guy::create(array('userId'=>$id,'reason'=>'1'));
          }else{
           Girl::create(array('userId'=>$id,'reason'=>'1'));
          }
          User::where('id',$id)->update(['isApproved'=>1]);
          Profile::where('userId',$id)->update(['photoScore'=>$request->get('photoScore')]);
          if($profile->deviceId){
            // $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>5,'isApproved'=>1,'photoScore'=>$request->get('photoScore'),'reason'=>'no reason','isPush'=>1)));
          }
          if($profile->isApproved==0||$profile->isApproved==4){
            $this->dispatch(new CalculateCompatibility(User::with(
                  array('profile'=>function($query){
                  $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','salaryScore','collegeScore','photoScore','interests','cityLatitude','cityLongitude','userId');},
                  'psychometric' =>function($query){
                  $query->select('typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId');
                  }))->where('id',$id)->first(array('id','token','uid'))));
          }else if($profile->isApproved==2){
            $this->dispatch(new ActivateProfile(User::with(
                  array('profile'=>function($query){
                  $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','salaryScore','collegeScore','photoScore','interests','cityLatitude','cityLongitude','userId');},
                  'psychometric' =>function($query){
                  $query->select('typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId');
                  }))->where('id',$id)->first(array('id','token','uid'))));
          }
        }
        else
        {
          User::where('id',$id)->update(['isApproved'=>1]);
          if($profile->deviceId){
        //   $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>$user->status,'isApproved'=>1,'photoScore'=>5.5,'reason'=>'no reason','isPush'=>1)));
          }

        }
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

    public function blockUser(Request $request)
    {

      $token = JWTAuth::getToken();

      $user = JWTAuth::toUser($token);
      $id = $request->get('id');
      try{
        $user=User::where('id',$id)->first(['status']);
        $profile=Profile::where('userId',$id)->first(['deviceOs','deviceId','gender','isApproved']);
        if($request->get('reason')=='We are not in your city'){
          User::where('id',$id)->update(['isApproved'=>5]);
          Profile::where('userId',$id)->update(['isApproved'=>5]);
          if($profile->deviceId){
            // $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>$user->status,'isApproved'=>5,'photoScore'=>'','reason'=>'We are not in your city','isPush'=>0)));
        }
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
        }
        if($user->status!=7)
        {
          if($profile->gender=='Male'){
         Guy::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //  Redis::sadd('compatibleProfileSeen', $id);
        }else{
          Girl::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //   Redis::sadd('compatibleProfileSeen', $id);
        }
        User::where('id',$id)->update(['isApproved'=>0,'isReported'=>0,'status'=>7]);
        Profile::where('userId',$id)->update(['isApproved'=>0]);
        if($profile->deviceId){
            // $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>7,'isApproved'=>0,'photoScore'=>'','reason'=>'no reason','isPush'=>0)));
        }
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
    }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

    public function getWaitlistedUsers(Request $request)
  {

      try
       {
          return  response()->json(['code'=>200,'message'=>"success",'response'=>User::where('isApproved',3)->where('role','user')->whereIn('status',[1,3,4,5])->with(array('profile'=>function($query){
            $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
          ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status','isApproved'))]);

       }
       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
   }

   public function getInvitedWaitlistedUsers(Request $request)
  {

      try
       {
          return  response()->json(['code'=>200,'message'=>"success",'response'=>User::where('isApproved',4)->where('role','user')->whereIn('status',[1,3,4,5])->with(array('profile'=>function($query){
            $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
          ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status','isApproved'))]);

       }
       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
   }

    public function waitlistUser(Request $request)
    {
      $profile = Profile::where('userId',$request->get('id'))->first(['deviceOs','deviceId','photoScore','gender']);
      $user = User::where('id',$request->get('id'))->first(['status']);
      try{
        $waitListNo = User::where('isApproved',3)->get(['id']);
        User::where('id',$request->get('id'))->update(['isApproved'=>3,'waitListNo'=>50+sizeof($waitListNo)]);
        Profile::where('userId',$request->get('id'))->update(['isApproved'=>3]);
        if($profile->gender=='Male'){
          Guy::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //   Redis::sadd('compatibleProfileSeen', $request->get('id'));
        }else{
          Girl::where('userId', $request->get('id'))->whereIn('reason',['1','2'])->delete();
        //   Redis::sadd('compatibleProfileSeen', $request->get('id'));
        }
        // $this->dispatch(new CloudMessaging($profile->deviceOs, $profile->deviceId, array('key'=>'changeStatus','status'=>$user->status,'isApproved'=>3,'photoScore'=>$profile->photoScore,'reason'=>'no reason','waitListNo'=>50+sizeof($waitListNo),'isPush'=>1)));
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

    public function getUnverifiedUsers(Request $request)
  {
    try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('status',6)->where('role','user')->where('isApproved', 1)->with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

  }

  public function verifyUser($id,Request $request)
    {

      $token = JWTAuth::getToken();

       $user = JWTAuth::toUser($token);
       try{
          User::where('id',$id)->update(['status'=>8]);
          Profile::where('userId',$request->get('id'))->update(['isApproved'=>1,'photoScore'=>$request->get('photoScore')]);

        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

  public function unverifyUser(Request $request)
    {
      try{
        User::where('id',$request->get('id'))->update(['isApproved'=>2,'reason'=>$request->get('reason'),'status'=>2]);
    Profile::where('userId',$request->get('id'))->update(['isApproved'=>2]);
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }

    public function getBlockedUsers(Request $request)
  {
    try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('status',7)->where('role','user')->where('isApproved', 0)->with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

  }

  public function getReportedUsers(Request $request)
  {
    try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('isReported',1)->where('role','user')->with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

  }

  public function removeuserfromreported(Request $request)
    {
      try{
        User::where('id',$request->get('id'))->update(['isReported'=>0]);
        return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
      }
      catch(Exception $e)
    {
      return response()->json(['code'=>500,'message'=>"Server Error",'response'=>""]);
    }

    }


  public function getRejectedUsers(Request $request)
  {
    try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('isApproved',2)->where('role','user')->where('status',2)->with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

  }
    public function getAdmins(Request $request)
    {
      try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('role','admin')->get(['id','email','firstName','lastName','phone','status'])]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
    }

    public function approveAdmin($id,Request $request)
    {
      try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('id',$id)->update(['status'=>1])]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
    }


    public function getAll(Request $request)
    {
      try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::where('role','user')->with(array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->get(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status','isApproved'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }
    }




    public function show($id,Request $request)
    {
        $token = JWTAuth::getToken();
       $user = JWTAuth::toUser($token);
       try
       {

       return  response()->json(['code'=>200,'message'=>"ok",'response'=>User::with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','friends','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');}
            ))->where('id',$id)->first(array('id','firstName','lastName','email','phone','role','dpUrl','profileUrl','status'))]);
       }


       catch(Exception $e)
       {
          return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }

    public function update($id,Request $request)
    {
      if($request->get('type')=='photoRating')
      {
    try{
    Profile::where('userId',$id)->update(['photoScore'=>$request->get('photoScore')]);
    return response()->json(['code'=>200,'message'=>"Done!",'response'=>""]);
    }
    catch(Exception $e)
    {
      return response()->json(['code'=>404,'message'=>"Does NOt Exists!",'response'=>""]);
    }
      }
      return response()->json(['code'=>403,'message'=>"Wrong Parameter",'response'=>""]);
    }


    //public function
    public function delete($id,Request $request)
    {
       User::destroy($id);
       Profile::where('userId',$id)->delete();
       PsychometricResponse::where('userId',$id)->delete();
       ProfilePhoto::where('userId',$id)->delete();
       Compatibility::where('userAId',$id)->delete();
       Compatibility::where('userBId',$id)->delete();
       Like::where('userAId',$id)->delete();
       Like::where('userBId',$id)->delete();
       Message::where('userAId',$id)->delete();
       Message::where('userBId',$id)->delete();
       return response()->json(['code'=>200,'message'=>"success",'response'=>""]);
    }

    public function matchAssign($userAId, $userBId){
      Compatibility::where('userAId', $userAId)->where('userBId',$userBId)->update(['profileShown'=>1, 'profileSeen'=>0]);
      return response()->json(['code'=>200,'message'=>"success",'response'=>""]);
    }

    public function gcmId($userId){
      $profile = Profile::where('userId', $userId)->get();
      return response()->json(['code'=>200,'message'=>"success",'response'=>$profile]);
    }


    public function getMatchAssign(Request $request){
        define('PAGINATION', 40);
        if(gettype($request->page) == "string")
            $request->page = intval($request->page);

        $orderBy = ($request->orderBy) ? $request->orderBy : 'updated_at';
        $reverse = ($request->reverse) ? $request->reverse : 'desc';
        $totalPage = Compatibility::where('profileShown', 1)->where('profileSeen', 0)->count();
        $result['next_page_url'] = ($request->page < ceil($totalPage / PAGINATION)) ? ($request->page + 1) : null;
        $result['prev_page_url'] = ($request->page > 0) ? ($request->page - 1) : null;
        // echo "<pre>";
        // var_dump($request->page);
        // die();
        $result['data'] = Compatibility::where('profileShown', 1)->where('profileSeen', 0)->orderBy($orderBy, $reverse)->skip($request->page * PAGINATION)->take(PAGINATION)->get(['userAId', 'userBId', 'updated_at']);
        if($result)
            return response()->json(['code'=> 200, 'message'=>'Success', 'response'=>$result]);
        else
            return response()->json(['code'=>500, 'message'=>'Internal server error']);
    }

    public function matchAssignNew(Request $request){
        // $line = explode(PHP_EOL, $request->data);
        $line = explode(PHP_EOL, $request->data);
        for($i = 0, $n = count($line) ; $i < $n; $i++){
            $line[$i] = explode("\t", $line[$i]);
            if(strcasecmp ( $line[$i][count($line[$i]) - 1] , 'match' ) == 0 && count($line[$i]) == 3){
                if(Girl::where('userId', $line[$i][0])->get(['id'])){
                    $result = Girl::where('userId', $line[$i][0])->delete();
                }
                if(!$result){
                    if(Guy::where('userId', $line[$i][0])->get(['id'])){
                        $result = Guy::where('userId', $line[$i][0])->delete();
                    }
                }
            }
            if(count($line[$i]) == 3)
                $result = Compatibility::where('userAId', $line[$i][0])->where('userBId', $line[$i][count($line[$i]) - 2])->update(['profileShown' => '1']);
            if(!$result){
                return json_encode(['code'=> 500, 'message'=>'UsersA with id '. $line[$i][0] .' UserB with Id '. $line[$i][1] . 'gender is not found in Compatibility Table']);
            }
        }

        return json_encode(['code' => 200, 'message' => 'Successfuly Updated']);


    }
}
