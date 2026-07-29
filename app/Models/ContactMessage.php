<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ContactMessage extends Model {
    protected $table = 'contact_messages';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['name','email','subject','body','status','admin_notes','resolved_at'];
    protected $casts = ['resolved_at'=>'datetime'];
}
