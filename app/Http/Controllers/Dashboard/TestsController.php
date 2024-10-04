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

        if(request()->filled('type-filter')){
            $type = request()->input('type-filter');

            if($type == 'release-detection-annual-testing'){
                $testings = Test_rda_testing::all();
            } elseif($type == 'atg-test'){
                $testings = Test_atg_test::all();                
            }

            foreach ($testings as $test) {
                $test->type = $type;
            }
            
        } else {
            $rda_testings = Test_rda_testing::all();
            foreach ($rda_testings as $test) {
                $test->type = "release-detection-annual-testing";
            }

            $atg_tests = Test_atg_test::all();
            foreach ($atg_tests as $test) {
                $test->type = "atg-test";
            }
            $testings = $rda_testings->merge($atg_tests);
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
