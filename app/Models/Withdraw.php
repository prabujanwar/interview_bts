<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    public $incrementing = false;
    protected $table = 'withdraw';
    public $timestamps = true;
    protected $fillable = [
        'id', 'user_id', 'num_point', 'status', 'created_at', 'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
