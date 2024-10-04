<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_infos extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'fu_brand', 'truck_stop', 'dis_brand', 'dis_model', 'dis_sumps', 'dis_type', 'vents_count', 'h_many_3_0', 'h_many_3_1', 'h_many_h_flows', 'tanks_count', 'atg_brand', 'atg_sensors', 'relay_brand', 'pos_system', 'lock'];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
    
    public function site_info_tanks()
    {
        return $this->hasMany(Site_info_tanks::class, 'site_info_id');
    }
}
