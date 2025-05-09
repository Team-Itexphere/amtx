<?php

namespace App\Models\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Test_stage_1_test extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'tech_id', 'date', 'arrived_at', 'departed_at', 'gdf_name', 'gdf_address', 'gdf_phone', 'gdf_permit', 'gdf_fac_id', 'tank_noz_1', 'tank_noz_2', 'tank_noz_3', 'tank_noz_4', 'vapor_items', 'test_items', 'pv_data_items', 'last_items', 'pdf_path'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
