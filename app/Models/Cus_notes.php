<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cus_notes extends Model
{
    use HasFactory;

    protected $fillable = ['cus_id', 'note', 'status', 'reason', 'created_by', 'completed_by'];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
        
        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status === 'Completed') {
                $model->completed_by = Auth::id();
            }
        });
    }
    
    public function customer()
    {
        return $this->belongsTo(User::class, 'cus_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function completor()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
    
    public function getCreatorInfoAttribute()
    {
        if ($this->creator) {
            return $this->creator->role_label . ' (' . $this->creator->name . ')';
        }
        return 'N/A';
    }
    
    public function getCompletorInfoAttribute()
    {
        if ($this->completor) {
            return $this->completor->role_label . ' (' . $this->completor->name . ')';
        }
        return 'N/A';
    }
}
