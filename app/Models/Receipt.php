<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;

class Receipt extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'receipt_status_id',
        'url'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ReceiptStatus::class, 'receipt_status_id');
    }

    public function scopeChecking(Builder $query): void
    {
        $query->where('receipt_status_id', ReceiptStatus::CHECKING);
    }

}
