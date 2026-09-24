<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'category_id',
        'user_id',
        'featured_image',
        'image_caption',
        'is_breaking',
        'is_featured',
        'is_trending',
        'is_editor_pick',
        'status',
        'views_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_breaking' => 'boolean',
            'is_featured' => 'boolean',
            'is_trending' => 'boolean',
            'is_editor_pick' => 'boolean',
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true)->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'published');
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true)->where('status', 'published');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            if (str_starts_with($this->featured_image, 'http')) {
                return $this->featured_image;
            }
            return asset('storage/' . $this->featured_image);
        }
        return 'https://picsum.photos/800/450?random=' . $this->id;
    }
}
