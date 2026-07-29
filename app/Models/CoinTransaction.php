<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CoinTransaction extends Model {
    protected $table = 'coin_transactions';
    public $keyType = 'string';
    public $incrementing = false;
    const UPDATED_AT = null;
    protected $fillable = ['user_id','action','amount','type','action_type'];
    protected $casts = ['amount'=>'integer','created_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function order() { return null; }
    // Alias: views use $tx->description
    public function getDescriptionAttribute(): string { return $this->action ?? ''; }
}
