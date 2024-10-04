<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pictures extends Model
{

    use HasFactory;

    protected $table = 'pictures';

    protected $fillable = ['cus_id', 'list_id', 'type', 'image'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id');
    }

}
