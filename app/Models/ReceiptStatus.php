<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceiptStatus extends LocalizableModel
{
    use HasFactory;

    const NOT_FOUND = 1;

    const ACCEPTED = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ru',
        'name_kk',
        'name_uz',
        'color',
    ];


    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'name'
    ];
}
