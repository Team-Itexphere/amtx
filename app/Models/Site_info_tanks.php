<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site_info_tanks extends Model
{
    use HasFactory;

    protected $fillable = ['tank_name', 'fu_type', 'size', 'diameter', 'material', 'sb_brand', 'wall_type', 'drain', 'h_many_g_bucket', 'in_denpth', 'overfill_prev', 'vent_type', 'stp_manf', 'leak_detector', 'stp_sumps', 'stps_type', 'site_info_id'];

    public function site_info()
    {
        return $this->belongsTo(Site_infos::class, 'site_info_id');
    }
}
