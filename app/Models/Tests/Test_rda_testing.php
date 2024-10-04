<?php

namespace App\Models\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Test_rda_testing extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'tech_id', 'date', 'pdf_path'];

    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function testing_meta()
    {
        return $this->hasMany(Test_rda_testing_meta::class, 'testing_id');
    }
}
