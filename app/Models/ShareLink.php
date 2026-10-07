<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'token',
        'expires_at',
    ];

    /**
     * The resource that belongs to the ShareLink.
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
