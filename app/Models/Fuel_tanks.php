<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel_tanks extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'capacity', 'installation_date', 'installed_by', 'tmc_path', 'store_id'];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }

    public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'fuel_tank_id');
    }
}
