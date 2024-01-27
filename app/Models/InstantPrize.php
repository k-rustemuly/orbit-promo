<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstantPrize extends LocalizableModel
{
    use HasFactory;
    use Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ru',
        'name_kk',
        'name_uz',
        'code',
        'draw_date',
        'winner_id',
        'winning_date',
    ];


    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'winning_date' => 'datetime',
    ];

    public function scopeNotWon(Builder $query): void
    {
        $query->whereNull('winner_id');
    }

    public function scopeWon(Builder $query): void
    {
        $query->whereNotNull('winner_id')->whereNotNull('winning_date');
    }

    public function scopeGift(Builder $query): void
    {
        $query->where('draw_date', '<=', now());
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
