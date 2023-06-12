<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'image_url'
    ];

    /**
     * Get the home that owns the games.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image && \Storage::disk('public')->exists($this->image)) {
            return \Storage::disk('public')->url($this->image);
        } else {
            return asset('img/photo-profile.png');
        }
    }
}
