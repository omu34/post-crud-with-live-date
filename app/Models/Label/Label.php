<?php

namespace App\Models\Label;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Label extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'color',
        'description',
        'status',
        'user_id',
        'workspaces_id'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($label) {
            $label->slug = Str::slug(request()->title, '_');
        });

        static::updating(function ($label) {
            $label->slug = Str::slug(request()->title, '_');
        });
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
