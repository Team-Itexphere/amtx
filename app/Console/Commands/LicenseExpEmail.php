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
        
        $reminderIntervals = [45, 30, 7, 1];
        
        $cus_licenses = Cus_licenses::all();
        
        foreach ($cus_licenses as $license) {
            if ($license->expire_date) {
                $expireDate = \Carbon\Carbon::parse($license->expire_date);
                $formattedExpireDate = $expireDate->format('m/d/Y');
                $daysUntilExpiry = $today->diffInDays($expireDate, false);
    
                if (in_array($daysUntilExpiry, $reminderIntervals)) {
                    $customerName = $license->customer->name;
                    $liName = $license->name;
                    
                    // email template
                    $message = "
                        Dear $customerName, 
                            <br><br>
                        We hope this message finds you well. 
                            <br><br>
                        We are writing to inform you that your delivery certificate ($liName) will expire in $daysUntilExpiry days, on $formattedExpireDate. 
                        Please rest assured that there is no need to worry, as our team is already taking care of the renewal process for you. 
                            <br><br>
                        Our commitment is to ensure a seamless experience for our valued customers. 
                        The renewal will be handled promptly and efficiently, and you will receive the updated certificate well before the current one expires. 
                            <br><br>
                        If you have any questions or require further assistance, please do not hesitate to contact us at 281-242-2687. 
                            <br><br>
                        Thank you for your continued trust in our services. 
                            <br><br>
                        Best regards, <br>
                        Anil Momin <br>
                        Director <br>
                        Petro Tank Solutions <br>
                        832-276-6144 <br>
                        <a href='https://amtstx.com'>www.amtstx.com</a>
                    ";
    
                    $data["email"] = $license->customer->email;
                    $data["subject"] = "Upcoming Expiry of Your Delivery Certificate - Renewal in Progress";
                  
                    // Send email
                    /*Mail::html($message, function ($mail) use ($data) {
                        $mail->to($data["email"], $data["email"])
                             ->subject($data["subject"]);
                    });*/
    
                    $license->remind_date = $today;
                    $license->save();
                }
            }
        }
    }
}