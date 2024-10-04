<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_items extends Model
{
    use HasFactory;

    protected $fillable = ['invoice_id', 'item_name', 'category', 'descript', 'qty', 'rate', 'amount'];

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'invoice_id');
    }
}
