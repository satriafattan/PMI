<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_super_admin'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_super_admin' => 'boolean',
    ];

    /**
     * Check if the admin is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }
}
