<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'purch_from', 'purch_date', 'cost', 'warr_info', 'serial', 'store_id'];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }

    public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'equipment_id');
    }
}
