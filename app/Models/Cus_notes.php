<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cus_notes extends Model
{
    use HasFactory;

    protected $fillable = ['cus_id', 'note'];
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id');
    }
}
