<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model {
    protected $table = 'categories';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['parent_id','name','slug','description','icon','is_active','sort_order'];
    protected $casts = ['is_active'=>'boolean'];
    public function parent()   { return $this->belongsTo(Category::class, 'parent_id'); }
    public function children() { return $this->hasMany(Category::class, 'parent_id'); }
    public function productCount(): int { return Product::where('type', $this->name)->orWhere('subcategory', 'like', "%{$this->name}%")->count(); }
}
