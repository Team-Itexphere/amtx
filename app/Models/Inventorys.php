<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventorys extends Model
{
    use HasFactory;

    protected $fillable = ['part_no', 'serial', 'manufacturer', 'purchase_price', 'selling_price', 'warranty', 'category', 'fleet_id'];

    public function fleet()
    {
        return $this->belongsTo(Fleets::class);
    }

    /*public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'equipment_id');
    }*/
}
