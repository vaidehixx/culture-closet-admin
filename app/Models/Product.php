<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'listings';
    public $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'lender_id', 'name', 'type', 'subcategory', 'occasion',
        'size', 'colour', 'material', 'condition', 'rrp',
        'price_per_day', 'description', 'wear_tear_notes',
        'available', 'status', 'is_featured', 'reject_reason',
    ];

    protected $casts = [
        'available'   => 'boolean',
        'is_featured' => 'boolean',
        'subcategory' => 'array',
        'occasion'    => 'array',
        'who'         => 'array',
        'measurements'=> 'array',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    // Alias: views use $product->owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    // Alias: views use $product->owner for 'by X'
    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function images()
    {
        return $this->hasMany(ListingImage::class, 'listing_id')->orderBy('position');
    }

    public function scopePending($q)   { return $q->where('status', 'pending'); }
    public function scopeApproved($q)  { return $q->where('status', 'approved'); }
    public function scopeRejected($q)  { return $q->where('status', 'rejected'); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
}
