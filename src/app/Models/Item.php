<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'price',
        'image_url',
        'description',
        'condition',
        'sold_out',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }


    public function getImageUrlAttribute($value)
    {

        if (str_starts_with($value, 'http')) {
            return $value;
        }


        return asset('storage/' . $value);
    }

}
