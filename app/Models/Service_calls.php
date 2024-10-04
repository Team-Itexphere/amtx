<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_calls extends Model
{
    use HasFactory;

    protected $fillable = ['wo_id', 'status', 'tech_id', 'start_date', 'comp_date', 'comment', 'images'];

    public function work_order()
    {
        return $this->belongsTo(Work_orders::class, 'wo_id');
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }
}
