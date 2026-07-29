<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Message extends Model {
    protected $table = 'messages';
    public $keyType = 'string';
    public $incrementing = false;
    const UPDATED_AT = null;
    protected $fillable = ['conversation_id','sender_id','text','type','read'];
    protected $casts = ['read'=>'boolean','created_at'=>'datetime'];
    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender() { return $this->belongsTo(User::class, 'sender_id'); }
    // Alias: views use $message->body
    public function getBodyAttribute(): string { return $this->text ?? ''; }
    // Alias: views check is_flagged
    public function getIsFlaggedAttribute(): bool { return false; }
    // SoftDelete compatibility shim
    public function delete(): ?bool { return parent::delete(); }
}
