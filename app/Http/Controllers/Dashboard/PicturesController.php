<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Response;

use App\Models\Pictures;
use App\Models\User;

class PicturesController extends Controller
{
    // For API

    // get list
    function list(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
            
            $query = Pictures::query();
            
            if($request->input('type')){
                $query->where('type', $request->input('type'));
            }
            
            if($request->input('cus_id')){
                $query->where('cus_id', $request->input('cus_id'));
            }
            
            $pics = $query->latest()->take(10)->get();
            
            $output = [];
            $output['total'] = $query->count();
            $output['images'] = [];
            foreach($pics as $pic){
                $output['images'][] = url('') . $pic->image;
            }

            return $output;

        }

        return response()->json(['message' => 'Access Denied'], 401);
        
    }


    function upload(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
            
            if(!$request->input('image')){
                return response()->json(['message' => 'Image Not Found'], 404);
            }
            
            if(!$request->input('cus_id')){
                return response()->json(['message' => 'Customer ID Not Found'], 404);
            }
            
            $customer = User::find($request->input('cus_id'));
            
            if(!$customer){
                return response()->json(['message' => 'Customer Not Found'], 404);
            }
            
            $last_pic = Pictures::orderBy('id', 'desc')->first();
            if($last_pic){
                $next_id = $last_pic->id + 1;
            } else {
                $next_id = 1;
            }
               
            $type = $request->input('type');
              
            $fileData = $request->input('image');
            $file = base64_decode($fileData, true);

            $file_name = $next_id . ".png";

            $directoryPath = public_path("picture-uploads/$type/$customer->id/");

            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }

            $new_file_path = $directoryPath . $file_name;
                    
            file_put_contents($new_file_path, $file);

            $new_picture = [
                'route_list_id' => $request->input('list_id'),
                'cus_id' => $customer->id,
                'type' => $type,
                'image' => "/picture-uploads/$type/$customer->id/" . $file_name,
            ];
            $picture = Pictures::create($new_picture);
            
            $last_pics = Pictures::where('type', $type)->where('cus_id', $customer->id)->latest()->take(10)->get();
            
            $output = [];
            $output['total'] = Pictures::where('type', $type)->where('cus_id', $customer->id)->count();
            $output['images'] = [];
            foreach($last_pics as $pic){
                $output['images'][] = url('') . $pic->image;
            }

            return $output;

        }

        return response()->json(['message' => 'Access Denied'], 401);
        
    }

}
