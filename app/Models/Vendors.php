<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rep_name', 'phone', 'delivery', 'payment_type', 'acc_num', 'store_id'];								

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
}
