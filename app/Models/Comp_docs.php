<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comp_docs extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'expire_date', 'doc_path', 'customer_id', 'remind_date'];

    public function customer()
    {
        return $this->belongsTo(User::class);
    }
}
