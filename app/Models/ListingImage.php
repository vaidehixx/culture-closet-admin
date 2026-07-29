<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ListingImage extends Model {
    protected $table = 'listing_images';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['listing_id','url','position'];
    public function listing() { return $this->belongsTo(Product::class, 'listing_id'); }
}
