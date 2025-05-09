<?php
 
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Stores;

class CommunicationController extends Controller
{
    public function index()
    {
        if (auth()->user() && auth()->user()->role == 1) {
            $stores = Stores::where('status', 'Complete')->get();
            return view('dashboard', compact('stores'));
        }

        return abort(404);
    }

    //for sending message
    public function send(Request $request)
    {
        if (!auth()->user()) {
            return redirect('login');
        }
        
        $role = auth()->user()->role;

        if ($role != 1) {
            return redirect()->back()->with('error', 'Access denied');
        }
        
        $rules = [
            'rec_role' => 'required|array',
            'rec_store' => 'required|array',
            'message' => 'required|string|max:100',
        ];

        $request->validate($rules);

        $rec_roles = $request->input('rec_role');
        $rec_store_ids = $request->input('rec_store');
        $message = $request->input('message');

        $users = User::whereIn('role', $this->getRoleIds($rec_roles))->whereHas('stores', function($query) use ($rec_store_ids) {
            $query->whereIn('store_id', $rec_store_ids);
        })->get(['name', 'phone']);

        if(!$users){
            return redirect()->back()->with('error', 'No users to continue');
        }        
            
        $country_code = config('app.country_code', 'Laravel');
        $mandatory_numbers = explode(',', config('app.main_numbers', 'Laravel'));

        foreach($mandatory_numbers as $mandatory_number){

            $sms = 'Hello,<br>'. $message;
            $mandatory_number = $country_code . $mandatory_number;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL,"https://api-mapper.clicksend.com/http/v2/send.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            
            curl_setopt($ch, CURLOPT_POSTFIELDS,"username=info@cyberdreams.net&key=CAFB6AE4-BB12-728F-0243-E0B8FA88C5AD&to=" . $mandatory_number . "&senderid=GASGURU&from=GASGURU&message=". $sms);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $server_output = curl_exec($ch);

            curl_close ($ch);

        }

        foreach($users as $user){

            $name = $user->name;
            $number = $user->phone;

            if ($number && ( $number != '' )) {
                
                $sms = 'Dear '. $name .',<br>'. $message;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL,"https://api-mapper.clicksend.com/http/v2/send.php");
                curl_setopt($ch, CURLOPT_POST, 1);
            
                curl_setopt($ch, CURLOPT_POSTFIELDS,"username=info@cyberdreams.net&key=CAFB6AE4-BB12-728F-0243-E0B8FA88C5AD&to=" . $number . "&senderid=GASGURU&from=GASGURU&message=". $sms);
            
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $server_output = curl_exec($ch);

                curl_close ($ch);
            }
        }

        $response = implode(', ', $rec_roles);
        $response = strtolower($response);

        return redirect()->back()->with('success', 'Your message was sent to '. $response .' successfully!');
    }

    private function getRoleIds($roles)
    {
        $roleMapping = [
            'ALL' => [3, 4, 5, 6],
            'Supervisor' => [3],
            'Managers' => [4],
            'Employees' => [5, 6],
        ];

        $roleIds = [];

        foreach ($roles as $role) {
            if (isset($roleMapping[$role])) {
                $roleIds = array_merge($roleIds, $roleMapping[$role]);
            }
        }

        return array_unique($roleIds);
    }
}
