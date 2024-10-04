<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stores extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'fax', 'size', 'status', 'expenses', 'blue_print_path', 'doc_1_path', 'doc_2_path', 'llc', 'ein'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'stores_user', 'store_id', 'user_id');
    }

    public function equipments()
    {
        return $this->hasMany(Equipments::class, 'store_id');
    }
    
    public function fuel_tanks()
    {
        return $this->hasMany(Fuel_tanks::class, 'store_id');
    }
    
    public function pumps()
    {
        return $this->hasMany(Pumps::class, 'store_id');
    }

    public function store_licenses()
    {
        return $this->hasMany(Store_licenses::class, 'store_id');
    }

    public function work_orders()
    {
        return $this->hasMany(Work_orders::class, 'store_id');
    }

    public function tenants()
    {
        return $this->hasMany(Tenants::class, 'store_id');
    }

    public function utilities()
    {
        return $this->hasMany(Utilities::class, 'store_id');
    }
    
    public function vendors()
    {
        return $this->hasMany(Vendors::class, 'store_id');
    }
}
