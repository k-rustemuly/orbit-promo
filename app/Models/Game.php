<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'before_life',
        'after_life',
        'before_coins',
        'coins',
        'after_coins',
        'before_level',
        'after_level',
        'instant_prize_id',
        'score',
        'time',
        'is_finished',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function instantPrize(): BelongsTo
    {
        return $this->belongsTo(InstantPrize::class);
    }

    public function getInstantGiftAttribute()
    {
        /** @var InstantPrize */
        if($prize = $this->instantPrize) {
            return is_null($prize?->code) ? 'box': 'mobi';
        }
        return 'none';
    }
}
