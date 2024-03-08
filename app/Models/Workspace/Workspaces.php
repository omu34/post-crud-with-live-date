<?php

namespace App\Models\Workspace;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspaces extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
        'support_email',
        'settings_flags',
        'feature_flags',
        'auto_resolve_duration',
        'auto_response_time',
        'confirmation_message',
        'absence_message',
        'address',
    ];

    public function user () {
        return $this->hasMany(User::class);
    }
}