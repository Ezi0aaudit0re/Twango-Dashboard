<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Notification;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Storage;

class NotificationController extends Controller
{

    private function uploadImage($image)
    {
             if($image)
             {
               $imageFileName = date('Y-m-d').$image->getClientOriginalExtension();
               $s3 = Storage::disk('s3');
               $filePath = '/notificationPhotos/'.$imageFileName;
               $s3->put($filePath, file_get_contents($image), 'public');
               if($imageFileName)
                   return $imageFileName;
             }
             else
             {
               return null;
             }
    }
        public function getData()
        {
            $result = Notification::orderBy('updated_at', 'desc')->get(['id', 'type', 'description', 'date', 'title'])->toArray();
            if($result){
                return json_encode(['code' => 200, 'message' => 'success', 'response' => $result]);
            }
            return json_encode(['code' => 500, 'message'=> 'Internal Server Error']);
        }

        public function add(Request $request)
        {
            // return json_encode($request->file('file'));
            $image = ($request->file('file')) ? ($request->file('file')) : null;
                if($image)
                {
                  $imageFileName = date('Y-m-d').$image->getClientOriginalExtension();
                  $s3 = Storage::disk('s3');
                  $filePath = '/notificationPhotos/'.$imageFileName;
                  $s3->put($filePath, file_get_contents($image), 'public');
                  if($imageFileName)
                      $image =  $imageFileName;
                }
                else
                {
                  $image = null;
                }
            $result= Notification::create(['type' => (($request->type) ? $request->type : null), 'date' => date('Y-m-d', strtotime($request->date)), 'title' => (($request->title) ? $request->title : null), 'description' => (($request->description)  ? $request->description : null), 'imgUrl' => $image]);
            if($result)
                return json_encode(['code' => 200, 'message' => 'success']);
            else
                return json_encode(['code' => 500, 'message' => 'internal server error']);
        }

        public function edit(Request $request)
        {
            $request->id = json_decode($request->id);
            $image = ($request->file('file')) ? $request->file : null;
            $result = Notification::where('id', $request->id)->update(['type' => (($request->type) ? $request->type : null), 'date' => date('Y-m-d', strtotime($request->date)), 'title' => (($request->title) ? $request->title : null), 'description' => (($request->description)  ? $request->description : null), 'imgUrl' => $image]);
            if($result)
                return json_encode(['code' => 200, 'message' => 'success']);

            return json_encode(['code' => 500, 'message' => 'internal server error']);

        }

        public function delete(Request $request)
        {
            $result = Notification::destroy($request->id);
            if($result){
                return json_encode(['code' => 200, 'message'=> 'success']);
            }
            else {
                return json_encode(['code' => 500, 'message' => 'internal Server error']);
            }
        }


}
