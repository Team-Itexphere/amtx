<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Route_lists extends Model
{
    use HasFactory;
    
    //public $timestamps = false;

    protected $fillable = ['route_id', 'start_date', 'comp_date', 'tech_id', 'insp_type', 'status'];

    /*public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id'); //->where('role', 5)
    }*/

    public function route()
    {
        return $this->belongsTo(Routes::class, 'route_id'); 
    }
    
    public function testings()
    {
        return $this->hasMany(Testings::class, 'route_list_id'); 
    }
    
    public function invoices()
    {
        return $this->hasMany(Invoices::class, 'route_list_id'); 
    }
    
    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_route_lists', 'route_list_id', 'user_id');
    }
}
