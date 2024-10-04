<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoices;
use App\Models\Invoice_items;
use App\Models\Maintain_logs;
use App\Models\Inventorys;
use App\Models\Testings;
use App\Models\User;
use PDF;
use Mail;
use Carbon\Carbon;

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
            if($store && $store != ''){ 
                $str_usr = User::find($store);
                $fac_id = $str_usr->fac_id;
                $invoices = $invoices->where('customer_id', $store);
            }
            
            $invoices = $invoices->orderBy('created_at', 'desc')->get();
            
            foreach($invoices as $inv){
                if($inv->updatedBy){
                    $updatedByArr = json_decode($inv->updatedBy, true);
    
                    foreach ($updatedByArr as &$By) {
                        $By[0] = User::find($By[0])->name ?? 'Unknown';
                        
                        $By[1] = Carbon::parse($By[1])->format('m/d/Y H:s A');
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
        
            if($last_inv){
                $inv_no = $last_inv->invoice_no + 1;
                $inv_no = str_pad($last_inv->id + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $inv_no = '00001';
            }
    
            $new_invoice_data = [
                'invoice_no' => $inv_no,
                'date' => $request->input('date'),
                'service' => $request->input('service'),
                'customer_id' => $request->input('customer_id'),
                'route_list_id' => $request->input('list_id') ?? null,
                'payment' => $request->input('payment') ?? ($request->input('pay_opt') != '' ? 'Paid' : null),
                'po_no' => $request->input('po_no'),
                'pay_opt' => $request->input('pay_opt'),
                'check_no' => $request->input('check_no'),
                'mo_no' => $request->input('mo_no'),
                'comment' => $request->input('comment'),
                'addi_comments' => $request->input('addi_comments'),
                'createdBy' => auth()->user()->id,
            ];
            
            
            
            $invoice = Invoices::create($new_invoice_data);
            $invoice_id = $invoice->id;
            
            if($request->input('paid_amount') && $request->input('paid_date')){
                $paid = [];
                $paid[] = [$request->input('paid_amount'), $request->input('paid_date')];
                $invoice->paid_amount = $paid;
            }

            /*if($invoice && $request->filled('invoice_items')){
                $invoice_items = json_decode($request->input('invoice_items'), true);

                foreach($invoice_items as $item) {

                    if(isset($item['descript'])){

                        $item = new Invoice_items([
                            'invoice_id' => $invoice_id,
                            'descript' => $item['descript'],
                            'is_inspection' => 'Yes',
                            'qty' => $item['qty'],
                            'rate' => $item['rate'],
                            'amount' => $item['amount'],
                        ]);

                        $invoice->invoice_items()->save($item);

                    }
                }
            }*/
            
            if($invoice && $request->filled('items')){
                $items = json_decode($request->input('items'), true);

                foreach($items as $item) {
                    $item['item_name'] = isset($item['item_name']) ? $item['item_name'] : null;
                    $item['category'] = isset($item['category']) ? $item['category'] : null;

                    $inv_item = new Invoice_items([
                        'invoice_id' => $invoice_id,
                        'item_name' => $item['item_name'],
                        'category' => $item['category'],
                        'descript' => $item['description'],
                        'qty' => $item['qty'] ?? null,
                        'rate' => $item['rate'] ?? null,
                        'amount' => $item['amount'],
                    ]);

                    $invoice->invoice_items()->save($inv_item);
                    
                    if($item['category'] != 'Monthly Inspection' && request()->is('api/testing*')){
                        $maintaince_log = new Maintain_logs([
                            'invoice_id' => $invoice_id,
                            'cus_id' => $request->input('customer_id'),
                            'category' => $item['category'],
                            'descript' => $item['description'],
                            'des_problem' => $item['des_problem'],
                            'qty' => $item['qty'] ?? null,
                            'rate' => $item['rate'] ?? null,
                            'amount' => $item['amount'],
                            'date' => date('Y-m-d'),
                            'location' => $item['location'] ?? null,
                            'tech_id' => auth()->user()->id,
                        ]);
                        
                        $invoice->maintain_logs()->save($maintaince_log);
                    }
                }
            }

            if(request()->is('api/testing*')){
                /*$testing = Testings::find($request->input('testing_id'));
                $testing->invoice_id = $invoice->id;
                $testing->save();*/
    
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
            $invoice_link = url("invoices/" . $invoice->invoice_no . '.pdf');
            return response()->json(['invoice_link' => $invoice_link]);
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
            
            if ($role > 3) {
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
            $updatedBy[] = [auth()->id(), now()->toDateTimeString()];
            $invoice->updatedBy = $updatedBy;
    
            $invoice->save();

            if($request->filled('invoice_items')){
                $invoice_id = $invoice->id;
                $invoice_items = json_decode($request->input('invoice_items'), true);

                $invoice->invoice_items()->delete();

                foreach($invoice_items as $item) {

                    if(isset($item['descript'])){

                        $item = new Invoice_items([
                            'invoice_id' => $invoice_id,
                            'item_name' => $item['item_name'],
                            'descript' => $item['descript'],
                            'qty' => $item['qty'],
                            'rate' => $item['rate'],
                            'amount' => $item['amount'],
                        ]);

                        $invoice->invoice_items()->save($item);

                    }
                }
            }
    
            return redirect('/dashboard/invoice?edit='.$invoice_id)->with('success', 'Invoice updated successfully!');
        } else {
            return redirect('login');
        }
    }
    
    
    // for API
    public function createInvoice(Request $request)
    {
        $role = auth()->user()->role;
        
        if ( $role == 5 ) {

            $last_inv = Invoices::orderBy('invoice_no', 'desc')->first();
        
            if($last_inv){
                $inv_no = $last_inv->invoice_no + 1;
                $inv_no = str_pad($last_inv->id + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $inv_no = '00001';
            }
    
            $new_invoice_data = [
                'invoice_no' => $inv_no,
                'date' => date('Y-m-d'),
                'customer_id' => $request->input('customer_id'),
                'pay_opt' => $request->input('pay_opt'),
                'payment' => $request->input('payment') ?? ($request->input('pay_opt') != '' ? 'Paid' : null),
                'check_no' => $request->input('check_no'),
                'mo_no' => $request->input('mo_no'),
                'createdBy' => auth()->user()->id,
            ];
            
            $invoice = Invoices::create($new_invoice_data);
            $invoice_id = $invoice->id;
            
            if($invoice && $request->filled('items')){
                $items = $request->input('items');

                foreach($items as $item) {
                    $inv_item = new Invoice_items([
                        'invoice_id' => $invoice_id,
                        'category' => $item['category'],
                        'descript' => $item['description'],
                        'qty' => $item['qty'] ?? null,
                        'amount' => $item['amount'],
                    ]);

                    $invoice->invoice_items()->save($inv_item);
                }
            }
            
            return $this->pdfGen($invoice);

        } else {

            return response()->json(['message' => 'Access Denied'], 401);

        }

    }
    
}
