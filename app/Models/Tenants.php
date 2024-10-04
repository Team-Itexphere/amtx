<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    use HasFactory;

    protected $fillable = ['address', 'size', 'rent', 'vacant', 'name', 'contact', 'agreement_path', 'insurance_path', 'dl_path', 'store_id'];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
}
