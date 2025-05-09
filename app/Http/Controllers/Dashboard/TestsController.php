<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Response;
use App\Models\User;

use App\Models\Tests\Test_rda_testing;
use App\Models\Tests\Test_rda_testing_meta;
use App\Models\Tests\Test_atg_test;
use App\Models\Tests\Test_cs_test;
use App\Models\Tests\Test_line_leak_test;
use App\Models\Tests\Test_ls_test;
use App\Models\Tests\Test_overfill_test;
use App\Models\Tests\Test_sb_test;
use App\Models\Tests\Test_gcp_test;
use App\Models\Tests\Test_iccp_test;
use App\Models\Tests\Test_stage_1_test;

use PDF;


class TestsController extends Controller
{
    function rda_comp_tests()
    {
        return [
            [
                "id" => 1,
                "test" => "Automatic tank gauge and other controllers:",
                "description" => "test alarm; verify system configuration; test battery backup."
            ],
            [
                "id" => 2,
                "test" => "Probes and sensors:",
                "description" => "inspect for residual buildup; ensure floats move freely; ensure shaft is not damaged; ensure cables are free of kinks and breaks; test alarm operability and communication with controller."
            ],
            [
                "id" => 3,
                "test" => "Automatic line leak detector:",
                "description" => "test to ensure device can detect any release from the piping system of 3 gallons per hour at 10 pounds per square inch within one hour by simulating a leak."
            ],
            [
                "id" => 4,
                "test" => "Vacuum pumps and pressure gauges:",
                "description" => "ensure proper communication with sensors and controller"
            ],
            [
                "id" => 5,
                "test" => "Hand-held electronic sampling equipment associated with groundwater or vapor monitoring:",
                "description" => "ensure proper operation."
            ]
        ];

    }

    function index(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $type = request('type', '');
        
        $auth_role = auth()->user()->role;
        $auth_id = auth()->user()->id;

        if($auth_role > 4){
            return abort(404);
        }
        
        $customers_all = User::whereNull('deleted')->where('role', '=', 6)->get();
        $technicians_all = User::whereNull('deleted')->where('role', '=', 5)->get();
        $rda_comp_tests = $this->rda_comp_tests();

        if (request()->input('type') == 'release-detection-annual-testing') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_rda_testing::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'rda_comp_tests', 'testing'));
        }

        if (request()->input('type') == 'atg-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_atg_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'cs-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_cs_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'line-leak-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_line_leak_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'ls-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_ls_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'overfill-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_overfill_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'sb-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_sb_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'gcp-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_gcp_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'iccp-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_iccp_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if (request()->input('type') == 'stage-1-test') {
            $testing = null;
            if (request()->has('edit')) {
                $testing = Test_stage_1_test::where('id', request()->input('edit'))->first();
            }
            return view('dashboard', compact('customers_all', 'technicians_all', 'testing'));
        }

        if(request()->filled('type-filter')){
            $type = request()->input('type-filter');

            if($type == 'release-detection-annual-testing'){
                $testings = Test_rda_testing::all();
            } elseif($type == 'atg-test'){
                $testings = Test_atg_test::all();                
            } elseif($type == 'cs-test'){
                $testings = Test_cs_test::all();                
            } elseif($type == 'line-leak-test'){
                $testings = Test_line_leak_test::all();                
            } elseif($type == 'ls-test'){
                $testings = Test_ls_test::all();                
            } elseif($type == 'overfill-test'){
                $testings = Test_overfill_test::all();                
            } elseif($type == 'sb-test'){
                $testings = Test_sb_test::all();                
            } elseif($type == 'gcp-test'){
                $testings = Test_gcp_test::all();                
            } elseif($type == 'iccp-test'){
                $testings = Test_iccp_test::all();                
            } elseif($type == 'stage-1-test'){
                $testings = Test_stage_1_test::all();                
            }

            foreach ($testings as $test) {
                $test->type = $type;
            }
            
        } else {
            $rda_testings = Test_rda_testing::all();
            foreach ($rda_testings as $test) {
                $test->type = "Release Detection Annual Testing";
            }

            $atg_tests = Test_atg_test::all();
            foreach ($atg_tests as $test) {
                $test->type = "ATG Test";
            }

            $cs_tests = Test_cs_test::all();
            foreach ($cs_tests as $test) {
                $test->type = "CS Test";
            }

            $test_line_leak_tests = Test_line_leak_test::all();
            foreach ($test_line_leak_tests as $test) {
                $test->type = "Line Leak Test";
            }

            $ls_tests = Test_ls_test::all();
            foreach ($ls_tests as $test) {
                $test->type = "Liquid Sensor Test";
            }

            $overfill_tests = Test_overfill_test::all();
            foreach ($overfill_tests as $test) {
                $test->type = "Overfill Test";
            }

            $sb_tests = Test_sb_test::all();
            foreach ($sb_tests as $test) {
                $test->type = "Spill Bucket Test";
            }

            $gcp_tests = Test_sb_test::all();
            foreach ($gcp_tests as $test) {
                $test->type = "GCP Test";
            }

            $iccp_tests = Test_sb_test::all();
            foreach ($iccp_tests as $test) {
                $test->type = "ICCP Test";
            }

            $stage_1_tests = Test_stage_1_test::all();
            foreach ($stage_1_tests as $test) {
                $test->type = "Stage 1 Test";
            }

            $testings = $rda_testings->merge($atg_tests)->merge($cs_tests)->merge($test_line_leak_tests)->merge($ls_tests)->merge($overfill_tests)->merge($sb_tests)->merge($gcp_tests)->merge($iccp_tests)->merge($stage_1_tests);
        }        

        $testings = paginateCollection($testings, $perPage, $page);
        return view('dashboard', compact('testings'));
    }

    function add_rda_testing(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_rda_testing::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->save();
            $testing->testing_meta()->delete();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
            ];
            $testing = Test_rda_testing::create($new_testing);

        }        

        $testing_metas = $request->input('tests');
        if($testing_metas){

            $testing_metas = json_decode($testing_metas, true);

            foreach($testing_metas as $meta){
                Test_rda_testing_meta::create([
                    'testing_id' => $testing->id,
                    'descript' => $meta['descript'],
                    'meets_criteria' => $meta['meets_criteria'],
                    'needs_action' => $meta['needs_action'],
                    'action_taken' => $meta['action_taken'] ?? null,
                ]);
            }

        }

        $directoryPath = public_path("tests-docs/release-detection-annual-testing/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'release-detection-annual-testing-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $rda_comp_tests = $this->rda_comp_tests();
        $data = [
            'testing' => $testing,
            'rda_comp_tests' => $rda_comp_tests,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.release-detection-annual-testing', $data);
        $pdf->setPaper('A4', 'landscape');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/release-detection-annual-testing/$fileName";
        $testing->save();

        return redirect( url("tests-docs/release-detection-annual-testing/$fileName") );
    }

    function add_atg_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_atg_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->tanks = $request->input('tanks');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'tanks' => $request->input('tanks'),
            ];
            $testing = Test_atg_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/atg-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'atg-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.atg-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/atg-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/atg-test/$fileName") );
    }

    function add_cs_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_cs_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->items = $request->input('items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'items' => $request->input('items'),
            ];
            $testing = Test_cs_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/cs-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'cs-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.containment-sump-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/cs-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/cs-test/$fileName") );
    }
    
    function add_line_leak_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_line_leak_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->items = $request->input('items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'items' => $request->input('items'),
            ];
            $testing = Test_line_leak_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/line-leak-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'line-leak-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.line-leak-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/line-leak-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/line-leak-test/$fileName") );
    }

    function add_ls_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_ls_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->items = $request->input('items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'items' => $request->input('items'),
            ];
            $testing = Test_ls_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/ls-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'ls-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.liquid-sensor-test', $data);
        $pdf->setPaper('A4', 'landscape');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/ls-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/ls-test/$fileName") );
    }

    function add_overfill_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_overfill_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->items = $request->input('items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'items' => $request->input('items'),
            ];
            $testing = Test_overfill_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/overfill-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'overfill-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.overfill-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/overfill-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/overfill-test/$fileName") );
    }

    function add_sb_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_sb_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->items = $request->input('items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'items' => $request->input('items'),
            ];
            $testing = Test_sb_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/sb-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'sb-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.spill-bucket-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/sb-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/sb-test/$fileName") );
    }

    function add_gcp_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_gcp_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->reason = $request->input('reason');
            $testing->conducted_date = $request->input('conducted_date');
            $testing->evaluation = $request->input('evaluation');
            $testing->criteria_appli = $request->input('criteria_appli');
            $testing->action_req = $request->input('action_req');
            $testing->tank_des_items = $request->input('tank_des_items');
            $testing->event_des_items = $request->input('event_des_items');
            $testing->result_items = $request->input('result_items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'reason' => $request->input('reason'),
                'conducted_date' => $request->input('conducted_date'),
                'evaluation' => $request->input('evaluation'),
                'criteria_appli' => $request->input('criteria_appli'),
                'action_req' => $request->input('action_req'),
                'tank_des_items' => $request->input('tank_des_items'),
                'event_des_items' => $request->input('event_des_items'),
                'result_items' => $request->input('result_items'),
            ];
            $testing = Test_gcp_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/gcp-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'gcp-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.galvanic-cp-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/gcp-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/gcp-test/$fileName") );
    }

    function add_iccp_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_iccp_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->reason = $request->input('reason');
            $testing->conducted_date = $request->input('conducted_date');
            $testing->evaluation = $request->input('evaluation');
            $testing->criteria_appli = $request->input('criteria_appli');
            $testing->action_req = $request->input('action_req');
            $testing->rec_man = $request->input('rec_man');
            $testing->rec_serial = $request->input('rec_serial');
            $testing->rec_model = $request->input('rec_model');
            $testing->rec_volt = $request->input('rec_volt');
            $testing->rec_amp = $request->input('rec_amp');
            $testing->tank_des_items = $request->input('tank_des_items');
            $testing->event_des_items = $request->input('event_des_items');
            $testing->result_items = $request->input('result_items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'reason' => $request->input('reason'),
                'conducted_date' => $request->input('conducted_date'),
                'evaluation' => $request->input('evaluation'),
                'criteria_appli' => $request->input('criteria_appli'),
                'action_req' => $request->input('action_req'),
                'rec_man' => $request->input('rec_man'),
                'rec_serial' => $request->input('rec_serial'),
                'rec_model' => $request->input('rec_model'),
                'rec_volt' => $request->input('rec_volt'),
                'rec_amp' => $request->input('rec_amp'),
                'tank_des_items' => $request->input('tank_des_items'),
                'event_des_items' => $request->input('event_des_items'),
                'result_items' => $request->input('result_items'),
            ];
            $testing = Test_iccp_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/iccp-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'iccp-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.impressed-current-cp-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/iccp-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/iccp-test/$fileName") );
    }

    function add_stage_1_test(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $auth_role = auth()->user()->role;

        if($auth_role > 4){
            return abort(404);
        }

        if (request()->has('edit')) {

            $testing = Test_stage_1_test::find(request()->input('edit'));
            $testing->customer_id = $request->input('customer_id');
            $testing->tech_id = $request->input('tech_id');
            $testing->date = $request->input('date');
            $testing->arrived_at = $request->input('arrived_at');
            $testing->departed_at = $request->input('departed_at');
            $testing->gdf_name = $request->input('gdf_name');
            $testing->gdf_address = $request->input('gdf_address');
            $testing->gdf_phone = $request->input('gdf_phone');
            $testing->gdf_permit = $request->input('gdf_permit');
            $testing->gdf_fac_id = $request->input('gdf_fac_id');
            $testing->tank_noz_1 = $request->input('tank_noz_1');
            $testing->tank_noz_2 = $request->input('tank_noz_2');
            $testing->tank_noz_3 = $request->input('tank_noz_3');
            $testing->tank_noz_4 = $request->input('tank_noz_4');
            $testing->vapor_items = $request->input('vapor_items');
            $testing->test_items = $request->input('test_items');
            $testing->pv_data_items = $request->input('pv_data_items');
            $testing->last_items = $request->input('last_items');
            $testing->save();

        } else {

            $new_testing = [
                'customer_id' => $request->input('customer_id'),
                'tech_id' => $request->input('tech_id'),
                'date' => $request->input('date'),
                'arrived_at' => $request->input('arrived_at'),
                'departed_at' => $request->input('departed_at'),
                'gdf_name' => $request->input('gdf_name'),
                'gdf_address' => $request->input('gdf_address'),
                'gdf_phone' => $request->input('gdf_phone'),
                'gdf_permit' => $request->input('gdf_permit'),
                'gdf_fac_id' => $request->input('gdf_fac_id'),
                'tank_noz_1' => $request->input('tank_noz_1'),
                'tank_noz_2' => $request->input('tank_noz_2'),
                'tank_noz_3' => $request->input('tank_noz_3'),
                'tank_noz_4' => $request->input('tank_noz_4'),
                'vapor_items' => $request->input('vapor_items'),
                'test_items' => $request->input('test_items'),
                'pv_data_items' => $request->input('pv_data_items'),
                'last_items' => $request->input('last_items'),

            ];
            $testing = Test_stage_1_test::create($new_testing);

        }

        $directoryPath = public_path("tests-docs/stage-1-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $fileName = 'stage-1-test-' . $testing->id . '.pdf';
        $filePath = $directoryPath . $fileName;

        $data = [
            'testing' => $testing,
        ];
            
        $pdf = PDF::loadView('layouts.dashboard.tests.stage-1-test', $data);
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        $testing->pdf_path = "tests-docs/stage-1-test/$fileName";
        $testing->save();

        return redirect( url("tests-docs/stage-1-test/$fileName") );
    }


    function atg_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/atg-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'atg-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.atg-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/atg-test/atg-test.pdf") );
    }

    function line_leak_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/line-leak-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'line-leak-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.line-leak-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/line-leak-test/line-leak-test.pdf") );
    }

    function liquid_sensor_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/liquid-sensor-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'liquid-sensor-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.liquid-sensor-test');
        $pdf->setPaper('A4', 'landscape');

        $pdf->save($filePath);

        return redirect( url("tests-docs/liquid-sensor-test/liquid-sensor-test.pdf") );
    }

    function containment_sump_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/containment-sump-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'containment-sump-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.containment-sump-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/containment-sump-test/containment-sump-test.pdf") );
    }

    function galvanic_cp_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/galvanic-cp-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'galvanic-cp-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.galvanic-cp-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/galvanic-cp-test/galvanic-cp-test.pdf") );
    }

    function impressed_current_cp_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/impressed-current-cp-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'impressed-current-cp-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.impressed-current-cp-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/impressed-current-cp-test/impressed-current-cp-test.pdf") );
    }

    function overfill_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/overfill-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'overfill-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.overfill-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/overfill-test/overfill-test.pdf") );
    }

    function spill_bucket_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/spill-bucket-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'spill-bucket-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.spill-bucket-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/spill-bucket-test/spill-bucket-test.pdf") );
    }

    function stage_1_test()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/stage-1-test/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'stage-1-test.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.stage-1-test');
        $pdf->setPaper('A4', 'portrait');

        $pdf->save($filePath);

        return redirect( url("tests-docs/stage-1-test/stage-1-test.pdf") );
    }

    function release_detection_annual_testing()
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $directoryPath = public_path("tests-docs/release-detection-annual-testing/");

        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filePath = $directoryPath . 'release-detection-annual-testing.pdf';
            
        $pdf = PDF::loadView('layouts.dashboard.tests.release-detection-annual-testing');
        $pdf->setPaper('A4', 'landscape');

        $pdf->save($filePath);

        return redirect( url("tests-docs/release-detection-annual-testing/release-detection-annual-testing.pdf") );
    }
}
