<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Models\Invoices;
use App\Models\Invoice_items;
use App\Models\Maintain_logs;
use App\Models\Inventorys;
use App\Models\Testings;
use App\Models\User;
use PDF;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoicesController extends Controller
{
    //views for par
    public function index()
    {
        if (!auth()->user()) {
            return redirect('login');
        }

        $perPage = request('per_page', 10);
        $page = request('page', 1);

        $type = request('type', '');
        $type = ( $type == 'equip' ? 'Equipment' : ( $type == 'f_tank' ? 'Fuel Tank' : ( $type == 'pump' ? 'Pump' : ( $type == 'general' ? 'General' : '' ))));
        $store = request('store', '');
        $s = request('s', '');
        $fltRole = request('role', '');
        $dateRange  = request('d_range', '');
        $status = request('status', '');
        
        $role = auth()->user()->role;
        $auth_user = auth()->user();
        $auth_id = $auth_user->id;
        $customers = User::where('role', 6)->get();
        
        if ( $role < 7 ) {
            
            if (request()->has('add')) {
                if ( $role < 5 ) {
                    $inventorys = Inventorys::all();
                    return view('dashboard', compact('customers', 'inventorys'));
                }

                return abort(404);
            }
            
            if (request()->has('edit')) {
                if (request()->filled('edit')) {
                    $invId = request()->input('edit');
                    
                    $invoice = Invoices::find($invId);

                    if ( $role < 5 ) {
                        $inventorys = Inventorys::all();
                        return view('dashboard', compact('invoice', 'customers', 'inventorys'));
                    }
                }
                return abort(404);
            }

            if($role == 6){
                $all_stores = $auth_user->stores()->get();
                $all_stores->push($auth_user);
                $all_stores_ids = $all_stores->pluck('id');
                $invoices = Invoices::whereIn('customer_id', $all_stores_ids);
            } else {
                $all_stores = User::where('role', 6)->get(); 
                $invoices = Invoices::query();
            }
            
            $fac_id = null;
$invoices = Invoices::query(); // Start with a query builder instance

if ($store && $store != '') { 
    $str_usr = User::find($store);
    $fac_id = $str_usr->fac_id ?? null;
    $invoices->where('customer_id', $store);
}

if ($role == 5) { 
    $invoices->where('createdBy', $auth_id);
}

if ($status) {
    $invoices->where(function ($query) use ($status) {
        $query->where('payment', $status)->orWhereNull('payment');
    });
}

// Role-based filtering
/*if ($role > 3) {
    $invoices = auth()->user()->invoices()->whereColumn('id', '!=', 0); // Ensures invoices aren't overridden
}*/

// Apply sorting and fetch results
$invoices = $invoices->orderBy('created_at', 'desc')->get();
            
            foreach($invoices as $inv){
                if($inv->updatedBy){
                    $updatedByArr = json_decode($inv->updatedBy, true);
    
                    foreach ($updatedByArr as &$By) {
                        $By[0] = User::find($By[0])->name ?? 'Unknown';
                        
                        $By[1] = Carbon::parse($By[1])->setTimezone('America/Chicago')->format('m/d/Y H:s A');
                    }
            
                    $inv->updatedBy = $updatedByArr;
                }
            }

            $invoices = paginateCollection($invoices, $perPage, $page);

            return view('dashboard', compact('invoices', 'all_stores', 'fac_id'));

        }

        return abort(404);
    }


    //for invoice add
    public function add(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;

        if ( $role < 6 ) {
        
            $rules = [
                'date' => 'required|max:60',
                'customer_id' => 'required|max:20',
            ];
            
            $request->validate($rules);

            $last_inv = Invoices::orderBy('invoice_no', 'desc')->first();
        
            if ($last_inv) {
                $last_inv_number = (int)substr($last_inv->invoice_no, 2);
                $new_inv_number = $last_inv_number + 1;
                $inv_no = 'PT' . $new_inv_number;
            } else {
                $inv_no = 'PT1';
            }
            
            $edit_inv = null;
            if($request->input('id')){
                $edit_inv = Invoices::find($request->input('id'));
                if ($edit_inv && $edit_inv->payment == 'Paid') {
                    return response()->json(['message' => 'Access Denied'], 401);
                }
            }
    
            $new_invoice_data = [
                'invoice_no' => $edit_inv ? $edit_inv->invoice_no : $inv_no,
                'date' => $request->input('date') ?? ($edit_inv ? $edit_inv->date : null),
                'service' => $edit_inv && $edit_inv->service == 'Monthly Inspection' ? $edit_inv->service : $request->input('service'),
                'customer_id' => $edit_inv ? $edit_inv->customer_id : $request->input('customer_id'),
                'route_list_id' => $edit_inv ? $edit_inv->route_list_id : ($request->input('list_id') ?? null),
                'payment' => $request->input('payment') ?? ($request->input('pay_opt') != '' ? 'Paid' : ($edit_inv ? $edit_inv->payment : null)),
                'po_no' => $request->input('po_no') ?? ($edit_inv ? $edit_inv->po_no : null),
                'pay_opt' => $request->input('pay_opt') ?? ($edit_inv ? $edit_inv->pay_opt : null),
                'check_no' => $request->input('check_no') ?? ($edit_inv ? $edit_inv->check_no : null),
                'mo_no' => $request->input('mo_no') ?? ($edit_inv ? $edit_inv->mo_no : null),
                'comment' => $request->input('comment') ?? ($edit_inv ? $edit_inv->comment : null),
                'addi_comments' => $request->input('addi_comments') ?? ($edit_inv ? $edit_inv->addi_comments : null),
                'createdBy' => $edit_inv ? $edit_inv->createdBy : auth()->user()->id,
                'updated_at' => Carbon::now('America/Chicago')
            ];
            
            if($edit_inv){
                $edit_inv->fill($new_invoice_data)->save();
                $invoice = $edit_inv;
            } else {
                $new_invoice_data['created_at'] = Carbon::now('America/Chicago');
                $invoice = Invoices::forceCreate($new_invoice_data);
            }
            
            $invoice_id = $invoice->id;
            
            if($request->input('paid_amount') && $request->input('paid_date')){
                $paid = [];
                $paid[] = [$request->input('paid_amount'), $request->input('paid_date')];
                $invoice->paid_amount = $paid;
            }

            if($invoice && $request->filled('items')){
                $items = json_decode($request->input('items'), true);
                $invoice->invoice_items()->delete();
                
                foreach($items as $item) {
                    $item['item_name'] = isset($item['item_name']) ? $item['item_name'] : null;
                    $item['category'] = isset($item['category']) ? $item['category'] : null;

                    $inv_item = [
                        'invoice_id' => $invoice_id,
                        'item_name' => $item['item_name'],
                        'category' => $item['category'],
                        'descript' => $item['description'],
                        'location' => $item['location'] ?? null,
                        'qty' => $item['qty'] ?? null,
                        'rate' => $item['rate'] ?? null,
                        'amount' => $item['amount'],
                        'created_at' => Carbon::now('America/Chicago'),
                        'updated_at' => Carbon::now('America/Chicago')
                    ];

                    Invoice_items::forceCreate($inv_item);
                    
                    if($item['category'] != 'Monthly Inspection' && $item['category'] != 'Service Call' && request()->is('api/testing*') && $item['description'] != 'Calibration Labor'){
                        $maintaince_log = [
                            'invoice_id' => $invoice_id,
                            'cus_id' => $request->input('customer_id'),
                            'category' => $item['category'],
                            'descript' => $item['description'],
                            'des_problem' => isset($item['des_problem']) ? $item['des_problem'] : null,
                            'qty' => $item['qty'] ?? null,
                            'rate' => $item['rate'] ?? null,
                            'amount' => $item['amount'],
                            'date' => Carbon::now('America/Chicago')->format('Y-m-d'),
                            'location' => $item['location'] ?? null,
                            'tech_id' => auth()->user()->id,
                            'updated_at' => Carbon::now('America/Chicago')
                        ];
                        
                        $exist_log = $invoice->maintain_logs()->where('descript', $item['description'])->first();
                        
                        if($exist_log){
                            $exist_log->fill($maintaince_log);
                            $exist_log->save();
                        } else {
                            $maintaince_log['created_at'] = Carbon::now('America/Chicago');
                            Maintain_logs::forceCreate($maintaince_log);
                        }
                    }
                }
            }

            if(request()->is('api/testing*')){
                $invoice_link = url("invoices/" . $invoice->invoice_no . '.pdf');
                return $this->pdfGen($invoice);
            }

            $this->pdfGen($invoice);
    
            return redirect('/dashboard/invoice?add')->with('success', 'New invoice created successfully!');
        } else {
            return redirect('login');
        }
    }

    // for pdf generator
    public function pdfGen($invoice)
    {

        $data = [
            'invoice' => $invoice
        ]; 
            
        $pdf = PDF::loadView('layouts.dashboard.invoice.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $filePath = public_path('invoices/') . $invoice->invoice_no . '.pdf';
        $pdf->save($filePath);

        $invoice->file_name = $invoice->invoice_no . '.pdf';
        $invoice->updated_at = Carbon::now('America/Chicago');
        $invoice->save();

        /*/ send email -
        if($invoice->customer->email){
            $data["email"] = $invoice->customer->email;
            $data["title"] = "Invoice " . $invoice->invoice_no . " - AMTS";
     
            $files = [
                $filePath,
            ];
      
            Mail::send('emails.invoiceCreated', $data, function($message)use($data, $files) {
                $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
     
                foreach ($files as $file){
                    $message->attach($file);
                }
                
            });
        }
        // - send email*/

        if(request()->is('api/testing*') || request()->is('api/invoice*')){
            $invoice_link = url("invoices/" . $invoice->invoice_no . '.pdf?v=' . Str::random(3));
            return response()->json([
                'id' => $invoice->id,
                'invoice_link' => $invoice_link,
                'has_sign' => (bool) $invoice->signature
            ]);
        }

    }
    

    //for invoice edit
    public function edit(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;
        
        if ( $role < 5 ) {
            $invoice_id = $request->input('inv_id');
            $invoice = Invoices::find($invoice_id);
            
            if ($role > 3 || $invoice->payment == 'Paid') {
                return redirect('/dashboard/invoice?edit='.$invoice_id)->with('error', 'Access denied');
            }
    
            $rules = [
                'date' => 'required|max:60',
                'customer_id' => 'required|max:20',
            ];
    
            $request->validate($rules);
    
            $invoice->date = $request->input('date');
            $invoice->service = $request->input('service');
            $invoice->customer_id = $request->input('customer_id');
            $invoice->payment = $request->input('payment');
            $invoice->po_no = $request->input('po_no');
            $invoice->pay_opt = $request->input('pay_opt');
            $invoice->check_no = $request->input('check_no');
            $invoice->addi_comments = $request->input('addi_comments');
            
            if($request->input('paid_amount') && $request->input('paid_date')){
                $paid = $invoice->paid_amount ? json_decode($invoice->paid_amount, true) : [];
                $paid[] = [$request->input('paid_amount'), $request->input('paid_date')];
                $invoice->paid_amount = $paid;
            }
            
            $updatedBy = $invoice->updatedBy ? json_decode($invoice->updatedBy, true) : [];
            $updatedBy[] = [auth()->id(), Carbon::now('America/Chicago')->toDateTimeString()];
            $invoice->updatedBy = $updatedBy;
            $invoice->updated_at = Carbon::now('America/Chicago');
    
            $invoice->save();

            if($request->filled('invoice_items')){
                $invoice_id = $invoice->id;
                $invoice_items = json_decode($request->input('invoice_items'), true);

                $invoice->invoice_items()->delete();

                foreach($invoice_items as $item) {

                    /*if(isset($item['descript'])){

                        $item = new Invoice_items([
                            'invoice_id' => $invoice_id,
                            'item_name' => $item['item_name'],
                            'descript' => $item['descript'],
                            'qty' => $item['qty'],
                            'rate' => $item['rate'],
                            'amount' => $item['amount'],
                        ]);

                        $invoice->invoice_items()->save($item);

                    }*/
                }
            }
    
            return redirect('/dashboard/invoice?edit='.$invoice_id)->with('success', 'Invoice updated successfully!');
        } else {
            return redirect('login');
        }
    }
    
    
    // for API
    public function editInvoice(Request $request)
    {
        $role = auth()->user()->role;
        
        if ( $role == 4 || $role == 5 ) {

            $last_inv = Invoices::orderBy('invoice_no', 'desc')->first();
        
            if ($last_inv) {
                $last_inv_number = (int)substr($last_inv->invoice_no, 2);
                $new_inv_number = $last_inv_number + 1;
                $inv_no = 'PT' . $new_inv_number;
            } else {
                $inv_no = 'PT1';
            }
            
            if($request->input('inv_id') || $request->input('id')){
                $id = $request->input('inv_id') ?? $request->input('id');
                $edit_inv = Invoices::find($id);
                if (!$edit_inv) {
                    return response()->json(['message' => 'Invoice Not Found'], 404);
                }
                
                if($request->input('signature')){
                    $fileData = $request->input('signature');
                    $file = base64_decode(explode(',', $fileData)[1], true);

                    $file_name = $edit_inv->invoice_no . ".png";
                    $directoryPath = public_path("invoices/");
                    $new_file_path = $directoryPath . $file_name;
                    
                    file_put_contents($new_file_path, $file);

                    $signature = "/invoices/" . $file_name . '?v=' . Str::random(3);
                    
                    $new_invoice_data = [
                        'signature' => $signature
                    ];
                } else {
                    $new_invoice_data = [
                        'addi_comments' => $request->input('addi_comments') ?? $edit_inv->addi_comments,
                        'pay_opt' => $request->input('pay_opt'),
                        'payment' => $request->input('payment') ?? ($request->input('pay_opt') != '' ? 'Paid' : null),
                        'check_no' => $request->input('check_no'),
                        'mo_no' => $request->input('mo_no')
                    ];
                }
                
                $items = $request->filled('items') ? $request->input('items') : [];
                if(count($items) > 0 && !$request->filled('signature') && !$request->filled('payment') && !$request->input('pay_opt')){
                    $edit_inv->invoice_items()->delete();
                    
                    foreach($items as $item) {
                        $item['item_name'] = isset($item['item_name']) ? $item['item_name'] : null;
                        $item['category'] = isset($item['category']) ? $item['category'] : null;
    
                        $inv_item = new Invoice_items([
                            'invoice_id' => $id,
                            'item_name' => $item['item_name'],
                            'category' => $item['category'],
                            'descript' => $item['description'],
                            'location' => $item['location'] ?? null,
                            'qty' => $item['qty'] ?? null,
                            'rate' => $item['rate'] ?? null,
                            'amount' => $item['amount'],
                            'created_at' => Carbon::now('America/Chicago'),
                            'updated_at' => Carbon::now('America/Chicago')
                        ]);
    
                        $edit_inv->invoice_items()->save($inv_item);
                        
                        if($item['category'] != 'Monthly Inspection' && $item['category'] != 'Service Call' && $item['description'] != 'Calibration Labor'){
                            $maintaince_log = [
                                'invoice_id' => $id,
                                'cus_id' => $edit_inv->customer_id,
                                'category' => $item['category'],
                                'descript' => $item['description'],
                                'des_problem' => isset($item['des_problem']) ? $item['des_problem'] : null,
                                'qty' => $item['qty'] ?? null,
                                'rate' => $item['rate'] ?? null,
                                'amount' => $item['amount'],
                                'date' => Carbon::now('America/Chicago')->format('Y-m-d'),
                                'location' => $item['location'] ?? null,
                                'tech_id' => auth()->user()->id,
                                'updated_at' => Carbon::now('America/Chicago')
                            ];
                            
                            $exist_log = $edit_inv->maintain_logs()->where('descript', $item['description'])->first();
                            if($exist_log){
                                $exist_log->fill($maintaince_log);
                                $exist_log->save();
                            } else {
                                $maintaince_log['created_at'] = Carbon::now('America/Chicago');
                                Maintain_logs::forceCreate($maintaince_log);
                            }
                        }
                    }
                }
                
                $edit_inv->updated_at = Carbon::now('America/Chicago');
                $edit_inv->fill($new_invoice_data)->save();
                
                $this->pdfGen($edit_inv);
                
                $invoice_link = url("invoices/" . $edit_inv->invoice_no . '.pdf?v=' . Str::random(3));
                return response()->json([
                    'id' => $edit_inv->id,
                    'invoice_link' => $invoice_link,
                    'has_sign' => (bool) $edit_inv->signature
                ]);
                
            } else {
                $new_invoice_data = [
                    'invoice_no' => $inv_no,
                    'date' => Carbon::now('America/Chicago')->format('Y-m-d'),
                    'customer_id' => $request->input('customer_id'),
                    'pay_opt' => $request->input('pay_opt'),
                    'payment' => $request->input('payment') ?? ($request->input('pay_opt') != '' ? 'Paid' : null),
                    'check_no' => $request->input('check_no'),
                    'mo_no' => $request->input('mo_no'),
                    'addi_comments' => $request->input('addi_comments'),
                    'service' => $request->input('service'),
                    'createdBy' => auth()->user()->id,
                    'created_at' => Carbon::now('America/Chicago'),
                    'updated_at' => Carbon::now('America/Chicago')
                ];
            }
    
            
            
            $invoice = Invoices::create($new_invoice_data);
            $invoice_id = $invoice->id;
            
            if($invoice && $request->filled('items')){
                $items = $request->input('items');

                foreach($items as $item) {
                    /*$inv_item = new Invoice_items([
                        'invoice_id' => $invoice_id,
                        'category' => $item['category'],
                        'descript' => $item['description'],
                        'qty' => $item['qty'] ?? null,
                        'amount' => $item['amount'],
                    ]);

                    $invoice->invoice_items()->save($inv_item);*/
                    
                    $item['item_name'] = isset($item['item_name']) ? $item['item_name'] : null;
                    $item['category'] = isset($item['category']) ? $item['category'] : null;

                    $inv_item = new Invoice_items([
                        'invoice_id' => $invoice_id,
                        'item_name' => $item['item_name'],
                        'category' => $item['category'],
                        'descript' => $item['description'],
                        'location' => $item['location'] ?? null,
                        'qty' => $item['qty'] ?? null,
                        'rate' => $item['rate'] ?? null,
                        'amount' => $item['amount'],
                        'created_at' => Carbon::now('America/Chicago'),
                        'updated_at' => Carbon::now('America/Chicago')
                    ]);

                    $invoice->invoice_items()->save($inv_item);
                    
                    if($item['category'] != 'Monthly Inspection' && $item['category'] != 'Service Call' && $item['description'] != 'Calibration Labor'){
                        $maintaince_log = new Maintain_logs([
                            'invoice_id' => $invoice_id,
                            'cus_id' => $request->input('customer_id'),
                            'category' => $item['category'],
                            'descript' => $item['description'],
                            'des_problem' => isset($item['des_problem']) ? $item['des_problem'] : null,
                            'qty' => $item['qty'] ?? null,
                            'rate' => $item['rate'] ?? null,
                            'amount' => $item['amount'],
                            'date' => Carbon::now('America/Chicago')->format('Y-m-d'),
                            'location' => $item['location'] ?? null,
                            'tech_id' => auth()->user()->id,
                            'created_at' => Carbon::now('America/Chicago'),
                            'updated_at' => Carbon::now('America/Chicago')
                        ]);
                        
                        $invoice->maintain_logs()->save($maintaince_log);
                    }
                }
            }
            
            return $this->pdfGen($invoice);

        } else {

            return response()->json(['message' => 'Access Denied'], 401);

        }

    }
    
    public function inv_list(Request $request)
    {
        if ( auth()->user()->role < 6 && request()->has('cus_id')) {

            $inv_list = Invoices::with('invoice_items')->where('customer_id', request()->input('cus_id'))->where('createdBy', auth()->user()->id)->latest()->take(30)->get()
                        ->map(function ($inv) {
                            $inv->pdf_link = url("invoices/" . $inv->file_name);
                            $inv->has_sign = (bool) $inv->signature;
                            return $inv;
                        });
            
            return $inv_list;

        } else {
            
            return response()->json(['message' => 'Not Found'], 404);
            
        }

    }
    
}
