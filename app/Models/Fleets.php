<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleets extends Model
{
    use HasFactory;

    protected $fillable = ['fleet_no', 'license_pl_no', 'vin_no', 'gas_type', 'start_millage', 'stop_millage', 'date'];

    /*public function store()
    {
        return $this->belongsTo(Stores::class);
    }*/

    public function inventorys()
    {
        return $this->hasMany(Inventorys::class);
    }
    
    public function fleet_routings()
    {
        return $this->hasMany(Fleet_routings::class, 'fleet_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'fleet_id');
    }
}
