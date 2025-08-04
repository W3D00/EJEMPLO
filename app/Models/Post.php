<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(PostObserver::class)]

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'image_path', 'excerpt', 'content', 'is_published', 'published_at', 'user_id', 'category_id'];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    //Accesores
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_path ? Storage::url($this->image_path) : 'https://static.vecteezy.com/system/resources/previews/022/059/000/non_2x/no-image-available-icon-vector.jpg',
        );
    }

    //Routear los eventos de los modelos
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //Relaciones uno a muchos inversa
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    //Relaciones uno a muchos
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    //Relaciones muchos a muchos
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
