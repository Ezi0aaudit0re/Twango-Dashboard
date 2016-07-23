<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Profile;
use App\ProfilePhoto;
use App\Compatibility;
use App\College;
use App\PsychometricResult;
use App\PsychometricResponse;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Redis;
use App\Like;
use App\Guy;
use App\Girl;
use App\Message;
use Storage;
use Illuminate\Database\Eloquent\Collection;
use DB;
class UserController extends Controller
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
       $this->middleware('jwt.auth');
   }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $user =User::create($requests);
        $user->save();
        //$input = Input::all();
        //var_dump($userData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
       $token = JWTAuth::getToken();
       $user = JWTAuth::toUser($token);
       if($request->has('fields'))
        {

            $user = User::where('id',$user->id)->first(['status','isApproved','validUser','waitListNo']);
            return response()->json(['code'=>200,'message'=>"Success",'response'=>$user]);
        }
       try
       {
        $profile=Compatibility::where('userAId',$user->id)->where('userBId',$id)->where('profileShown','1')->first();
        $firstMatch=Redis::sismember('firstMatch',$user->id.'-'.$id);
        $secondMatch=Redis::sismember('secondMatch',$user->id.'-'.$id);
       if(sizeof($profile)==1||$firstMatch||$secondMatch)
       {
        $user = User::with(
            array('profile'=>function($query)use($user){
                $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','companyName','position','salary','college','degree','interests','userId');},
                'fromMessage' =>function($query) use($user){
                $query->select('userAId','message','created_at')->where('userBId',$user->id)->where('type','1')->take(1);},
                'toMessage' =>function($query) use($user){
                 $query->select('userBId','message','created_at')->where('userAId',$user->id)->where('type','1')->take(1);},
                'isLiked' =>function($query) use($user){
                $query->select('userAId','isLike')->where('userBId',$user->id)->whereIn('isLike',[2,4,6])->take(1);},
                'compatibility' =>function($query) use($user){
                $query->select('userAId','mutualFriends')->where('userBId',$user->id)->take(1);
                }))->where('id',$id)->first(array('id','firstName','lastName','email','phone','role','dpUrl','status'));
        $user->firstName = ucwords(substr($user->firstName, 0, 1));
        $user->lastName = ucwords(substr($user->lastName, 0, 1));
        //$user->dpUrl = 'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$user->dpUrl;
        $user->isLiked = sizeof($user->isLiked) ? '1' : '0';
       return  response()->json(['code'=>200,'message'=>"ok",'response'=>['user'=>$user,'personality'=>$this->_getPersonality($id,$user->profile->gender),'date'=>date_format(date_create(date('Y-m-d')), 'j M y')]]);
       }
       else{
       return response()->json(['code'=>403,'message'=>"Not Allowed",'response'=>""]);
       }
       }
       catch(Exception $e)
       {
       		return response()->json(['code'=>404,'message'=>"Not Allowed",'response'=>""]);
       }

    }

    public function update(Request $request)
     {
        //return response()->json(['req'=>$request->get('socialLikes')]);//,'id'=>$id]);
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $id=$user->id;
        if($request->get('type')=="status")
        {
            return $this->_updateStatus($id,$request);
        }
        if($request->get('type')=="isReported")
        {
            return $this->_updateReportUser($id,$request);
        }
         return response()->json(['message'=>'Method Not Allowed','code'=>402,'response'=>'']);

    }

    private function _updateStatus($id,$request){
        try{
            if($request->get('status')==6){
                User::where('id',$id)->update(['status'=>6,'isApproved'=>1]);
                //$this->dispatch(new CleverTapUpdateProfile($id, array('status'=>6,'isApproved'=>1)));
                //Profile::where('userId',$id)->update(['isApproved'=>1]);
            }else{
                User::where('id',$id)->update(['status'=>$request->get('status'),'isApproved'=>0]);
                Profile::where('userId',$id)->update(['isApproved'=>0]);
            }
        return response()->json(['message'=>'OK','code'=>200,'response'=>array('status'=>$request->get('status'))]);
        }
        catch(Exception $e)
        {
            return response()->json(['message'=>'Not Allowed','code'=>404,'response'=>'']);
        }
    }

    private function _updateReportUser($id,$request){
        try{
            User::where('id',$id)->update(['isReported'=>$request->get('isReported')]);
        return response()->json(['message'=>'success','code'=>200,'response'=>'']);
        }
        catch(Exception $e)
        {
            return response()->json(['message'=>'Not Allowed','code'=>404,'response'=>'']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::delete($id);
    }

    public function getUser(Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        return response()->json(['code'=>200,'message'=>"success",'response'=>array('status'=>$user->status,'isApproved'=>$user->isApproved,'validUser'=>$user->validUser,'waitListNo'=>$user->waitListNo)]);
    }
    public function getProfile($id){
        return  response()->json(User::with(
            array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','companyName','position','salary','college','degree','interests','hobbies','cityLatitude','cityLongitude','userId');}
            ))->where('id',$id)->first(array('id','firstName','lastName','email','phone','role','dpUrl','status')));
    }

    private function _getPersonality($id, $gender){
        $personality = PsychometricResponse::where('userId',$id)->first(['typeA','typeB','typeC','typeD','typeE','valueA','valueB']);
        $combined = array($personality->typeA,$personality->typeB);
        sort($combined);
        if($gender=='Male'){
            $combinedPersonality = PsychometricResult::where('type',$combined[0]."-".$combined[1])->first(['type','keyword','maleDescription','colorCode']);
            $combinedPersonality->description = $combinedPersonality->maleDescription;
            $combinedPersonality->colorCode = '#82d1d8';
            $primaryPersonality = PsychometricResult::where('type',$personality->typeA)->first(['type','keyword','maleDescription','colorCode']);
            $primaryPersonality->description = $primaryPersonality->maleDescription;
            $primaryPersonality->colorCode = '#00ff84';
            $secondaryPersonality = PsychometricResult::where('type',$personality->typeB)->first(['type','keyword','maleDescription','colorCode']);
            $secondaryPersonality->description = $secondaryPersonality->maleDescription;
            $secondaryPersonality->colorCode = '#f6cf10';
        }else{
            $combinedPersonality = PsychometricResult::where('type',$combined[0]."-".$combined[1])->first(['type','keyword','femaleDescription','colorCode']);
            $combinedPersonality->description = $combinedPersonality->femaleDescription;
            $combinedPersonality->colorCode = '#82d1d8';
            $primaryPersonality = PsychometricResult::where('type',$personality->typeA)->first(['type','keyword','femaleDescription','colorCode']);
            $primaryPersonality->description = $primaryPersonality->femaleDescription;
            $primaryPersonality->colorCode = '#00ff84';
            $secondaryPersonality = PsychometricResult::where('type',$personality->typeB)->first(['type','keyword','femaleDescription','colorCode']);
            $secondaryPersonality->description = $secondaryPersonality->femaleDescription;
            $secondaryPersonality->colorCode = '#f6cf10';

        }
        $primaryPercentage = round(abs($personality->valueA)/(abs($personality->valueA)+abs($personality->valueB)), 2);
        $secondaryPercentage = 1 - $primaryPercentage;
        return array('primaryPersonality'=>$primaryPersonality,'secondaryPersonality'=>$secondaryPersonality,'combinedPersonality'=>$combinedPersonality,'primaryPercentage'=>$primaryPercentage,'secondaryPercentage'=>$secondaryPercentage);
    }

    public function getUsers( $orderBy='id', $order='asc')
    {

        /* This function returns information about all the users
        * Name
        * age
        * id
        * gender
        * etc
        ----------------------------------*/
        $users['total'] = User::count();
        $users['male'] =  Profile::where('gender', 'LIKE', 'm%')->count();
        $users['female'] =  Profile::where('gender', 'LIKE', 'f%')->count();

        /*-- Get user Info --*/
        $users['info'] = Profile::with(array(
            'like'=>function($query)
            {
                $query->select('userAId', 'date')->whereIn('isLike', ['3', '5']);
            },
            'user'=>function($query)
            {
                $query->select('firstName', 'lastName', 'id', 'status', 'isApproved');

            }))
            // ->orderBy($orderBy , $order)
            // ->get(['gender', 'age', 'currentCity', 'userId', 'lastActiveDate', 'currencyUsed', 'currencyPurchased', 'iAm', 'created_at'])
            ->select('gender', 'age', 'currentCity', 'userId', 'lastActiveDate', 'currencyUsed', 'currencyPurchased', 'iAm', 'created_at')
            ->orderBy($orderBy, $order)
            ->paginate(100); // use this code if we want to paginate




        $users['info'] = $users['info']->toArray();

        /*-- Change the value of total mutual likes--*/
        foreach($users['info']['data'] as &$user_info)
        {
            $user_info['daysToLastML'] = ($user_info['like']) ? floor((time() -strtotime($user_info['like'][0]['date']))/(60*60*24)) : null;
            $user_info['registered'] = ($user_info['lastActiveDate'] != "0000-00-00 00:00:00") ? floor((time() -strtotime($user_info['lastActiveDate']))/(60*60*24)) : "not active";
            $user_info['like'] = count($user_info['like']);

        }



        return json_encode(['code' => 200, 'message' => 'success', 'response' => $users]);


    }

    public function getCities()
    {
        /* this function gets all the cities from the database */
        $result = Profile::select('currentCity')->distinct()->get();
        if($result)
            return json_encode(['code' => 200, 'message' => 'success', 'response' => $result]);
        else
            return json_encode(['code' => 500, 'message'=> 'internal server error']);

    }

    public function getNames(Request $request){
        // $input = $request->all();
        $data = User::select('firstName', 'lastName', 'id')->where('firstName', 'LIKE', str_replace('"', '', $request->value).'%')->orWhere('lastName', 'LIKE', str_replace('"', '', $request->value).'%')->get();
        if($data)
            return json_encode(['code'=> 200, 'message'=> 'success', 'response'=> $data]);
        else
            return json_encode(['code'=> 500, 'message'=> 'Internal service error']);

    }



    public function getColleges(Request $request)
    {
        /* This function gets all the colleges and there scores */
        $input = $request->all();
        $data = College::select('collegeName', 'collegeScore')->where('collegeName', 'LIKE', str_replace('"', '', $input['collegeName']).'%')->get();
        if($data)
            return json_encode(['code' => 200, 'response' => $data]);
        else
            return json_encode(['code' => 500, 'message' => 'internal server error']);
    }

    public function updatePhotoScore($id, $score)
    {
        /* this function upates the users photo score */
        $result = Profile::select('photoScore')->where('userId', $id)->first();
        if(!empty($result))
            Profile::where('userId', $id)->update(['photoScore' => $score]);

        else
            Profile::insert(['photoScore' => $score])->where('userId', $id);

        return json_encode(['code' => 200, 'message' => 'success']);
    }

    public function updateCollegeScore($id, $score)
    {

            /* This function updates users college score */
            $result = Profile::select('college')->where('userId', $id)->first();
            if(empty($result))
                return json_encode(['code' => 403, 'message'=> 'User Doesnot have a college specified']);
            else {
                Profile::where('userId', $id)->update(['collegeScore' => $score]);
                return json_encode(['code'=> 200, 'message'=> 'College score was successfully updated']);
            }
    }

    // public function getChat($aId, $bId){
    //     /* this function gets the chat messages */
    //     $result = Message::select('message', 'created_at')->where('userAId', $aId)->where('userBId', $bId)->orderBy('created_at', 'desc')->paginate(10);
    //     return json_encode(['code' => 200, 'message' => 'success', 'response'=>$result]);
    //
    // }

    public function getAllChats(Request $request){
        /* Only gets basic information of users where message type is type 0 */
        if($request->type == "1"){
            $request->type=[1];
        }
        else if($request->type == '0'){
            // means all messages
            $request->type = [0];
        }
        $result['data'] = Message::whereIn('type', $request->type)->orderBy('date', 'desc')->skip(intval($request->page) * 40)->take(40)->groupBy('userAId', 'userBId')->get(['userAId', 'userBId', 'date'])->toArray();
        // use selection sort to delete dublicates
        for($i = 0;  $i < count($result['data'])-1; $i++){
            for($j = $i + 1; $j < count($result['data']) -1; $j++){
                if(($result['data'][$i]['userAId'] == $result['data'][$j]['userBId'] && $result['data'][$i]['userBId'] == $result['data'][$j]['userAId']))
                {
                    array_splice($result['data'], $j, 1);
                }
            }
        };
        $total_pages = intval(ceil(Message::where('type', 0)->distinct()->get(['userAId'])->count() / 20));
        $result['nextPage'] =  ($total_pages - 1 > intval($request->page)) ? intval($request->page) + 1 : null;
        $result['prevPage'] = (intval($request->page) != 0) ? intval($request->page) - 1 : null;
        if(count($result['data']) > 0){
            foreach ($result['data'] as &$user) {
                $user['message'] = Message::select('message')->whereIn('userAId', [$user['userAId'], $user['userBId']])->whereIn('userBId', [$user['userAId'], $user['userBId']])->count();
            }
            return json_encode(['code' => 200, 'message'=> 'success', 'response'=> $result]);
        }
        else {
            return json_encode(['code' => 500, 'message'=> 'internal Service error']);
        }
    }

    public function getChatInfo($Aid, $Bid, Request $request){
        /* This function gets basic chat info for 2 users and there messages*/
        // convert type into an array


        if($request->type == 1)
        {
            $request->type=[0,1];
        }
        else {
            $request->type = [0];
        }

        $data['userA'] = User::select('firstName', 'lastName', 'originalDpUrl', 'profiles.college', 'profiles.degree', 'profiles.age', 'profiles.companyName', 'profiles.currentCity', 'profiles.heightFeet','profiles.position', 'profiles.salary')->leftJoin('profiles', 'users.id', '=', 'userId')->where('profiles.userId', $Aid)->get();
        $data['userB'] = User::select('firstName', 'lastName', 'originalDpUrl', 'profiles.college', 'profiles.degree', 'profiles.age', 'profiles.companyName', 'profiles.currentCity', 'profiles.heightFeet', 'gender', 'profiles.position', 'profiles.salary')->leftJoin('profiles', 'users.id', '=', 'userId')->where('profiles.userId', $Bid)->get();
        $dpUrlMaker = function($url){
            $array = explode('/', $url);
            if(count($array) == 1 ){
                return "https://s3-us-west-2.amazonaws.com/twango/profilePhotos/" . $url;
            }
            return $url;
        };
        $data['userA'] = $data['userA']->toArray();
        $data['userB'] = $data['userB']->toArray();
        $data['userA'][0]['originalDpUrl'] = ($data['userA'][0]['originalDpUrl']) ? $dpUrlMaker($data['userA'][0]['originalDpUrl']) : null;
        $data['userB'][0]['originalDpUrl'] = $dpUrlMaker($data['userB'][0]['originalDpUrl']);

        if($request->type && !$request->getStatus){
            // only return messages if the type is also present
            $data['message']= Message::whereIn('type', $request->type)->whereIn('userAid', [$Aid, $Bid])->whereIn('userBid', [$Aid, $Bid])->get(['message', 'userAId', 'date']);

        }

        return json_encode(['code' => 200, 'message' => 'success', 'response' => $data]);
    }

    public function getStatus(Request $request){
        $result['userA'] = Like::select('isLike', 'created_at')->where('userAId', $request->Aid)->where('userBId', $request->Bid)->first();
        $result['userB'] = Like::select('isLike', 'created_at')->where('userAId', $request->Bid)->where('userBId', $request->Aid)->first();
        if($result)
            return json_encode(['code'=> 200, 'message'=> 'success', 'response'=> $result]);
        else
            return json_encode(['code' => 500, 'message'=> 'Internal Server Error']);
    }

    public function getSearchedData(Request $request){
        $input = ($request->all()) ? $request->all() : null;
        $input = json_decode($input['object']);


        /*-- Get user Info --*/
        $users['info']['data'] = Profile::with(array(
            'like'=>function($query)
            {
                $query->select('userAId', 'date')->whereIn('isLike', ['3', '5']);
            },
            'user'=>function($query) use ($input)
            {
                $query->select('firstName', 'lastName', 'id', 'status', 'isApproved')
                ->where(function($query) use ($input)
                {
                    /* we put the where clause here
                    * firstName filter, status and isAproved are coming form user Tabel
                    */
                    if($input != null){
                        if(array_key_exists('id', $input)){
                            $query->where('id', $input->id);
                        }
                        if(array_key_exists('firstName', $input)){
                            // TODO why empty data
                            $query->whereRaw('concat(firstName, " ", lastName) = ?', [$input->firstName]);
                        }
                        else if(array_key_exists('status', $input) && !array_key_exists('isApproved', $input)){
                            $query->whereIn('status', $input->status);
                        }
                        else if (array_key_exists('isApproved', $input) && !array_key_exists('status', $input)){
                            $query->whereIn('isApproved', $input->isApproved);
                        }
                        else if(array_key_exists('isApproved', $input) && array_key_exists('status', $input)){
                            $query->whereIn('status', $input->status)->whereIn('isApproved', $input->isApproved);
                        }
                    }
                    // $query->whereIn('status', [1]);

                });
            }))
            ->select('gender', 'age', 'currentCity', 'userId', 'lastActiveDate', 'currencyUsed', 'currencyPurchased', 'iAm', 'created_at')
            ->where(function($query) use ($input)
            {
                if(array_key_exists('id', $input)){
                    $query->whereIn('userId', [$input->id]);
                }


                else if(array_key_exists('age', $input) && array_key_exists('currentCity', $input)){
                    foreach ($input->currentCity as $city) {
                        $query->where('currentCity', 'LIKE', '%'.$city.'%');
                    }
                    $query->where('age', $input->age[0]);
                }
                else if(array_key_exists('age', $input)){
                    $query->where('age', $input->age[0]);
                }
                else if(array_key_exists('currentCity', $input) && !array_key_exists('gender', $input))
                {
                    foreach ($input->currentCity as $city) {
                        $query->orWhere('currentCity', 'LIKE', '%'.$city.'%');
                    }

                }
                else if(!array_key_exists('currentCity', $input) && array_key_exists('gender', $input))
                {
                    $query->where('gender', 'LIKE', $input->gender[0].'%');
                }
                else if(array_key_exists('currentCity', $input) && array_key_exists('gender', $input))
                {

                    $query->where('currentCity', 'LIKE', '%'.$input->currentCity[0].'%')->where('gender', 'LIKE', $input->gender[0].'%');
                }
            })
            ->get()
            ->reject(function($value){
                /* function will reject the value wehre user are null */
                return $value->user == null;
            });


            $users['info']['data'] = $users['info']['data']->toArray();

        /*-- Change the value of total mutual likes--*/
        foreach($users['info']['data'] as &$user_info)
        {
            $user_info['daysToLastML'] = ($user_info['like']) ? floor((time() -strtotime($user_info['like'][0]['date']))/(60*60*24)) : null;
            $user_info['registered'] = ($user_info['lastActiveDate'] != "0000-00-00 00:00:00") ? floor((time() -strtotime($user_info['lastActiveDate']))/(60*60*24)) : "not active";
            $user_info['like'] = ($user_info['like']) ? count($user_info['like']) : 0;

        }



        return json_encode(['code' => 200, 'message' => 'success', 'response' => $users]);

    }

    public function singleUser($id)
    {
        /* This function takes in one arguments the name and id of the user
        * use id to query the likes table and get information about who liked who
        * also gets the reason for liking
        */

        // get the likes information
        $data['liked_by'] = Like::leftJoin('users', 'users.id', '=', 'likes.userBId')->select('users.firstName', 'users.lastName', 'likes.isLike', 'likes.reason', 'likes.date', 'likes.userBId')->where('userAId', $id)->orderBy('likes.date', 'asc')->get();

        foreach ($data['liked_by'] as &$likes)
        {
                if($likes['isLike'] == 1)
                    $likes['isLike'] = 'Dislike';
                else if($likes['isLike'] == 2)
                    $likes['isLike'] = 'Like';
                else if($likes['isLike'] == 3)
                    $likes['isLike'] = 'Connected';
                else if($likes['isLike'] == 4)
                    $likes['isLike'] = 'Sent Message';
                else if($likes['isLike'] == 5)
                    $likes['isLike'] = 'UnMatch';
                else if($likes['isLike'] == 6)
                    $likes['isLike'] = 'Got Unmatched';
                else if($likes['isLike'] == 0)
                    $likes['isLike'] = 'Seen';
        }

        //get users name
        $data['user_name'] = User::leftJoin('profiles', 'profiles.userId', '=', 'users.id')->select('firstName', 'lastName', 'heightFeet', 'religion', 'relationshipStatus',
         'users.profileUrl', 'companyName', 'position', 'degree', 'age', 'gender', 'salary', 'currentCity', 'college', 'interests', 'users.id', 'dpUrl', 'collegeScore')->where('users.id', $id)->first();
        $data['user_name'] = $data['user_name']->toArray();
        $array = explode('/', $data['user_name']['dpUrl']);
        if(count($array) == 1 ){
            $data['user_name']['dpUrl'] = "https://s3-us-west-2.amazonaws.com/twango/profilePhotos/" . $data['user_name']['dpUrl'];
        }


        return json_encode($data, JSON_PRETTY_PRINT);
    }


       public function getActivityData(Request $request){
       $date = null;

       if(getType($request->info) == 'string')
           $request->info = json_decode($request->info);

       if(!$request->info){
           // if we ever come across a condition where user doesnt have the period specified
           $request->info = new \Illuminate\Database\Eloquent\Collection;
           $request->info->period = 'all';
       }




       if(array_key_exists('period', $request->info)){
           // get the date based on parameters
           if($request->info->period == "today"){
               $date = [date('Y-m-d'), date('Y-m-d')];

           }
           else if($request->info->period == "yesterday"){
               $date = [date('Y-m-d',strtotime("-1 days")), date('Y-m-d',strtotime("-1 days"))];

           }
           else if($request->info->period == "lastWeek"){
               $start_week = strtotime("last monday midnight");
               $end_week = strtotime("+1 week",$start_week);

               $start_week = date('Y-m-d',$start_week);
               $end_week = date('Y-m-d',$end_week);
               $date = [$start_week, $end_week];

           }
           else if($request->info->period == 'lastMonth'){
               $date = [date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month'))];
           }
           else if($request->info->period == 'all'){
               $date = [Like::min('date'), Like::max('date')];
           }
           else {
               // $request->info->period = json_decode($request->info->period);
               $date = [$request->info->period->start, $request->info->period->end];
           }
       }

       $request->page = json_decode($request->page);

       /* -- query --*/
       $result['data'] = Like::with([
           'profile'=> function($query) use ($request){
               if(array_key_exists('gender', $request->info)){
                   $query->select('gender', 'userId')->where('gender', 'Like', $request->info->gender.'%');
               }
               else {
                   $query->select('gender', 'userId');
               }


           }
           ])->orderBy('date', 'dsc')->whereBetween('date', $date)->skip(40 * $request->page)->take(40)
       ->where(function($query) use ($request){
           if(array_key_exists('userA', $request->info) || array_key_exists('userB', $request->info) ||  array_key_exists('isLike', $request->info)){
               if(array_key_exists('userA', $request->info)){
                   $query->where('userAId', $request->info->userA);
               }
               if(array_key_exists('userB', $request->info)){
                   $query->where('userBId', $request->info->userB);
               }
               if(array_key_exists('isLike', $request->info) && count($request->info->isLike)){
                   $query->whereIn('isLike', $request->info->isLike);
               }

           }
           else{
               $query->where('id', '>', 0);
           }
       } )
       ->get(['userAId', 'userBId', 'isLike', 'date']);


       $collection = collect($result['data']);
       $result['data']= $collection->reject(function($value, $key){
           return $value['profile'] == null;
       });
       // pagination
       $result['total_pages'] = ceil(Like::count() / 40);
       $result['next_page'] = ($request->page < $result['total_pages'])  ? $request->page + 1 : null;
       $result['prev_page'] = ($request->page > 0) ? $request->page - 1: null;
       foreach ($result['data'] as &$likes)
       {
           $likes['isLikeB_A'] = Like::where('userAId', $likes['userBId'])->where('userBId', $likes['userAId'])->get(['isLike']);
           $likes['compat'] = Compatibility::where('userAId', $likes['userAId'])->where('userBId', $likes['userBId'])->get(['compatibilityAB'])->toArray();
       }
       return json_encode(['code'=> 200, 'message' => 'success', 'response' => $result]);
      }


       public function getStackData(Request $request){
           if($request->searchParams){
              $request->searchParams = intval($request->searchParams);
              $data= Compatibility::where('userAId', $request->searchParams)->where('interactionAB', '>', 0)->where('profileShown', 0)->get(['userBId']);
              $data = $data->toArray();
              foreach($data as &$result){
                  $result = Profile::with([
                      'compatibilities'=>function($query) use ($request, $result){
                          $query->select('compatibilityAB', 'userBId')->where('userAId', $request->searchParams)->where('userBId', $result['userBId'])->get();
                      },
                      'user'=> function($query) use ($result){
                          $query->select('firstName', 'lastName', 'id')->where('id', $result['userBId'])->get();
                      },
                      ])->where('userId', $result['userBId'])->get(['gender', 'age', 'currentCity', 'currencyPurchased', 'currencyUsed', 'lastActiveDate', 'created_at', 'userId']);
                      unset($result['userBId']);
              };

                    return response()->json(['code'=> 200, 'message'=> 'Successfull', 'response'=> $data]);
            //   else
            //       return response()->json(['code'=> 500, 'message'=>'Internal Server Error']);


           }
       }




}
