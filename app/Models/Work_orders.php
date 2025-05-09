<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Work_orders extends Model
{
    use HasFactory;
    
    //public $timestamps = false;

    protected $fillable = ['wo_number', 'customer_id', 'status', 'tech_id', 'fleet_id', 'date', 'start_date', 'comp_date', 'comp_time', 'time', 'priority', 'comment', 'description', 'createdBy', 'invoiced'];

    public function service_calls()
    {
        return $this->hasMany(Service_calls::class, 'wo_id');
    }
    
    public function fleet()
    {
        return $this->belongsTo(Fleets::class, 'fleet_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id'); //->where('role', 6)
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id'); //->where('role', 5)
    }
    
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
