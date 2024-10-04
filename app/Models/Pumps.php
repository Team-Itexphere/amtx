<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pumps extends Model
{
    use HasFactory;

    protected $fillable = ['pump_number', 'dispense_type', 'model', 'payment_type', 'installed_date', 'warr_info', 'purch_date', 'purch_from', 'store_id'];								

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }

    public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'pump_id');
    }
}
