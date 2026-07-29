<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'rentals';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'renter_id', 'lender_id', 'listing_id', 'status',
        'start_date', 'end_date', 'total_amount', 'subtotal',
        'platform_fee_amount', 'service_fee_amount',
        'insurance_plan', 'insurance_fee', 'cleaning_fee',
        'stripe_payment_intent_id',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'paid_at'     => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function renter()   { return $this->belongsTo(User::class, 'renter_id'); }
    public function borrower() { return $this->belongsTo(User::class, 'renter_id'); }
    public function lender()   { return $this->belongsTo(User::class, 'lender_id'); }
    public function product()  { return $this->belongsTo(Product::class, 'listing_id'); }
    public function reviews()  { return $this->hasMany(Review::class, 'rental_id'); }

    // Aliases for views
    public function getTotalAttribute()     { return $this->total_amount; }
    public function getBorrowerIdAttribute(){ return $this->renter_id; }
    public function getDaysAttribute(): int {
        if (!$this->start_date || !$this->end_date) return 0;
        return (int) $this->start_date->diffInDays($this->end_date) ?: 1;
    }
}
