<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'start_at',
        'end_at',
        'hours',
        'type',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
