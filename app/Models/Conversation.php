<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Conversation extends Model {
    protected $table = 'conversations';
    public $keyType = 'string';
    public $incrementing = false;
    const UPDATED_AT = null;
    protected $fillable = ['user_a','user_b','listing_id','last_message_at'];
    protected $casts = ['last_message_at'=>'datetime','created_at'=>'datetime'];
    public function user1() { return $this->belongsTo(User::class, 'user_a'); }
    public function user2() { return $this->belongsTo(User::class, 'user_b'); }
    public function product() { return $this->belongsTo(Product::class, 'listing_id'); }
    public function messages() { return $this->hasMany(Message::class); }
    public function latestMessage() { return $this->hasOne(Message::class)->orderByDesc('created_at'); }
}
