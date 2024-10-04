<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testings extends Model
{
    use HasFactory;

    protected $fillable = ['route_list_id', 'ro_loc_id', 'cus_id', 'tech_id', 'status', 'type', 'invoice_id'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id'); //->where('role', 5)
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id'); //->where('role', 6)
    }

    public function route_list()
    {
        return $this->belongsTo(Route_lists::class, 'route_list_id');
    }
    
    public function ro_location()
    {
        return $this->belongsTo(Ro_locations::class, 'ro_loc_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }

    public function testing_meta()
    {
        return $this->hasMany(Testing_meta::class, 'testing_id');
    }
}
