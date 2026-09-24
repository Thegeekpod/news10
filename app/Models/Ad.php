<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'placement',
        'type',
        'image_path',
        'target_url',
        'custom_code',
        'is_active',
        'impressions',
        'clicks',
        'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'impressions' => 'integer',
            'clicks' => 'integer',
            'expiry_date' => 'date',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                  ->orWhere('expiry_date', '>=', now()->toDateString());
            });
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }
            return asset('storage/' . $this->image_path);
        }
        return null;
    }
}
