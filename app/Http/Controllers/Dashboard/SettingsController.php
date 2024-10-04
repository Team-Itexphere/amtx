<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Settings;

class SettingsController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role == 1) {
            return view('dashboard');
        }
        if (auth()->user() && auth()->user()->role == 2) {
            return redirect('dashboard');
        }

        return redirect('login');
    }

    //for settings edit
    public function edit(Request $request)
    {
        if (!auth()->user() || auth()->user()->role != 1) {
            return redirect('login');
        }

        $rules = [
            'site_name' => 'required|string|max:60',
            'country_code' => 'required|string|max:5',
        ];
        $rules['site_logo'] = $request->file('site_logoaddress') ? 'mimes:jpg,jpeg,png|max:20480' : '';
        $rules['favicon'] = $request->file('favicon') ? 'mimes:jpg,jpeg,png|max:20480' : '';
        $rules['main_numbers'] = $request->input('main_numbers') ? 'max:300' : '';
    
        $request->validate($rules);
        
        $settings = Settings::find(1);

        if(!$settings){
            $site_settings = [
                'site_name' => 'MVMS',
                'site_logo_path' => '',
                'favicon_path' => '',
                'country_code' => '',
            ];
            $settings = Settings::create($site_settings);
        }
    
        $new_settings = [
            'site_name' => $request->input('site_name'),
            'country_code' => $request->input('country_code'),
            'main_numbers' => $request->input('main_numbers'),
        ];

        if ($request->hasFile('site_logo')) {
            $site_logo = $request->input('site_logo');
            $fileName = $request->file('site_logo')->getClientOriginalName();

            $request->file('site_logo')->move(public_path('img') . "/", $fileName);

            $new_settings['site_logo_path'] = $fileName;
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->input('favicon');
            $fileName = $request->file('favicon')->getClientOriginalName();

            $request->file('favicon')->move(public_path('img') . "/", $fileName);

            $new_settings['favicon_path'] = $fileName;
        }

        $settings->fill($new_settings);
    
        $settings->save();
    
        return redirect()->back()->with('success', 'Site settings updated successfully!');

    }
}
