<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintain_logs extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'cus_id', 'category', 'descript', 'des_problem', 'qty', 'rate', 'amount', 'date', 'location', 'tech_id', 'created_at', 'updated_at'];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id');
    }
    
    public function technician()
    {
        return $this->belongsTo(User::class, 'tech_id');
    }
}
