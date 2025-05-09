<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = ['site_name', 'site_logo_path', 'favicon_path', 'country_code', 'main_numbers'];

}
