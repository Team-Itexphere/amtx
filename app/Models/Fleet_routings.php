<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet_routings extends Model
{
    use HasFactory;

    protected $fillable = ['fleet_id', 'date', 'start_millage', 'stop_millage'];

    public function fleet()
    {
        return $this->belongsTo(Fleets::class);
    }

    /*public function inventorys()
    {
        return $this->hasMany(Inventorys::class);
    }*/
}
