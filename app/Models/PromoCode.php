<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PromoCode extends Model {
    protected $table = 'promo_codes';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['code','description','type','value','min_order','max_uses','used_count','expires_at','is_active'];
    protected $casts = ['expires_at'=>'date','is_active'=>'boolean','value'=>'float','min_order'=>'float'];
    public function isExpired(): bool { return $this->expires_at && $this->expires_at->isPast(); }
    public function isExhausted(): bool { return $this->max_uses && $this->used_count >= $this->max_uses; }
}
