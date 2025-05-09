<?php

namespace App\Models\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Test_cs_test extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'tech_id', 'date', 'items', 'pdf_path'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
