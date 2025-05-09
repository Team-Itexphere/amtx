<?php

namespace App\Models\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Test_iccp_test extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'tech_id', 'date', 'conducted_date', 'reason', 'evaluation', 'criteria_appli', 'action_req', 'rec_man', 'rec_serial', 'rec_model', 'rec_volt', 'rec_amp', 'tank_des_items', 'event_des_items', 'result_items', 'pdf_path'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
