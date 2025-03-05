<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SCHEDULED = 'scheduled';

    protected $fillable = ['title', 'content', 'status', 'published_at', 'user_id'];
    
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Hanya menampilkan post yang sudah dipublikasikan.
     * Scheduled posts tidak akan muncul sebelum waktunya.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->orWhere(function ($q) {
                         $q->where('status', self::STATUS_SCHEDULED)
                           ->where('published_at', '<=', now());
                     });
    }

    /**
     * Cek apakah post sudah diterbitkan.
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED ||
               ($this->status === self::STATUS_SCHEDULED && $this->published_at <= now());
    }

    /**
     * Otomatis ubah scheduled post ke published jika waktunya telah tiba.
     */
    public function setStatusAttribute($value)
    {
        if ($value === self::STATUS_SCHEDULED && $this->published_at && $this->published_at <= now()) {
            $this->attributes['status'] = self::STATUS_PUBLISHED;
        } else {
            $this->attributes['status'] = $value;
        }
    }

    /**
     * Label status untuk tampilan.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_SCHEDULED => 'Scheduled',
            default => 'Unknown',
        };
    }

    protected static function booted()
{
    static::retrieved(function ($post) {
        if ($post->status === 'scheduled' && $post->published_at && $post->published_at <= now()) {
            $post->update(['status' => 'published']);
        }
    });
}

}
