<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cus_sir_inv_docs extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'expire_date', 'doc_path', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
