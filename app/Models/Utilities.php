<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilities extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'service_pro', 'avg_m_bill', 'end_date', 'acc_num', 'note', 'bill_path', 'store_id'];

    public function store()
    {
        return $this->belongsTo(Stores::class);
    }
}
