<?php
  
namespace App\Console\Commands;
  
use Illuminate\Console\Command;
use App\Models\Cus_licenses;
use Mail;
  
class LicenseExpEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'li_exp:email';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Check Licenses';
  
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = \Carbon\Carbon::today();
        $risk_period = $today->copy()->addDays(29);
        
        $cus_licenses = Cus_licenses::all();
        
        foreach($cus_licenses as $license){
            
            if($license->expire_date){
                
                $exp_risk = false;
                
                $ex_date = \Carbon\Carbon::parse($license->expire_date);
                $formatted_date = $ex_date->format('m/d/Y');
                                
                if($ex_date->isPast()) {
                    
                    $status = "has been expired on $formatted_date";
                    $exp_risk = true;
                    
                } elseif ($ex_date->between($today, $risk_period)){
                    
                    $status = "will be expired on $formatted_date";
                    $exp_risk = true;
                    
                }
                
                if($exp_risk){
                    $li_name = $license->name;
                    $message = "Dear customer, <br><br> Your license ($li_name) $status. <br> Please renew as soon as possible. <br><br>Thank you.";
                    
                    $data["email"] = $license->customer->email;
                    $data["subject"] = "Attention - Renew Your License";
              
                    Mail::send('emails.invoiceCreated', $data, function($message)use($data) {
                        $message->to($data["email"], $data["email"])
                                ->subject($data["subject"]);
                        
                    });
                    
                    $license->remind_date = $today;
                    $license->save();
                }
                
            }
            
        }
        
    }
}