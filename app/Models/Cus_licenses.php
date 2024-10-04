<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cus_licenses extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'expire_date', 'agency', 'doc_path', 'customer_id', 'remind_date'];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
