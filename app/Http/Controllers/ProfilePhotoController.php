<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Support\JsonableInterface;
use App\ProfilePhoto;
use App\User;
use App\Profile;
use App\Guy;
use App\Girl;
use App\Http\Controllers\Controller;
// use JWTAuth;
use Paginator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Storage;
use App\Jobs\CalculateCompatibility;
use App\Jobs\UploadProfilePhoto;
use Image;
use App\Email;
use Mail;
use Redis;
use GuzzleHttp\Client;
class ProfilePhotoController extends Controller
{

   public function __construct()
   {
       // Apply the jwt.auth middleware to all methods in this controller
       // except for the authenticate method. We don't want to prevent
       // the user from retrieving their token if they don't already have it
       $this->middleware('jwt.auth',['except' => ['test']]);
   }

  public function postProfilePhoto(Request $request)
  {
      //return response()->json(['message'=>'success','code'=>200]);
      $token=JWTAuth::getToken();
      $user = JWTAuth::toUser($token);
      if($request->get('uploadFrom')=="facebook")
      {
          return $this->_uploadFromFacebook($user,$request);
      }
      if($request->get('uploadFrom')=="normal")
      {
          return $this->_uploadFromNormal($user,$request);
      }
      if($request->get('uploadFrom')=="gallery")
      {
        //return response()->json(['message'=>'sucddddcess','code'=>200,'response'=>$request->input('name')]);
        return $this->_uploadFromGallery($user,$request);
      }
      if($request->get('uploadFrom')=="url")
      {
        return $this->_uploadFromUrl($user,$request);
      }
  }

  public function makeDp($dpId,Request $request)
    {
      $token=JWTAuth::getToken();
      $user = JWTAuth::toUser($token);
        $id=$user->id;
      try
      {
        $dp = ProfilePhoto::where('userId',$id)->where('isDp',0)->first(['id']);
        if($dp){
          ProfilePhoto::where('id',$dp['id'])->update(['isDp'=>0]);
        }
        ProfilePhoto::where('userId',$id)->where('id',$dpId)->update(['isDp'=>1]);
        User::where('id',$id)->update(['dpUrl'=>ProfilePhoto::where('userId',$id)->where('id',$dpId)->first(['imgUrl'])['imgUrl']]);
        return response()->json(['message'=>'Done!','code'=>200,'response'=>'']);
      }
      catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed!','code'=>402,'response'=>'']);
        }
    }

    public function updateProfilePhoto($photoId,Request $request){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if($request->get('verify')=="true")
        {
          return $this->makeVerified($photoId,$request);
        }
        if($request->get('makeDp')=="true")
        {
          return $this->makeDp($photoId,$request);
        }
        if($request->get('updatePhoto')=="true"){
          return $this->updatePhoto($photoId,$request,$user);
        }
    }

  public function makeVerified($dpId, $request)

    {
      try
      {
        ProfilePhoto::where('id',$dpId)->update(['isVerified'=>1]);
        return response()->json(['message'=>'success','code'=>200,'response'=>'']);
      }
      catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed!','code'=>402,'response'=>'']);
        }
    }
  public function getProfilePhotos($userId, Request $request)
   {
    // $token = JWTAuth::getToken();
        // $user = JWTAuth::toUser($token);
       try{
          $photos=ProfilePhoto::where('userId',$userId)->get(['id','imgUrl','isDp']);
          for($i=0;$i<sizeof($photos);$i++){
            $photos[$i]->imgUrl = 'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$photos[$i]->imgUrl;
        }
          return  response()->json(['code'=>200,'message'=>"success",'response'=>$photos])->header("Access-Control-Allow-Origin", "*");
           }
       catch(Exception $e){
          return response()->json(['code'=>500,'message'=>"Internal Server Error",'response'=>""]);
           }
    }

    public function getProfilePhotoByUserId(Request $request){
      $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
       try{
          $photos=ProfilePhoto::where('userId',$request->get('userId'))->get(['id','imgUrl','isDp']);
          for($i=0;$i<sizeof($photos);$i++){
            $photos[$i]->imgUrl = 'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$photos[$i]->imgUrl;
          }
          return  response()->json(['code'=>200,'message'=>"success",'response'=>$photos]);
           }
       catch(Exception $e){
          return response()->json(['code'=>500,'message'=>"Internal Server Error",'response'=>""]);
           }
    }
    public function getProfilePhoto($id,Request $request)
    {
      try{
          $photo=ProfilePhoto::where('id',$id)->first(['imgUrl']);
          $photo->imgUrl = 'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$photo->imgUrl;
          return  response()->json(['code'=>200,'message'=>"success",'response'=>$photo]);
           }
       catch(Exception $e){
          return response()->json(['code'=>500,'message'=>"Internal Server Error",'response'=>""]);
           }
     }
     public function deleteProfilePhoto($id,Request $request)
     {
      try{
          $photo=ProfilePhoto::where('id',$id)->first(['imgUrl']);
          if($photo){
            ProfilePhoto::where('id',$id)->delete();
            if(Storage::has('profilePhotos/'.$photo['imgUrl'])){
              Storage::delete('profilePhotos/'.$photo['imgUrl']);
            }
          }
          return  response()->json(['code'=>200,'message'=>"success",'response'=>'']);
           }
       catch(Exception $e){
          return response()->json(['code'=>500,'message'=>"Internal Server Error",'response'=>""]);
           }
     }


    private function _uploadFromFacebook($user, $request){
      $id=$user->id;
      $profile = Profile::where('userId',$id)->first(['isApproved']);
      //$user = User::where('id',$id)->first();
      $status = $user->status;
      $smallUrls = $request->get('smallUrls');
      $smallUrlsArray = explode(',', $smallUrls);
      $largeUrls = $request->get('largeUrls');
      $largeUrlsArray = explode(',', $largeUrls);
      if(sizeof($smallUrlsArray))
      {
        $data = array();
        $response = array();
        for($i=0;$i<sizeof($smallUrlsArray);$i++){
          $smallImageFileName = $id.'_'.time().'_'.rand().'_small.jpg';
          $largeImageFileName = $id.'_'.time().'_'.rand().'_large.jpg';
          $photo = ProfilePhoto::create(array(
                'userId'=>$id,'smallImgUrl'=>$smallImageFileName,'largeImgUrl'=>$largeImageFileName,'isDp'=>0,'isVerified'=>0));
          $response[] = array('smallImgUrl'=>$smallImageFileName,'largeImgUrl'=>$largeImageFileName,'smallFbUrl'=>$smallUrlsArray[$i],'largeFbUrl'=>$largeUrlsArray[$i],'id'=>$photo->id);
        }
        $this->dispatch(new UploadProfilePhoto($response));
        try{
          if($status==4){
            User::where('id',$id)->update(['status'=>5,'dpUrl'=>$response[0]['smallImgUrl'],'originalDpUrl'=>$response[0]['largeImgUrl']]);
            ProfilePhoto::where('id',$response[0]['id'])->update(['isDp'=>1]);
            if($user->isApproved==1){
            $a = User::with(
                array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','salaryScore','collegeScore','photoScore','interests','cityLatitude','cityLongitude','userId');},
                'psychometric' =>function($query){
                $query->select('typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId');
                }))->where('id',$id)->first(array('id','token','uid'));
            $this->dispatch(new CalculateCompatibility($a));

            if($a->profile->gender=='Male'){
            Guy::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }else{
              Girl::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }
            Profile::where('userId',$id)->update(['isApproved'=>1]);
            }else if($user->isApproved==2){
              User::where('id',$id)->update(['isApproved'=>0]);
            }
            return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>5)]);
          }
          return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>$status)]);
        }
        catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed','code'=>402,'response'=>'']);
        }
      }
      else
      {
        return response()->json(['message'=>'No Photo','code'=>404,'response'=>'']);
      }
    }

    private function _uploadFromGallery($user, $request){
      $image = $request->file('image');
      $id=$user->id;
      $status = $user->status;
      if($image)
      {
        $imageFileName = $id.'_'.time().'_'.rand().'.'.$image->getClientOriginalExtension();
        $s3 = Storage::disk('s3');
        $filePath = '/profilePhotos/'.$imageFileName;
        $s3->put($filePath, file_get_contents($image), 'public');
        try{
          $photo = ProfilePhoto::create(['userId'=>$id,'imgUrl'=>$imageFileName,'isDp'=>0,'isVerified'=>0]);
          return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>array('imgUrl'=>'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$imageFileName,'id'=>$photo->id),'status'=>$status)]);
        }
        catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed','code'=>402,'response'=>'']);
        }
      }
      else
      {
        return response()->json(['message'=>'No Photo','code'=>404,'response'=>'']);
      }
    }

    public function updatePhoto($photoId, $request, $user){
      $photoOld=ProfilePhoto::where('id',$photoId)->first(['imgUrl','isDp']);
      $image = $request->file('image');
      $id=$user->id;
      $status = $user->status;
      if($request->hasFile('image'))
      {
        $imageFileName = $id.'_'.time().'_'.rand().'.'.$image->getClientOriginalExtension();
        $s3 = Storage::disk('s3');
        $filePath = '/profilePhotos/'.$imageFileName;
        $s3->put($filePath, file_get_contents($image), 'public');
        if($photoOld){
            if(Storage::has('profilePhotos/'.$photoOld['imgUrl'])){
              Storage::delete('profilePhotos/'.$photoOld['imgUrl']);
            }
          }
        try{
          if($photoId==0){
            $photo = ProfilePhoto::create(array('userId'=>$id,'imgUrl'=>$imageFileName,'isDp'=>0,'isVerified'=>0));
          }else{
            $photo = ProfilePhoto::where('id',$photoId)->update(['imgUrl'=>$imageFileName,'isVerified'=>0]);
            if($photoOld->isDp=='1'){
              User::where('id',$id)->update(['dpUrl'=>$imageFileName]);
            }
          }
          return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>array('imgUrl'=>'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$imageFileName,'id'=>$photoId),'status'=>$status)]);
        }
        catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed','code'=>402,'response'=>'']);
        }
      }
      else
      {
        return response()->json(['message'=>'No Photo','code'=>403,'response'=>'']);
      }
  }

  private function _uploadFromUrl($user, $request){
      $id=$user->id;
      $profile = Profile::where('userId',$id)->first(['isApproved']);
      $status = $user->status;
      $urls = $request->get('urls');
      $urlsArray = explode(',', $urls);
      if(sizeof($urlsArray))
      {
        $data = array();
        $response = array();
        $upload = array();
        for($i=0;$i<sizeof($urlsArray);$i++){
          if(!(strpos($urlsArray[$i], 'https:\/\/s3-us-west-2.amazonaws.com\/') !== false)){
            $imageFileName = $id.'_'.time().'_'.rand().'.jpg';
            //$imageFileName = $urlsArray[$i];
            $photo = ProfilePhoto::create(array(
                'userId'=>$id,'imgUrl'=>$imageFileName,'isDp'=>0,'isVerified'=>0));
            $upload[] = array('imgUrl'=>$imageFileName,'fbUrl'=>$urlsArray[$i],'id'=>$photo->id);
            $response[] = array('imgUrl'=>'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$imageFileName,'id'=>$photo->id);
          }else{
            $trimmed = str_replace('https:\/\/s3-us-west-2.amazonaws.com\/twango\/profilePhotos\/', '', $urlsArray[$i]);
            $photo = ProfilePhoto::where('imgUrl',$trimmed)->first(['id','imgUrl']);
            if($photo){
              $response[] = array('imgUrl'=>'https://s3-us-west-2.amazonaws.com/twango/profilePhotos/'.$photo->imgUrl,'id'=>$photo->id);
          }
          }
        }
        $this->dispatch(new UploadProfilePhoto($upload));
        try{
          if($status==4){
            $trimmed = str_replace('https://s3-us-west-2.amazonaws.com/twango/profilePhotos/', '', $response[0]['imgUrl']);
            User::where('id',$id)->update(['status'=>5,'dpUrl'=>$trimmed,'originalDpUrl'=>$trimmed]);
            ProfilePhoto::where('id',$response[0]['id'])->update(['isDp'=>1]);
            if($user->isApproved==1){
            $a = User::with(
                array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','salaryScore','collegeScore','photoScore','interests','cityLatitude','cityLongitude','userId');},
                'psychometric' =>function($query){
                $query->select('typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId');
                }))->where('id',$id)->first(array('id','token','uid'));
            $this->dispatch(new CalculateCompatibility($a));

            if($a->profile->gender=='Male'){
            Guy::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }else{
              Girl::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }
            Profile::where('userId',$id)->update(['isApproved'=>1]);
            }else if($user->isApproved==2){
              User::where('id',$id)->update(['isApproved'=>0]);
            }
            return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>5)]);
          }
          return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>$status)]);
        }
        catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed','code'=>402,'response'=>'']);
        }
      }
      else
      {
        return response()->json(['message'=>'No Photo','code'=>404,'response'=>'']);
      }
    }


  private function _uploadFromNormal($user, $request){
      $id=$user->id;
      $profile = Profile::where('userId',$id)->first(['isApproved']);
      $user = User::where('id',$id)->first();
      $status = $user->status;
      $smallUrls = $request->get('smallUrls');
      $smallUrlsArray = explode(',', $smallUrls);
      $largeUrls = $request->get('largeUrls');
      $largeUrlsArray = explode(',', $largeUrls);
      if(sizeof($smallUrlsArray))
      {
        $data = array();
        $response = array();
        for($i=0;$i<sizeof($smallUrlsArray);$i++){
          //$smallImageFileName = $id.'_'.time().'_'.rand().'_small.jpg';
          //$largeImageFileName = $id.'_'.time().'_'.rand().'_large.jpg';
          $photo = ProfilePhoto::create(array(
                'userId'=>$id,'smallImgUrl'=>$smallUrlsArray[$i],'largeImgUrl'=>$largeUrlsArray[$i],'isDp'=>0,'isVerified'=>0));
          $response[] = array('smallImgUrl'=>$smallUrlsArray[$i],'largeImgUrl'=>$largeUrlsArray[$i],'smallFbUrl'=>$smallUrlsArray[$i],'largeFbUrl'=>$largeUrlsArray[$i],'id'=>$photo->id);
        }
        //$this->dispatch(new UploadProfilePhoto($response));
        try{
          if($status==4){
            User::where('id',$id)->update(['status'=>5,'dpUrl'=>$response[0]['smallImgUrl'],'originalDpUrl'=>$response[0]['largeImgUrl']]);
            ProfilePhoto::where('id',$response[0]['id'])->update(['isDp'=>1]);
            if($user->isApproved==1){
            $a = User::with(
                array('profile'=>function($query){
                $query->select('gender','iAm','age','heightFeet','religion','hometown','currentCity','salaryScore','collegeScore','photoScore','interests','cityLatitude','cityLongitude','userId');},
                'psychometric' =>function($query){
                $query->select('typeA','valueA','typeB','valueB','typeC','valueC','typeD','valueD','typeE','valueE','userId');
                }))->where('id',$id)->first(array('id','token','uid'));
            $this->dispatch(new CalculateCompatibility($a));

            if($a->profile->gender=='Male'){
            Guy::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }else{
              Girl::insert(array(array('userId'=>$id,'reason'=>'1'),array('userId'=>$id,'reason'=>'2')));
            }
            Profile::where('userId',$id)->update(['isApproved'=>1]);
            }else if($user->isApproved==2){
              User::where('id',$id)->update(['isApproved'=>0]);
            }
            return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>5)]);
          }
          return response()->json(['message'=>'success','code'=>200,'response'=>array('photos'=>$response,'status'=>$status)]);
        }
        catch(Exception $e)
        {
          return response()->json(['message'=>'Not Allowed','code'=>402,'response'=>'']);
        }
      }
      else
      {
        return response()->json(['message'=>'No Photo','code'=>404,'response'=>'']);
      }
    }

    public function makeProfilePicture(Request $request){
        /* This function updates the users Profile Picture and returns the path*/
        $result =  User::where('id', $request->id)->update(['dpUrl' => $request->url]);
        ProfilePhoto::where('userId', $request->id)->where('isDp', '1')->update(['isDp'=> '0']);
        ProfilePhoto::where('userId', $request->id)->where('imgUrl', $request->url)->update(['isDp'=> '1']);
        if($result)
            return json_encode(['code' => 200, 'message' => 'success', 'response'=>$result]);
        else
             return json_encode(['code' => 500, 'message'=> 'Internal Server Error']);

    }

    public function saveImage(Request $request){
        /* save the image to be edited in a temp file */
        $x = explode('/', __DIR__);
        $path = [];
        for($i = 0; $i < count($x); $i++){
            if($x[$i] != 'app')
                 array_push($path, $x[$i]);
             else
                 break;
        }
        array_push($path, 'public', 'images', 'temp'. $request->ip(). '.jpg');
        $path = implode('/', $path);
        if(!file_exists($path)){
            fopen($path, 'w');
        }
        copy($request->image, $path);
        return response()->json(['code' => 200, 'message' => 'success', 'response' => $path])->header('Cache-control', 'no-cache');

    }

    public function deletePhoto(Request $request){
        $result = ProfilePhoto::where('userId', $request->id)->where('imgUrl', $request->url)->delete();
        if($result){
            if(Storage::has('profilePhotos/'.$request->url)){
              Storage::delete('profilePhotos/'.$request->url);
            }
            return json_encode(['code' => 200, 'message'=>'success']);
        }
         else
             return json_encode(['code' => 500, 'message'=>'Internal Server Error']);
    }

    public function addFilteredImage(Request $request){
        /* This function gets 3 variables
        * base_64 format of images
        * id
        * imageUrl
        * TODO -> save image to amazon webserver and delete old image
        */
        $request->image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->image));
        $request->image = imagecreatefromstring($request->image);
        $s3 = Storage::disk('s3');
        $filePath = '/profilePhotos/'.$$request->imageUrl;
        $s3->put($filePath, file_get_contents($$request->image), 'public');
        unlink($request->path);
        return json_encode(gettype($request->image));
    }}
