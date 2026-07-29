<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model {
    protected $table = 'reviews';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['rental_id','reviewer_id','reviewee_id','listing_id','rating','comment','status'];
    protected $casts = ['rating'=>'integer'];
    public function rental()   { return $this->belongsTo(Order::class, 'rental_id'); }
    public function order()    { return $this->rental(); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewee() { return $this->belongsTo(User::class, 'reviewee_id'); }
    public function product()  { return $this->belongsTo(Product::class, 'listing_id'); }
    // Alias: views may use $review->body
    public function getBodyAttribute(): string { return $this->comment ?? ''; }
}
