<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testing_meta extends Model
{

    use HasFactory;

    protected $table = 'testing_meta';

    protected $fillable = ['testing_id', 'ques_id', 'answer', 'desc', 'file'];

    public function testing()
    {
        return $this->belongsTo(Testings::class, 'testing_id');
    }

}
