<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'resource_id', 'action'];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
