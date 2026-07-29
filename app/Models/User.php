<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'profiles';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name', 'email', 'phone', 'bio', 'avatar_url',
        'verified', 'coins', 'is_suspended', 'vacation_mode',
        'push_token', 'marketing_opt_in',
    ];

    protected function casts(): array
    {
        return [
            'verified'        => 'boolean',
            'is_suspended'    => 'boolean',
            'vacation_mode'   => 'boolean',
            'marketing_opt_in'=> 'boolean',
            'created_at'      => 'datetime',
            'updated_at'      => 'datetime',
            'dob'             => 'date',
        ];
    }

    // Alias for views that check email_verified_at
    public function getEmailVerifiedAtAttribute(): mixed
    {
        return $this->verified ? $this->created_at : null;
    }

    // Alias for views that check coin_balance
    public function getCoinBalanceAttribute(): int
    {
        return $this->coins ?? 0;
    }

    public function listings()
    {
        return $this->hasMany(Product::class, 'lender_id');
    }

    public function coinTransactions()
    {
        return $this->hasMany(CoinTransaction::class, 'user_id');
    }
}
