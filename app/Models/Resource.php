<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'type',
        'category_id',
        'user_id',
        'is_public',
        'file_path',
        'resource_image_path',
        'file_extension',
        'approval_status',
        'section_id',
        'default_icon',
        'private_share_token',
        'is_visible',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function makeVisible($attributes)
    {
        $this->attributes['is_visible'] = true;

        return parent::makeVisible($attributes);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'resource_id', 'user_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shareLinks()
    {
        return $this->hasMany(ShareLink::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_public', true)->where('approval_status', 'pending');
    }
}
