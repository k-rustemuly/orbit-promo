<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrizeDrawingCalendar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'prize_id',
        'number',
        'drawing_at',
        'started_at',
        'is_finished',
    ];

    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
}
