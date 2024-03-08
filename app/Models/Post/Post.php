<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'live_at',
        'workspace_id',
        'user_id'
    ];

    protected $dates = [
        'live_at', 'deleted_at',
    ];
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            $post->images()->delete();
        });

        static::creating(function ($post) {
            $post->workspace_id = request()->workspace_id;
        });

        static::updating(function ($post) {
            $post->user_id = request()->user_id;
        });
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    
}