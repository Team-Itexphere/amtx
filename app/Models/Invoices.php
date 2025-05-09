<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;
    
    // public $timestamps = false;

    protected $fillable = ['invoice_no', 'date', 'customer_id', 'route_list_id', 'service', 'file_name', 'pay_opt', 'check_no', 'payment', 'po_no', 'mo_no', 'comment', 'paid_amount', 'addi_comments', 'signature', 'createdBy', 'updatedBy', 'created_at'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function invoice_items()
    {
        return $this->hasMany(Invoice_items::class, 'invoice_id');
    }
    
    public function maintain_logs()
    {
        return $this->hasMany(Maintain_logs::class, 'invoice_id');
    }

    public function testing()
    {
        return $this->hasOne(Testings::class, 'invoice_id');
    }
    
    public function route_list()
    {
        return $this->belongsTo(Route_lists::class, 'route_list_id');
    }
    
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
