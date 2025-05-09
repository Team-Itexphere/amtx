<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ro_locations extends Model
{
    use HasFactory;
    
    //public $timestamps = false;

    protected $fillable = ['route_id', 'cus_id', 'amount'];

    public function route()
    {
        return $this->belongsTo(Routes::class, 'route_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id');
    }

    public function testings()
    {
        return $this->hasMany(Testings::class, 'ro_loc_id');
    }
}
