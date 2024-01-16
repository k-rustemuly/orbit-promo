<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prize extends LocalizableModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_ru',
        'name_kk',
        'name_uz',
        'bal',
        'number',
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
