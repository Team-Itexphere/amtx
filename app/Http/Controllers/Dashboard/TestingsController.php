<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\InvoicesController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Response;
use PDF;

use App\Models\Ro_locations;
use App\Models\Route_lists;
use App\Models\Testings;
use App\Models\Testing_meta;

class TestingsController extends Controller
{
    // For API

    // get questions list
    function ques_list()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 || $role == 6 || $role < 3 ) {

            //return Testings::all();

            return [
                [
                    "id" => 1,
                    "question" => "Hanging hardware leaking?"
                ],
                [
                    "id" => 2,
                    "question" => "Hanging hardware free of cracks and holes?"
                ],
                [
                    "id" => 3,
                    "question" => "Octane stickers on?"
                ],
                [
                    "id" => 4,
                    "question" => "Ethanol sticker on?"
                ],
                [
                    "id" => 5,
                    "question" => "No Smoking/Stop Engine stickers on?"
                ],
                [
                    "id" => 6,
                    "question" => "TDLR Weights & Measurements stickers on?"
                ],
                [
                    "id" => 7,
                    "question" => "Any sign of leaks under the dispensers?"
                ],
                [
                    "id" => 8,
                    "question" => "Any liquid or debris under dispenser sumps?"
                ],
                [
                    "id" => 9,
                    "question" => "Are Spill buckets free of liquid or debris?"
                ],
                [
                    "id" => 10,
                    "question" => "Spill buckets interior & exterior damaged or cracked?"
                ],
                [
                    "id" => 11,
                    "question" => "Grey Fill Cap in working condition?"
                ],
                [
                    "id" => 12,
                    "question" => "Fill Adapter threaded tight and not loose?"
                ],
                [
                    "id" => 13,
                    "question" => "Orange Vapor cap in working condition?"
                ],
                [
                    "id" => 14,
                    "question" => "Vapor adapter in working condition?"
                ],
                [
                    "id" => 15,
                    "question" => "STP Sumps free of liquid and debris?"
                ],
                [
                    "id" => 16,
                    "question" => "STP Sumps free of holes and cracks?"
                ],
                [
                    "id" => 17,
                    "question" => "Are all the lids properly painted?"
                ],
                [
                    "id" => 18,
                    "question" => "Are the vent caps in good condition?"
                ]
            ];
        }

        return response()->json(['message' => 'Access Denied'], 401);
    }

    // get list
    function index(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);
        
        $s = request('s', '');
        $role = auth()->user()->role;
        $auth_id = auth()->user()->id;

        if(request()->has('pdf') && request()->filled('pdf')){
            $testing = Testings::find(request()->input('pdf'));

            if(!$testing || ($testing && $role > 3 && $auth_id != $testing->cus_id)){
                return abort(404);
            }

            $answers = $testing->testing_meta()->orderBy('ques_id')->get();

            return $this->testing_pdfGen($testing, $answers);
        }
        
        if ( $role < 4 ) {

            $testings = Testings::where('status', 'completed');

        } elseif ( $role == 6 ) {

            $testings = auth()->user()->testings()->where('status', 'completed');

        } else {
            return abort(404);
        }
        
        if(request()->has('line')){
            
            $testings = $testings->where('type', 'Annual Line & Leak');
            
        } elseif(request()->has('stage')){
            
            $testings = $testings->where('type', 'Stage 1');
            
        } elseif(request()->has('cal')){
            
            $testings = $testings->where('type', 'Calibration');
        }
        
        if($s) {
            $testings = $testings->where(function($query) use ($s) {
                $columns = \Schema::getColumnListing($query->getModel()->getTable());
    
                foreach($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $s . '%');
                }
                
                $query->orWhereHas('customer', function($customerQuery) use ($s) {
                    $customerQuery->where('name', 'like', '%' . $s . '%');
                });
                
                $query->orWhereHas('technician', function($customerQuery) use ($s) {
                    $customerQuery->where('name', 'like', '%' . $s . '%');
                });
            });
        }

        $testings = $testings->orderBy('created_at', 'desc')->get();
        $testings = paginateCollection($testings, $perPage, $page);

        return view('dashboard', compact('testings'));

    }
    
    
    // get testings list
    function tests_list(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
            $list_id = $request->input('list_id');
            $cus_id = $request->input('cus_id');
            
            $testing = Testings::where('route_list_id', $list_id)->where('cus_id', $cus_id)->first();
            if($testing){
                $survey = $testing->testing_meta;
            } else {
                $survey = [];
            }
        
            return $survey;

        }

        return response()->json(['message' => 'Access Denied'], 401);
    }


    // create servey
    function create(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {
            
            $route_list_id = $request->input('list_id');
            $ro_loc_id = $request->input('ro_loc_id');
            $current_test = Testings::where('route_list_id', $route_list_id)->where('ro_loc_id', $ro_loc_id)->first();

            if($current_test){
                return $current_test;
            }
            
            $new_testing_data = [
                'ro_loc_id' => $request->input('ro_loc_id'),
                'cus_id' => $request->input('cus_id'),
                'tech_id' => auth()->user()->id,
                'status' => 'pending',
                'type' => $request->input('type'),
                'route_list_id' => $request->input('list_id'),
            ];

            $testing = Testings::create($new_testing_data);
        
            return $testing;

        }

        return response()->json(['message' => 'Access Denied'], 401);
    }

    // fill servey
    function fill(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {

            $testing_id = $request->input('unique_id');
            $testing = Testings::find($testing_id);

            if($testing){

                $ques_id = $request->input('ques_id');
                $current_meta = Testing_meta::where('testing_id', $testing->id)->where('ques_id', $ques_id)->first();

                $new_testing_meta = [
                    'testing_id' => $testing->id,
                    'ques_id' => $ques_id,
                    'answer' => $request->input('answer'),
                    'desc' => $request->input('desc'),
                ];

                if($current_meta){
                    $current_meta->fill($new_testing_meta);
                    $current_meta->save();
                    $testing_meta = $current_meta;
                } else {
                    $testing_meta = Testing_meta::create($new_testing_meta);
                }
                
                $meta_id = $testing_meta->id;

                if($request->input('file')){

                    $fileData = $request->input('file');
                    $file = base64_decode($fileData, true);

                    $file_name = $testing_meta->ques_id . ".png";

                    $directoryPath = public_path("testing-docs/$testing_id/");

                    if (!File::exists($directoryPath)) {
                        File::makeDirectory($directoryPath, 0755, true);
                    }

                    $new_file_path = $directoryPath . $file_name;
                    
                    file_put_contents($new_file_path, $file);

                    $testing_meta->file = "/testing-docs/$testing_id/" . $file_name; 
                    $testing_meta->save();
                }
				
				if($ques_id == 18){
                    $testing->status = 'completed';
                    $testing->save();
                    
                    $route_list = $testing->route_list;
                    
                    if($route_list){
                        $route = $route_list->route;
                        $locations = $route->ro_locations;
                        
                        $completed = true;
                        foreach($locations as $loc){
                            $list_id = $route_list->id;
                            $loc_id = $loc->id;
                            
                            $pend_testing = Testings::where('route_list_id', $list_id)->where('ro_loc_id', $loc_id)->where('status', 'completed')->first();
                            
                            if(!$pend_testing){
                                $completed = false;
                                break;
                            }
                        }
                        
                        if($completed){
                            $route_list->status = 'completed';
                            $route_list->save();
                        }
                    }
                }

                $testing_meta = $testing_meta->toArray();
                $testing_meta['unique_id'] = $testing_id;
                
                if($testing_meta['id']){
                    $testing_meta['success'] = true;
                } else {
                    $testing_meta['success'] = false;
                }
                    

                return $testing_meta;

            }
        
            return response()->json(['message' => 'Unique ID Not Found'], 404);

        }

        return response()->json(['message' => 'Access Denied'], 401);
    }

    // for pdf generator
    public function testing_pdfGen($testing, $answers)
    {
        $unique_id = $testing->id;
        $directoryPath = public_path("testing-docs/$unique_id/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . $unique_id . '.pdf';
        if (file_exists($filePath)) {
            if(request()->has('download')){
                return Response::download($filePath);
            } else {
                return redirect( url("testing-docs/$unique_id/" . $unique_id . '.pdf') );
            }            
        }

        $questions = $this->ques_list();

        $data = [
            'testing' => $testing,
            'questions' => $questions,
            'answers' => $answers,
        ]; 
            
        $pdf = PDF::loadView('layouts.dashboard.testing.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        if(request()->has('download')){
            return $pdf->download($unique_id . '.pdf');
        } else {
            return redirect( url("testing-docs/$unique_id/" . $unique_id . '.pdf') );
        } 

    }


    public function inv_amount(Request $request)
    {
        $role = auth()->user()->role;

        if ( $role == 5 ) {

            $location = Ro_locations::find($request->input('ro_loc_id'));
            
            if(!$location){
                return response()->json(['message' => 'Location Not Found'], 404);
            }

            return response()->json(['amount' => $location->amount]);

        } else {

            return response()->json(['message' => 'Access Denied'], 401);

        }
    }

    public function createInvoice(Request $request)
    {
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {

            $route_list = Route_lists::find($request->input('list_id'));
            $route = $route_list->route;
            $ro_location = Ro_locations::where('route_id', $route->id)->where('cus_id', $request->input('cus_id'))->first();
            
            if($ro_location){
                $amount = $request->input('amount') ?? $ro_location->amount;
            } else {
                $amount = $request->input('amount') ?? 0;
            }
            
            $items = $request->input('items') ?? [];

            $request = new Request([
                'list_id' => $request->input('list_id'),
                'date' => date('Y-m-d'),
                'customer_id' => $request->input('cus_id'),
                'service' => null,
                'payment' => null,
                'pay_opt' => $request->input('pay_opt'),
                'check_no' => $request->input('check_no'),
                'mo_no' => $request->input('mo_no'),
                /*'invoice_items' => json_encode([[
                        'category' => $request->input('category'),
                        'descript' => $request->input('descript'),
                        'qty' => null,
                        'rate' => null,
                        'amount' => $amount,
                ]]),*/
                'items' => json_encode($items),
            ]);

            $controller = new InvoicesController();
            return $controller->add($request);

        } else {

            return response()->json(['message' => 'Access Denied'], 401);

        }

    }

}
