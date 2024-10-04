<?php

namespace App\Models\Tests;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test_rda_testing_meta extends Model
{

    use HasFactory;

    //protected $table = 'testing_meta';

    protected $fillable = ['testing_id', 'descript', 'meets_criteria', 'needs_action', 'action_taken'];

    public function testing()
    {
        return $this->belongsTo(Test_rda_testing::class, 'testing_id');
    }

}
