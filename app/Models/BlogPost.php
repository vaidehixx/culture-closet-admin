<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class BlogPost extends Model {
    protected $table = 'blog_posts';
    public $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['title','slug','excerpt','body','category','featured_image','status','published_at'];
    protected $casts = ['published_at'=>'datetime'];
    public static function generateSlug(string $title): string {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
