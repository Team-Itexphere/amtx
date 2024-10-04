<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route_lists extends Model
{
    use HasFactory;

    protected $fillable = ['route_id', 'start_date', 'comp_date', 'tech_id', 'insp_type', 'status'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id'); //->where('role', 5)
    }

    public function route()
    {
        return $this->belongsTo(Routes::class, 'route_id'); 
    }
    
    public function testings()
    {
        return $this->hasMany(Testings::class, 'route_list_id'); 
    }
}
