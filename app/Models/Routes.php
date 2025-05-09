<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;
    
    //public $timestamps = false;

    protected $fillable = ['num', 'name', 'insp_type', 'deleted'];

    public function ro_locations()
    {
        return $this->hasMany(Ro_locations::class, 'route_id');
    }
    
    public function route_lists()
    {
        return $this->hasMany(Route_lists::class, 'route_id');
    }
}
