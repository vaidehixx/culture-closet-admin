<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    protected $table = 'admin_users';
    public $keyType = 'string';
    public $incrementing = false;
    protected $hidden = ['password'];
    protected $fillable = ['name', 'email', 'password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function getIsAdminAttribute(): bool
    {
        return true;
    }
}
