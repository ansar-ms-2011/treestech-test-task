<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'user_id',
        'assigned_to_id'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    const PRIORITY = ['low', 'medium', 'high'];
    const STATUS = ['pending', 'in_progress', 'completed', 'overdue'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assigned_to()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }
}
