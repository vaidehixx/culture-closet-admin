<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CleaningItem extends Model {
    protected $table = 'cleaning_items';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['rental_id','listing_id','status','assigned_to','notes','completed_at'];
    protected $casts = ['completed_at'=>'datetime','created_at'=>'datetime','updated_at'=>'datetime'];
    public function rental()  { return $this->belongsTo(Order::class, 'rental_id'); }
    public function listing() { return $this->belongsTo(Product::class, 'listing_id'); }
    // Alias used in older views
    public function order()   { return $this->rental(); }
    public function product() { return $this->listing(); }
}
