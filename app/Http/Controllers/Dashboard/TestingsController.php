<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\InvoicesController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Response;
use PDF;
use Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Ro_locations;
use App\Models\Route_lists;
use App\Models\Testings;
use App\Models\Testing_meta;
use App\Models\Testing_questions;
use App\Models\Pictures;

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
        
        if ( $role < 7 ) {

            /*return [
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
            ];*/
            
            return Testing_questions::all();
        }

        return response()->json(['message' => 'Access Denied'], 401);
    }
    
    // edit questions
    function edit_questions(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 1 ) {
            for ($i = 1; $i <= 18; $i++) {
                $questionText = $request->input("q_$i");
        
                $question = Testing_questions::find($i);
        
                if ($question) {
                    $question->question = $questionText;
                    $question->save();
                }
            }
            
            return redirect()->back()->with('success', 'Questions updated successfully!');
        }

        return redirect()->back()->with('error', 'Access Denied.');
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
        $fltCompany = request('company', '');
        $role = auth()->user()->role;
        $auth_id = auth()->user()->id;
        
        if(request()->has('questions')){
            $questions = $this->ques_list();

            return view('dashboard', compact('questions'));
        }

        if(request()->has('pdf') && request()->filled('pdf')){
            $testing = Testings::find(request()->input('pdf'));

            if(!$testing || ($testing && $role > 5 && $auth_id != $testing->cus_id)){
                return abort(404);
            }

            $answers = $testing->testing_meta()->orderBy('ques_id')->get();

            return $this->testing_pdfGen($testing, $answers);
        }
        
        if(request()->has('edit') && request()->filled('edit')){
            $testing = Testings::with('testing_meta')->find(request()->input('edit'));

            if(!$testing){
                return abort(404);
            }
            
            $meta = $testing->testing_meta()->orderBy('ques_id')->get();
            $questions = $this->ques_list();

            return view('dashboard', compact('testing', 'meta', 'questions'));
        }
        
        if ( $role < 5 ) {

            $testings = Testings::where('status', 'completed');

        } elseif ( $role == 5 ) {

            $testings = auth()->user()->tech_testings()->where('status', 'completed');

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
        
        $testings = $testings->whereHas('customer', function ($query) use ($fltCompany) {
            $query->where('com_to_inv', $fltCompany);
        });

        $testings = $testings->orderBy('created_at', 'desc')->get();
        $testings = paginateCollection($testings, $perPage, $page);

        return view('dashboard', compact('testings'));

    }
    
    
    // edit survey
    function edit_survey(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role < 3 ) {
            $testing = Testings::find($request->input("id"));
            
            if($testing){
                $testing->gen_comment = $request->input("gen_comment");
                $testing->updated_at = Carbon::now('America/Chicago');
                $testing->save();
            } else {
                return abort(404);
            }
            
            for ($i = 1; $i <= 18; $i++) {
                $meta = Testing_meta::where('testing_id', $request->input("id"))->where('ques_id', $i)->first();
                $answer = $request->input("q_$i");
                $desc = $request->input("des_$i");
                
                if($meta){
                    $meta->answer = $answer;
                    $meta->desc = $desc;
                    $meta->updated_at = Carbon::now('America/Chicago');
                    $meta->save();
                } else {
                    Testing_meta::Create([
                        'testing_id' => $testing->id,
                        'ques_id' => $i,
                        'answer'=> $answer,
                        'desc' => $desc,
                        'created_at' => Carbon::now('America/Chicago'),
                        'updated_at' => Carbon::now('America/Chicago')
                    ]);
                }
            }
            
            $answers = $testing->testing_meta()->orderBy('ques_id')->get();
            $this->testing_pdfGen($testing, $answers);
        
            return redirect()->back()->with('success', 'Survey updated successfully!');

        }

        return redirect()->back()->with('error', 'Access Denied.');
    }
    
    
    // get testings list
    function tests_list(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            $list_id = $request->input('list_id');
            $cus_id = $request->input('cus_id');
            
            $testing = Testings::where('route_list_id', $list_id)->where('cus_id', $cus_id)->first();
            if($testing){
                $survey = $testing->testing_meta;
                if (isset($survey[17])) {
                    $survey[17]['gen_comment'] = $testing->gen_comment;
                }
            } else {
                $survey = [];
            }
        
            return $survey;

        }

        return response()->json(['message' => 'Access Denied'], 401);
    }
    
    // get store testings
    function store_testings(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {
            $cus_id = $request->input('cus_id');
            
            $testings = Testings::where('cus_id', $cus_id)->where('status', 'completed')->orderBy('id', 'desc')->get()->map(function ($item) {
                $item->pdf_link = url("testing-docs/$item->id/" . $item->id . '.pdf?v=' . Str::random(3));
                return $item;
            });
        
            return $testings;

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
        
        if ( $role == 4 || $role == 5 ) {
            
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
                'created_at' => Carbon::now('America/Chicago'),
                'updated_at' => Carbon::now('America/Chicago')
            ];

            $testing = Testings::forceCreate($new_testing_data);
        
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
        
        if ( $role == 4 || $role == 5 ) {

            $testing_id = $request->input('unique_id');
            $testing = Testings::find($testing_id);

            if($testing){
                if(!$request->input('answer') || $request->input('answer') == ''){
                    return response()->json(['message' => 'Answer Not Found'], 404);
                }

                $ques_id = $request->input('ques_id');
                $current_meta = Testing_meta::where('testing_id', $testing->id)->where('ques_id', $ques_id)->first();

                $new_testing_meta = [
                    'testing_id' => $testing->id,
                    'ques_id' => $ques_id,
                    'answer' => $request->input('answer'),
                    'desc' => $request->input('desc'),
                    'updated_at' => Carbon::now('America/Chicago')
                ];

                if($current_meta){
                    $current_meta->fill($new_testing_meta);
                    $current_meta->save();
                    $testing_meta = $current_meta;
                } else {
                    $new_testing_meta['created_at'] = Carbon::now('America/Chicago');
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

                    $new_picture = [
                        'route_list_id' => $testing->route_list_id,
                        'cus_id' => $testing->customer->id,
                        'type' => 'picture',
                        'image' => "/testing-docs/$testing_id/" . $file_name,
                        'created_at' => Carbon::now('America/Chicago'),
                        'updated_at' => Carbon::now('America/Chicago')
                    ];
                    $picture = Pictures::create($new_picture);
                }
				
				if($ques_id == 18){
				    if(request()->input('action') !== 'save'){
                        $testing->status = 'completed';
				    }
                    $testing->gen_comment = $request->input('gen_comment');
                    $testing->tech_id = auth()->user()->id;
                    $testing->updated_at = Carbon::now('America/Chicago');
                    $testing->save();
                    
                    $answers = $testing->testing_meta()->orderBy('ques_id')->get();
                    $this->testing_pdfGen($testing, $answers);
                    
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
                            $today = Carbon::now('America/Chicago')->format('Y-m-d');
                            $route_list->comp_date = $today;
                            $route_list->save();
                        }
                    }
                    
                    if($testing->status == 'completed' && request()->input('action') !== 'save'){
                        // send email -
                        if($testing->customer->email){
                            $data = [
                                "email" => $testing->customer->email,
                                "title" => "PetroTank - Monthly Inspection Report",
                                "customer_name" => $testing->customer->name,
                                "inspection_date" => $testing->updated_at->format('m-d-Y'),
                            ];
                     
                            $unique_id = $testing->id;
                            $directoryPath = public_path("testing-docs/$unique_id/");
                            $filePath = $directoryPath . $unique_id . '.pdf';
                            $files = [
                                $filePath,
                            ];
                      
                            Mail::send('emails.inspectionCompleted', $data, function($message) use ($data, $files) {
                                $message->to($data["email"], $data["email"])
                                        ->subject($data["title"]);
                     
                                foreach ($files as $file){
                                    $message->attach($file);
                                }
                                
                            });
                        }
                        // - send email
                    }
                }

                $testing_meta = $testing_meta->toArray();
                $testing_meta['unique_id'] = $testing_id;
                $testing_meta['gen_comment'] = $ques_id == 18 ? $testing->gen_comment : '';
                $testing_meta['location_status'] = $testing->status;
                
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
        /*if (file_exists($filePath)) {
            if(request()->has('download')){
                return Response::download($filePath);
            } else {
                return redirect( url("testing-docs/$unique_id/" . $unique_id . '.pdf') );
            }            
        }*/

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
        } elseif(!request()->is('api/testing*')){
            return redirect( url("testing-docs/$unique_id/" . $unique_id . '.pdf?v=' . Str::random(3)) );
        }

    }


    public function inv_amount(Request $request)
    {
        $role = auth()->user()->role;

        if ( $role == 4 || $role == 5 ) {

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
        
        if ( $role == 4 || $role == 5 ) {

            $route_list = Route_lists::find($request->input('list_id'));
            
            if(!$route_list){
                $request->merge(['customer_id' => $request->input('cus_id')]);
                $controller = new InvoicesController();
                return $controller->editInvoice($request);
            }
            
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
                'date' => Carbon::now('America/Chicago')->format('Y-m-d'),
                'customer_id' => $request->input('cus_id'),
                'service' => null,
                'payment' => null,
                'pay_opt' => $request->input('pay_opt'),
                'check_no' => $request->input('check_no'),
                'mo_no' => $request->input('mo_no'),
                /*'invoice_items' => json_encode([[
                        'category' => $request->input('category'),
                        'descript' => ,
                        'qty' => null,
                        'rate' => null,
                        'amount' => $amount,
                ]]),*/
                'items' => json_encode($items),
                'addi_comments' => $request->input('addi_comments'),
                'service' => $request->input('service'),
                'id' => $request->input('id'),
            ]);

            $controller = new InvoicesController();
            return $controller->add($request);

        } else {

            return response()->json(['message' => 'Access Denied'], 401);

        }

    }

}
