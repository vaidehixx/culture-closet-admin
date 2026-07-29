<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Transaction extends Model {
    protected $table = 'transactions';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['rental_id','user_id','type','amount','status','reference','notes'];
    protected $casts = ['amount'=>'float'];
    public function rental() { return $this->belongsTo(Order::class, 'rental_id'); }
    public function order()  { return $this->rental(); }
    public function user()   { return $this->belongsTo(User::class); }
}
